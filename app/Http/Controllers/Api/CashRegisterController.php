<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CashRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CashRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = CashRegister::select("*")->with(["user", "user.person"])->paginate();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'year' => 'required|numeric',
            'amount' => 'required|numeric',
            'opening_date' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $number = CashRegister::where('year', $request->year)->count() + 1;
        $number = str_pad($number, 2, "0", STR_PAD_LEFT);
        $data['number'] = $number;
        $data['user_id'] = auth()->user()->id;

        $cashRegister = CashRegister::create($data);
        return response()->json([
            "message" => "Caja aperturada correctamente.",
            "data" => $cashRegister
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
        // $cashRegister = CashRegister::where('id', $cashRegister->id);
        return response()->json($cashRegister);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CashRegister  $cashRegister
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CashRegister $cashRegister)
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

    public function close(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'id' => 'required|numeric',
            'closing_date' => 'required',
            'amount_to_pay' => 'required|numeric',
            'surrender_report' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        CashRegister::where('id', $request->id)->update(
            [
                'closing_date' => $request->closing_date,
                'amount_to_pay' => $request->amount_to_pay,
                'surrender_report' => $request->surrender_report,
                'closed' => 1,
            ]
        );

        return response()->json([
            "message" => "Caja cerrada correctamente.",
        ]);
    }
}
