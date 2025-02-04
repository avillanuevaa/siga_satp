<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ViaticRegisterDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
// use App\Models\ViaticRegisterDetailWarehouse;
use Illuminate\Support\Facades\DB;
use Exception;

class ViaticRegisterDetailController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    try {
      $data = $request->all();
      $validator = Validator::make($data, [
        'issue_date' => 'required|date',
        'issue_type' => 'required|numeric',
      ]);

      if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
      }

      $register = ViaticRegisterDetail::where('issue_type', $request->issue_type)->where('supplier_number', $request->supplier_number)->where('issue_serie', $request->issue_serie)->where('issue_number', $request->issue_number)->first();

      if ($register && $request->id == 0) {
        throw new Exception('Documento ya se encuentra registrado');
      }

      $viaticRegisterDetail = ViaticRegisterDetail::updateOrCreate(['id' => $request->id], $data);
      // DB::commit();
      return response()->json([
        "message" => "Detalle registrado correctamente.",
        "data" => $viaticRegisterDetail
      ]);
    } catch (\Exception $exp) {
      DB::rollBack();
      return response([
        'message' => $exp->getMessage(),
        'status' => 'failed'
      ], 400);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\ViaticRegisterDetail  $viaticRegisterDetail
   * @return \Illuminate\Http\Response
   */
  public function show(ViaticRegisterDetail $viaticRegisterDetail)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\ViaticRegisterDetail  $viaticRegisterDetail
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, ViaticRegisterDetail $viaticRegisterDetail)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\ViaticRegisterDetail  $viaticRegisterDetail
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
    ViaticRegisterDetail::where(array('id' => $id))->delete();
    return response()->json([
        "message" => "Documento eliminado correctamente."
    ]);
  }

  public function listByViaticRegisterId(Request $request)
  {
    $data = ViaticRegisterDetail::where('viatic_register_id', $request->viatic_register_id)
            ->paginate($request->per_page, ['*'], 'page', $request->pageNumber);
    return response()->json($data);
  }
}
