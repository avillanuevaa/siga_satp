<?php

namespace App\Http\Controllers\Admin;

use App\Models\OrderRegisterDetail;
use App\Models\OrderRegister;
use App\Models\Parameter;
use App\Models\TypeAsset;
use App\Models\Office;

use App\Http\Requests\OrderRegisterDetailRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderRegisterDetailController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(OrderRegister $orderRegister)
    {
        //

        $items = $orderRegister->details()
                            ->with('warehouses')
                            ->paginate(20);
        
                            // Calcular el índice continuo en todas las páginas
        $startIndex = (($items->currentPage() - 1) * 20) + 1;

        session(['previous_url_order_register_detail' => url()->full()]);

        return response()->view('admin.order_register_detail.index', compact('orderRegister', 'items', 'startIndex'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(OrderRegister $orderRegister)
    {
        //
        return response()->view('admin.order_register_detail.create', [
            'paymentReceiptsTypes' => Parameter::paymentReceiptsType(), //
            'identityCardTypes' => Parameter::identityCardType(), //scopeRequestsType
            'offices' => Office::getListOffices(),
            'goals' => Parameter::goalsType(),
            'measures' => Parameter::measuresType(),
            'typesAssets' => TypeAsset::getAllAssetsType(),
            'orderRegister' => $orderRegister

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRegisterDetailRequest $request, OrderRegister $orderRegister)
    {
        //
        try{
            $data = $request->all();

            DB::beginTransaction();

            $register = OrderRegisterDetail::where('issue_type', $request->issue_type)
                            ->where('supplier_number', $request->supplier_number)
                            ->where('issue_serie', $request->issue_serie)
                            ->where('issue_number', $request->issue_number)
                            ->first();

            if ($register) {
                return redirect()->back()->withErrors(['Documento ya se encuentra registrado'])->withInput();
            }

            $orderRegisterDetail = $orderRegister->details()->create($data);

            $warehouses = $request->warehouses;

            $orderRegisterDetail->warehouses()->delete();

            foreach ($warehouses as $item) {
                $orderRegisterDetail->warehouses()->create([
                    'package' => $item['package'],
                    'detail' => $item['detail'],
                    'measure' => $item['measure'],
                    'quantity' => $item['quantity'],
                    'unit_value' => $item['unit_value'],
                    'total' => ($item['quantity'] * $item['unit_value']),
                    'lesser_package' => ($item['lesser_package'] ? "1" : "0"),
                    'observation' => $item['observation'],

                ]);
            }


            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors([$e->getMessage()])->withInput();
        }

        return redirect()->to(session('previous_url_order_register_detail'))
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
     * @param  \App\Models\OrderRegisterDetail  $orderRegisterDetail
     * @return \Illuminate\Http\Response
     */
    public function show(OrderRegister $orderRegister, OrderRegisterDetail $orderRegisterDetail)
    {
        //
        $orderRegisterDetail->load('warehouses');
        $orderRegisterDetail['view'] = true;

        return response()->view('admin.order_register_detail.edit', [
            'paymentReceiptsTypes' => Parameter::paymentReceiptsType(), //
            'identityCardTypes' => Parameter::identityCardType(), //scopeRequestsType
            'offices' => Office::getListOffices(),
            'goals' => Parameter::goalsType(),
            'measures' => Parameter::measuresType(),
            'typesAssets' => TypeAsset::getAllAssetsType(),
            'orderRegisterDetail' => $orderRegisterDetail,
            'orderRegister' => $orderRegister
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OrderRegisterDetail  $orderRegisterDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(OrderRegister $orderRegister, OrderRegisterDetail $orderRegisterDetail)
    {
        //
        $orderRegisterDetail->load('warehouses');
        $orderRegisterDetail['view'] = $orderRegister->closed;
        
        return response()->view('admin.order_register_detail.edit', [
            'paymentReceiptsTypes' => Parameter::paymentReceiptsType(), //
            'identityCardTypes' => Parameter::identityCardType(), //scopeRequestsType
            'offices' => Office::getListOffices(),
            'goals' => Parameter::goalsType(),
            'measures' => Parameter::measuresType(),
            'typesAssets' => TypeAsset::getAllAssetsType(),
            'orderRegisterDetail' => $orderRegisterDetail,
            'orderRegister' => $orderRegister
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrderRegisterDetail  $orderRegisterDetail
     * @return \Illuminate\Http\Response
     */
    public function update(OrderRegisterDetailRequest $request, OrderRegister $orderRegister, OrderRegisterDetail $orderRegisterDetail)
    {
        //
        try{
            $data = $request->all();

            DB::beginTransaction();

            $register = OrderRegisterDetail::where('issue_type', $request->issue_type)
                            ->where('supplier_number', $request->supplier_number)
                            ->where('issue_serie', $request->issue_serie)
                            ->where('issue_number', $request->issue_number)
                            ->where('id', '!=' , $orderRegisterDetail->id)
                            ->first();

            if ($register) {
                return redirect()->back()->withErrors(['Documento ya se encuentra registrado'])->withInput();
            }

            $warehouses = $request->warehouses;

            $orderRegisterDetail->warehouses()->delete();

            foreach ($warehouses as $item) {
                $orderRegisterDetail->warehouses()->create([
                    'package' => $item['package'],
                    'detail' => $item['detail'],
                    'measure' => $item['measure'],
                    'quantity' => $item['quantity'],
                    'unit_value' => $item['unit_value'],
                    'total' => ($item['quantity'] * $item['unit_value']),
                    'lesser_package' => ($item['lesser_package'] ? "1" : "0"),
                    'observation' => $item['observation'],

                ]);
            }

            $orderRegisterDetail->update($data);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors([$e->getMessage()])->withInput();
        }

        return redirect()->to(session('previous_url_order_register_detail'))
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
     * @param  \App\Models\OrderRegisterDetail  $orderRegisterDetail
     * @return \Illuminate\Http\Response
     */
    //public function store(OrderRegisterDetailRequest $request, OrderRegister $orderRegister)
    public function destroy(OrderRegister $orderRegister, OrderRegisterDetail $orderRegisterDetail)
    {

        // Verifica si hay registros relacionados en almacén
        if (!$orderRegisterDetail->warehouses->isEmpty()) {
            return response()->json([
                "success" => false,
                "message" => "No se puede eliminar este detalle de caja debido a registros relacionados en almacén.",
            ], 500);
        }

        DB::beginTransaction(); // Inicia la transacción

        try {

            $orderRegisterDetail->delete();
            
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
