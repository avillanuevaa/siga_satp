<?php

namespace App\Http\Controllers\Admin;

use App\Models\CashRegisterDetail;
use App\Models\CashRegister;
use App\Models\Parameter;
use App\Models\TypeAsset;
use App\Models\Office;

use App\Http\Requests\CashRegisterDetailRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CashRegisterDetailController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CashRegister $cashRegister)
    {
        //
        $items = $cashRegister->details()
                            ->with('warehouses')
                            ->orderByRaw("issue_type, CASE WHEN issue_type = '89' THEN issue_number ELSE issue_date END")
                            ->paginate(20);
        
                            // Calcular el índice continuo en todas las páginas
        $startIndex = (($items->currentPage() - 1) * 20) + 1;

        session(['previous_url_cash_register_detail' => url()->full()]);

        return response()->view('admin.cash_register_detail.index', compact('cashRegister', 'items', 'startIndex'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CashRegister $cashRegister)
    {
        //

        return response()->view('admin.cash_register_detail.create', [
            'paymentReceiptsTypes' => Parameter::paymentReceiptsType(), //
            'identityCardTypes' => Parameter::identityCardType(), //scopeRequestsType
            'offices' => Office::getListOffices(),
            'goals' => Parameter::goalsType(),
            'measures' => Parameter::measuresType(),
            'typesAssets' => TypeAsset::getAllAssetsType(),
            'cashRegister' => $cashRegister

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CashRegisterDetailRequest $request, CashRegister $cashRegister)
    {
        //
        try{
            $data = $request->all();

            DB::beginTransaction();

            $register = CashRegisterDetail::where('issue_type', $request->issue_type)
                            ->where('supplier_number', $request->supplier_number)
                            ->where('issue_serie', $request->issue_serie)
                            ->where('issue_number', $request->issue_number)
                            ->first();

            if ($register) {
                return redirect()->back()->withErrors(['Documento ya se encuentra registrado'])->withInput();
            }

            $cashRegisterDetail = $cashRegister->details()->create($data);

            $warehouses = $request->warehouses;

            $cashRegisterDetail->warehouses()->delete();

            foreach ($warehouses as $item) {
                $cashRegisterDetail->warehouses()->create([
                    'package' => $item['package'],
                    'package_text' => $item['package_text'],
                    'detail' => $item['detail'],
                    'measure' => $item['measure'],
                    'quantity' => $item['quantity'],
                    'unit_value' => $item['unit_value'],
                    'total' => ($item['quantity'] * $item['unit_value']),
                    'lesser_package' => ($item['lesser_package'] ? "1" : "0"),

                ]);
            }


            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors([$e->getMessage()])->withInput();
        }

        return redirect()->to(session('previous_url_cash_register_detail'))
                ->with([
                    'notif' => [
                        'message' => 'Detalle registrado satisfactoriamente.',
                        'icon' => 'success'
                    ],
                ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CashRegisterDetail  $cashRegisterDetail
     * @return \Illuminate\Http\Response
     */
    public function show(CashRegister $cashRegister, CashRegisterDetail $cashRegisterDetail)
    {
        //
        $cashRegisterDetail->load('warehouses');
        $cashRegisterDetail['view'] = true;

        return response()->view('admin.cash_register_detail.edit', [
            'paymentReceiptsTypes' => Parameter::paymentReceiptsType(), //
            'identityCardTypes' => Parameter::identityCardType(), //scopeRequestsType
            'offices' => Office::getListOffices(),
            'goals' => Parameter::goalsType(),
            'measures' => Parameter::measuresType(),
            'typesAssets' => TypeAsset::getAllAssetsType(),
            'cashRegisterDetail' => $cashRegisterDetail,
            'cashRegister' => $cashRegister
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CashRegisterDetail  $cashRegisterDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(CashRegister $cashRegister, CashRegisterDetail $cashRegisterDetail)
    {
        //

        $cashRegisterDetail->load('warehouses');
        $cashRegisterDetail['view'] = $cashRegister->closed;

        
        return response()->view('admin.cash_register_detail.edit', [
            'paymentReceiptsTypes' => Parameter::paymentReceiptsType(), //
            'identityCardTypes' => Parameter::identityCardType(), //scopeRequestsType
            'offices' => Office::getListOffices(),
            'goals' => Parameter::goalsType(),
            'measures' => Parameter::measuresType(),
            'typesAssets' => TypeAsset::getAllAssetsType(),
            'cashRegisterDetail' => $cashRegisterDetail,
            'cashRegister' => $cashRegister
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CashRegisterDetail  $cashRegisterDetail
     * @return \Illuminate\Http\Response
     */
    public function update(CashRegisterDetailRequest $request, CashRegister $cashRegister, CashRegisterDetail $cashRegisterDetail)
    {
        //
        try{
            $data = $request->all();

            DB::beginTransaction();

            $register = CashRegisterDetail::where('issue_type', $request->issue_type)
                            ->where('supplier_number', $request->supplier_number)
                            ->where('issue_serie', $request->issue_serie)
                            ->where('issue_number', $request->issue_number)
                            ->where('id', '!=' , $cashRegisterDetail->id)
                            ->first();

            if ($register) {
                return redirect()->back()->withErrors(['Documento ya se encuentra registrado'])->withInput();
            }

            $warehouses = $request->warehouses;

            $cashRegisterDetail->warehouses()->delete();

            foreach ($warehouses as $item) {
                $cashRegisterDetail->warehouses()->create([
                    'package' => $item['package'],
                    'package_text' => $item['package_text'],
                    'detail' => $item['detail'],
                    'measure' => $item['measure'],
                    'quantity' => $item['quantity'],
                    'unit_value' => $item['unit_value'],
                    'total' => ($item['quantity'] * $item['unit_value']),
                    'lesser_package' => ($item['lesser_package'] ? "1" : "0"),

                ]);
            }

            $cashRegisterDetail->update($data);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors([$e->getMessage()])->withInput();
        }

        return redirect()->to(session('previous_url_cash_register_detail'))
                ->with([
                    'notif' => [
                        'message' => 'Detalle actualizado satisfactoriamente.',
                        'icon' => 'success'
                    ],
                ]);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CashRegisterDetail  $cashRegisterDetail
     * @return \Illuminate\Http\Response
     */
    //public function store(CashRegisterDetailRequest $request, CashRegister $cashRegister)
    public function destroy(CashRegister $cashRegister, CashRegisterDetail $cashRegisterDetail)
    {

        // Verifica si hay registros relacionados en almacén
        if (!$cashRegisterDetail->warehouses->isEmpty()) {
            return response()->json([
                "success" => false,
                "message" => "No se puede eliminar este detalle de caja debido a registros relacionados en almacén.",
            ], 500);
        }

        DB::beginTransaction(); // Inicia la transacción

        try {

            $cashRegisterDetail->delete();
            
            DB::commit();

            return response()->json([
                "success" => true,
            ]);
            
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => $th->getMessage()
            ], 500);
            
        }

    }
}
