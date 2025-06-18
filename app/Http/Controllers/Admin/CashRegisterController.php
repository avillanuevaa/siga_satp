<?php

namespace App\Http\Controllers\Admin;

use App\Models\CashRegister;
use App\Http\Requests\CashRegisterRequest;
use App\Http\Requests\CashRegisterCloseRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CashRegisterController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = CashRegister::select("*")
                ->with("user.person");

            session(['previous_url_cash_register' => url()->full()]);

            $datatable = DataTables::of($query)
                ->addColumn('year', fn($u) => $u->year ?? '')
                ->addColumn('number', fn($u) => $u->number ?? '')
                ->addColumn('document_number', fn($u) => $u->user?->person?->document_number ?? '')
                ->addColumn('responsible', fn($u) => trim(($u->user?->person?->name ?? '') . ' ' . ($u->user?->person?->lastname ?? '')))
                ->addColumn('amount', fn($u) => 'S/. ' . number_format($u->amount, 2))
                ->addColumn('opening_date', fn($u) => Carbon::parse($u->opening_date)->format('d/m/Y'))
                ->addColumn('close_date', fn($u) => $u->closing_date ? Carbon::parse($u->closing_date)->format('d/m/Y') : '')
                ->addColumn('status', function($u) {
                    return $u->closed == 1 ? '<span class="badge bg-danger">Cerrada</span>' : '<span class="badge bg-success">Abierta</span>';
                })
                ->addColumn('action', function($u) {
                    $seeDetailsURL = route('cashRegisterDetails.index', $u->id);
                    $closeBox = '';
                    $seeDetails = '<a href="' . $seeDetailsURL . '" class="btn btn-info"><span class="fa fa-arrow-right"></span></a>';

                    if (!$u->closed) {
                        $closeBox = '<button type="button" class="btn btn-danger" data-toggle="modal" data-id="' . $u->id . '" data-target="#onCloseCashRegister"><i class="fa fa-times"></i></button>';
                    }

                    $print = '<button type="button" class="btn btn-warning btn-onPrint" data-id="' . $u->id . '" data-number="' . $u->number . '"><i class="fa fa-print"></i></button>';

                    return "<div class='btn-group'>{$print}{$closeBox}{$seeDetails}</div>";
                })
                ->rawColumns(['status', 'action'])
                ->filterColumn('year', function($query, $keyword) {
                    $query->where('year', 'like', "%{$keyword}%");
                })
                ->filterColumn('document_number', function($query, $keyword) {
                    $query->whereHas('user.person', function($q) use ($keyword) {
                        $q->where('document_number', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('responsible', function($query, $keyword) {
                    $query->whereHas('user.person', function($q) use ($keyword) {
                        $q->whereRaw("CONCAT(name, ' ', lastname) like ?", ["%{$keyword}%"]);
                    });
                })
                // CORRECCIÓN PRINCIPAL: Filtro de estado
                ->filterColumn('status', function($query, $keyword) {
                    if ($keyword === '0') {
                        $query->where('closed', 0);
                    } elseif ($keyword === '1') {
                        $query->where('closed', 1);
                    }
                })

                // Filtro global corregido
                ->filter(function ($query) use ($request) {
                    $search = $request->input('search.value');
                    if (!empty($search)) {
                        $query->where(function($q) use ($search) {
                            $q->where('year', 'like', "%{$search}%")
                                ->orWhere('number', 'like', "%{$search}%")
                                ->orWhereHas('user.person', function($subQ) use ($search) {
                                    $subQ->where('document_number', 'like', "%{$search}%")
                                        ->orWhereRaw("CONCAT(name, ' ', lastname) like ?", ["%{$search}%"]);
                                });
                        });
                    }
                })
                ->toJson();

            return $datatable;
        }

        return view('admin.cash_register.index');
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
