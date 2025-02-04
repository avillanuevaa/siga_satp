<?php

namespace App\Http\Controllers\Admin;

use App\Models\CashRegister;
use App\Http\Requests\CashRegisterRequest;
use App\Http\Requests\CashRegisterCloseRequest;

class CashRegisterController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = CashRegister::select("*")
                        ->with("user")
                        ->with("user.person")
                        ->paginate(20);


        session(['previous_url_cash_register' => url()->full()]);

        return response()->view('admin.cash_register.index', [
            'items' => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $responsible = auth()->user();

        return response()->view('admin.cash_register.create', compact('responsible'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CashRegisterRequest $request)
    {
        //

        try{
            $data = $request->all();

            $number = CashRegister::where('year', $request->year)->count() + 1;
            $number = str_pad($number, 2, "0", STR_PAD_LEFT);
            $data['number'] = $number;
            $data['user_id'] = auth()->user()->id;

            CashRegister::create($data);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()])->withInput();
        }

        return redirect()->to(session('previous_url_cash_register'))
                ->with([
                    'notif' => [
                        'message' => 'Caja aperturada correctamente',
                        'icon' => 'success'
                    ],
                ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CashRegister  $cashRegister
     * @return \Illuminate\Http\Response
     */
    public function show(CashRegister $cashRegister)
    {
        //
        $cashRegister->makeHidden(['user', 'person']);

        $cashRegister->cash_register_id = encrypt($cashRegister->id);
        $responsibleName = $cashRegister?->user?->person?->name .' ' . $cashRegister?->user?->person?->lastname;
        $responsibleDocumentNumber = $cashRegister?->user?->person?->document_number;
        $cashRegister->responsible = "$responsibleDocumentNumber - $responsibleName";

        return response()->json($cashRegister);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CashRegister  $cashRegister
     * @return \Illuminate\Http\Response
     */
    public function edit(CashRegister $cashRegister)
    {
        //

        
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CashRegister  $cashRegister
     * @return \Illuminate\Http\Response
     */
    public function update(CashRegisterRequest $request, CashRegister $cashRegister)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CashRegister  $cashRegister
     * @return \Illuminate\Http\Response
     */
    public function destroy(CashRegister $cashRegister)
    {
        //
    }

    public function close(CashRegisterCloseRequest $request)
    {
        $data = $request->all();

        CashRegister::where('id', $data['cash_register_id'])->update(
            [
                'closing_date' => $data['closing_date'],
                'amount_to_pay' => $data['amount_to_pay'],
                'surrender_report' => $data['surrender_report'],
                'closed' => 1,
            ]
        );

        session()->flash('notif', [
            'message' => 'Caja cerrada correctamente',
            'icon' => 'success'
        ]);

        return response()->json([
            'success' => true,
            "message" => "Caja cerrada correctamente.",
        ]);
    }
}
