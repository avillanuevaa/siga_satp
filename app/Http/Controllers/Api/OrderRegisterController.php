<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderRegisterController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $data = OrderRegister::select("*")->with("settlement")->with(["user", "user.person"])->paginate();
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
      'number' => 'required|numeric',
      'opening_date' => 'required',
      'settlement_id' => 'required',
      'siaf_date' => 'required',
      'siaf_number' => 'required|numeric',
      'voucher_date' => 'required',
      'voucher_number' => 'required|numeric',
      'order_pay_electronic_date' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(['error' => $validator->errors()], 400);
    }

    $data['user_id'] = auth()->user()->id;
    $orderRegister = OrderRegister::create($data);
    return response()->json([
      "message" => "Encargo aperturado correctamente.",
      "data" => $orderRegister
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
    // $orderRegister = OrderRegister::where('id', $orderRegister->id);
    return response()->json($orderRegister);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\OrderRegister  $orderRegister
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, OrderRegister $orderRegister)
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

  public function close(Request $request)
  {
    $data = $request->all();
    $validator = Validator::make($data, [
      'id' => 'required|numeric',
      'closing_date' => 'required',
      'amount_to_pay' => 'required|numeric',
      'amount_to_returned' => 'required|numeric',
      'surrender_report' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(['error' => $validator->errors()], 400);
    }

    OrderRegister::where('id', $request->id)->update(
      [
        'closing_date' => $request->closing_date,
        'amount_to_pay' => $request->amount_to_pay,
        'amount_to_returned' => $request->amount_to_returned,
        'surrender_report' => $request->surrender_report,
        'closed' => 1,
      ]
    );

    return response()->json([
      "message" => "Rendición cerrada correctamente.",
    ]);
  }
}
