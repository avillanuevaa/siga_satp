<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ViaticRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ViaticRegisterController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $data = ViaticRegister::select("*")->with("settlement")->with(["user", "user.person"])->paginate();
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
    $viaticRegister = ViaticRegister::create($data);
    return response()->json([
      "message" => "Encargo apreturado correctamente.",
      "data" => $viaticRegister
    ]);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\ViaticRegister  $viaticRegister
   * @return \Illuminate\Http\Response
   */
  public function show(ViaticRegister $viaticRegister)
  {
    // $viaticRegister = ViaticRegister::where('id', $viaticRegister->id);
    return response()->json($viaticRegister);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\ViaticRegister  $viaticRegister
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, ViaticRegister $viaticRegister)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\ViaticRegister  $viaticRegister
   * @return \Illuminate\Http\Response
   */
  public function destroy(ViaticRegister $viaticRegister)
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

    ViaticRegister::where('id', $request->id)->update(
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

  public function affidavit(Request $request)
  {
    $data = $request->all();
    $validator = Validator::make($data, [
      'id' => 'required|numeric',
      'affidavit_description_lost_documents' => 'required',
      'affidavit_amount_lost_documents' => 'required|numeric',
      'affidavit_amount_undocumented_expenses' => 'required|numeric',
    ]);

    if ($validator->fails()) {
      return response()->json(['error' => $validator->errors()], 400);
    }

    ViaticRegister::where('id', $request->id)->update(
      [
        'affidavit_description_lost_documents' => $request->affidavit_description_lost_documents,
        'affidavit_amount_lost_documents' => $request->affidavit_amount_lost_documents,
        'affidavit_amount_undocumented_expenses' => $request->affidavit_amount_undocumented_expenses
      ]
    );

    return response()->json([
      "message" => "Declaración Jurada registrada satisfactoriamente.",
    ]);
  }

  public function registerServiceComissionReport(Request $request)
  {
    $data = $request->all();
    $validator = Validator::make($data, [
      'id' => 'required|numeric',
      'service_commission_a' => 'required',
      'service_commission_from' => 'required',
      'service_commission_date' => 'required',
      'service_commission_activities_performed' => 'required',
      'service_commission_results_obtained' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(['error' => $validator->errors()], 400);
    }

    ViaticRegister::where('id', $request->id)->update(
      [
        'service_commission_a' => $request->service_commission_a,
        'service_commission_from' => $request->service_commission_from,
        'service_commission_date' => $request->service_commission_date,
        'service_commission_activities_performed' => $request->service_commission_activities_performed,
        'service_commission_results_obtained' => $request->service_commission_results_obtained
      ]
    );

    return response()->json([
      "message" => "Informe de Comisión de Servicio registrado satisfactoriamente.",
    ]);
  }

  
}
