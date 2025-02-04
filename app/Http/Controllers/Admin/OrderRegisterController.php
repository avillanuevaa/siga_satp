<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Arr;

use App\Models\OrderRegister;
use App\Models\Settlement;
use Illuminate\Http\Request;
use App\Http\Requests\OrderRegisterRequest;
use App\Http\Requests\OrderRegisterCloseRequest;
use Illuminate\Support\Facades\Validator;


class OrderRegisterController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = OrderRegister::select("*")
                        ->with("settlement")
                        ->with("user")
                        ->with("user.person")
                        ->paginate(20);

        session(['previous_url_order_register' => url()->full()]);

        return response()->view('admin.order_register.index', [
            'items' => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        if ($request->has(['correlative_search', 'year_search'])) {
        
            $validator = Validator::make($request->all(), [
                'correlative_search' => 'required|numeric',
                'year_search' => 'required|numeric',
            ]);
            
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $correlative = $request->input('correlative_search');
            $year = $request->input('year_search');
            $requestTypeId = 1;

            $settlement = Settlement::select('*')
                    ->with('settlementClassifier')
                    ->with('requestFile')
                    ->with('requestFile.person')
                    ->where('settlements.request_type', $requestTypeId)
                    ->where('settlements.number_correlative', $correlative)
                    ->where('year', $year)
                    ->first();


            if ($settlement) {
                $orderRegister = new OrderRegister;
                $orderRegister->fill(Arr::except($settlement->getAttributes(), ['id']));
                $orderRegister->settlement_id = encrypt($settlement->id);
                $orderRegister->number = $settlement->number_correlative;
                $orderRegister->approved_amount = $settlement->approved_amount;
                $orderRegister->responsible = ($settlement->person->document_number ?? '') . ' - '. ($settlement->person->name ?? '') . ' ' . ($settlement->person->lastname ?? '');
                $orderRegister->authorization_date = $settlement->authorization_date;
                $orderRegister->authorization_detail = $settlement->authorization_detail;
                $orderRegister->reason = $settlement->reason;
                
                return response()->view('admin.order_register.create', [
                    'orderRegister' => $orderRegister,
                    'searchRequest' => json_decode(json_encode($request->all()), false),
                ]);

            }else{
                $notFound = true;
            }
        }

        return response()->view('admin.order_register.create', [
            'searchRequest' => json_decode(json_encode($request->all()), false),
            'notFound' => $notFound ?? false
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRegisterRequest $request)
    {
        //
        $data = $request->all();
        $settlement = Settlement::find($data['settlement_id']);
        $data['year'] = $settlement->year;
        $data['number'] = $settlement->number_correlative;
        $data['user_id'] = auth()->user()->id;
        OrderRegister::create($data);
        return redirect()->to(session('previous_url_order_register'))
                ->with([
                    'notif' => [
                        'message' => 'Encargo aperturado correctamente',
                        'icon' => 'success'
                    ],
                ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OrderRegister  $orderRegister
     * @return \Illuminate\Http\Response
     */
    public function show(OrderRegister $orderRegister)
    {
        //
        $orderRegister->makeHidden(['user', 'person', 'settlement']);

        $orderRegister->cash_register_id = encrypt($orderRegister->id);
        $responsibleName = $orderRegister?->user?->person?->name .' ' . $orderRegister?->user?->person?->lastname;
        $responsibleDocumentNumber = $orderRegister?->user?->person?->document_number;
        $orderRegister->responsible = "$responsibleDocumentNumber - $responsibleName";
        $orderRegister->approved_amount = $orderRegister?->settlement?->approved_amount;
        $orderRegister->authorization_date = $orderRegister?->settlement?->authorization_date;
        $orderRegister->authorization_detail = $orderRegister?->settlement?->authorization_detail;
        $orderRegister->reason = $orderRegister?->settlement?->reason;

        return response()->json($orderRegister);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OrderRegister  $orderRegister
     * @return \Illuminate\Http\Response
     */
    public function edit(OrderRegister $orderRegister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrderRegister  $orderRegister
     * @return \Illuminate\Http\Response
     */
    public function update(OrderRegisterRequest $request, OrderRegister $orderRegister)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrderRegister  $orderRegister
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderRegister $orderRegister)
    {
        //
    }

    public function close(OrderRegisterCloseRequest $request)
    {
        $data = $request->all();

        OrderRegister::where('id', $data['order_register_id'])->update(
            [
                'closing_date' => $data['closing_date'],
                'amount_to_pay' => $data['amount_to_pay'],
                'amount_to_returned' => $data['amount_to_returned'],
                'surrender_report' => $data['surrender_report'],
                'closed' => 1,
            ]
        );

        session()->flash('notif', [
            'message' => 'Rendición cerrada correctamente',
            'icon' => 'success'
        ]);

        return response()->json([
            'success' => true,
            "message" => "Rendición cerrada correctamente.",
        ]);
    }
}
