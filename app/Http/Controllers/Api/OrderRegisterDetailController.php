<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\OrderRegisterDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\OrderRegisterDetailWarehouse;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderRegisterDetailController extends Controller
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

      $register = OrderRegisterDetail::where('issue_type', $request->issue_type)->where('supplier_number', $request->supplier_number)->where('issue_serie', $request->issue_serie)->where('issue_number', $request->issue_number)->first();

      if ($register && $request->id == 0) {
        throw new Exception('Documento ya se encuentra registrado');
      }

      DB::beginTransaction();

      $orderRegisterDetail = OrderRegisterDetail::updateOrCreate(['id' => $request->id], $data);

      $arrayItemsWarehouse = $request->arrayItemsWarehouse;

      foreach ($arrayItemsWarehouse as &$value) {
        $value["order_register_detail_id"] = $orderRegisterDetail->id;
        $value["created_at"] = date('Y-m-d H:i:s');
        $value["updated_at"] = date('Y-m-d H:i:s');
        $value["total"] = $value['quantity'] * $value['unit_value'];
      }

      OrderRegisterDetailWarehouse::where('order_register_detail_id', $orderRegisterDetail->id)->delete();
      OrderRegisterDetailWarehouse::insert($arrayItemsWarehouse);

      DB::commit();
      return response()->json([
        "message" => "Detalle registrado correctamente.",
        "data" => $orderRegisterDetail
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
   * @param  \App\Models\OrderRegisterDetail  $orderRegisterDetail
   * @return \Illuminate\Http\Response
   */
  public function show(OrderRegisterDetail $orderRegisterDetail)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\OrderRegisterDetail  $orderRegisterDetail
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, OrderRegisterDetail $orderRegisterDetail)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\OrderRegisterDetail  $orderRegisterDetail
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
    OrderRegisterDetailWarehouse::where('order_register_detail_id', $id)->delete();
    OrderRegisterDetail::where(array('id' => $id))->delete();
    return response()->json([
        "message" => "Registro eliminado correctamente."
    ]);
  }

  public function listByOrderRegisterId(Request $request)
  {
    $data = OrderRegisterDetail::where('order_register_id', $request->order_register_id)->with('warehouses')
            ->paginate($request->per_page, ['*'], 'page', $request->pageNumber); 
    return response()->json($data);
  }
}
