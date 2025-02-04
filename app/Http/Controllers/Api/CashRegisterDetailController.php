<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\CashRegisterDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\CashRegisterDetailWarehouse;
use Illuminate\Support\Facades\DB;
use Exception;

class CashRegisterDetailController extends Controller
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

            $register = CashRegisterDetail::where('issue_type', $request->issue_type)->where('supplier_number', $request->supplier_number)->where('issue_serie', $request->issue_serie)->where('issue_number', $request->issue_number)->first();

            if ($register && $request->id == 0) {
                throw new Exception('Documento ya se encuentra registrado');
            }

            DB::beginTransaction();

            $cashRegisterDetail = CashRegisterDetail::updateOrCreate(['id' => $request->id], $data);

            $arrayItemsWarehouse = $request->arrayItemsWarehouse;

            foreach ($arrayItemsWarehouse as &$value) {
                $value["cash_register_detail_id"] = $cashRegisterDetail->id;
                $value["created_at"] = date('Y-m-d H:i:s');
                $value["updated_at"] = date('Y-m-d H:i:s');
                $value["total"] = $value['quantity'] * $value['unit_value'];
            }

            CashRegisterDetailWarehouse::where('cash_register_detail_id', $cashRegisterDetail->id)->delete();
            CashRegisterDetailWarehouse::insert($arrayItemsWarehouse);

            DB::commit();
            return response()->json([
                "message" => "Detalle registrado correctamente.",
                "data" => $cashRegisterDetail
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
     * @param  \App\Models\CashRegisterDetail  $cashRegisterDetail
     * @return \Illuminate\Http\Response
     */
    public function show(CashRegisterDetail $cashRegisterDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CashRegisterDetail  $cashRegisterDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CashRegisterDetail $cashRegisterDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CashRegisterDetail  $cashRegisterDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        CashRegisterDetailWarehouse::where('cash_register_detail_id', $id)->delete();
        CashRegisterDetail::where(array('id' => $id))->delete();
        return response()->json([
            "message" => "Registro eliminado correctamente."
        ]);
    }

    public function listByCashRegisterId(Request $request)
    {
        $data = CashRegisterDetail::where('cash_register_id', $request->cash_register_id)->with('warehouses')
            ->orderByRaw("issue_type, CASE WHEN issue_type = '89' THEN issue_number ELSE issue_date END")
            ->paginate($request->per_page, ['*'], 'page', $request->pageNumber);
        return response()->json($data);
    }
}
