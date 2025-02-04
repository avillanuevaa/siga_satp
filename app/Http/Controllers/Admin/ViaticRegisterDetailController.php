<?php

namespace App\Http\Controllers\Admin;

use App\Models\ViaticRegisterDetail;
use App\Models\ViaticRegister;
use App\Models\Parameter;
use App\Models\TypeAsset;
use App\Models\Office;

use App\Http\Requests\ViaticRegisterDetailRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ViaticRegisterDetailController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ViaticRegister $viaticRegister)
    {
        //


        $items = $viaticRegister->details()->paginate(20);

        // Calcular el índice continuo en todas las páginas
        $startIndex = (($items->currentPage() - 1) * 20) + 1;

        session(['previous_url_viatic_register_detail' => url()->full()]);

        return response()->view('admin.viatic_register_detail.index', compact('viaticRegister', 'items', 'startIndex'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ViaticRegister $viaticRegister)
    {
        //
        return response()->view('admin.viatic_register_detail.create', [
            'paymentReceiptsTypes' => Parameter::paymentReceiptsType(), //
            'identityCardTypes' => Parameter::identityCardType(), //scopeRequestsType
            'offices' => Office::getListOffices(),
            'goals' => Parameter::goalsType(),
            'measures' => Parameter::measuresType(),
            'typesAssets' => TypeAsset::getAllAssetsType(),
            'viaticRegister' => $viaticRegister

        ]);
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ViaticRegisterDetailRequest $request, ViaticRegister $viaticRegister)
    {
        //
        try{

            $data = $request->all();

            DB::beginTransaction();

            $register = ViaticRegisterDetail::where('issue_type', $request->issue_type)
                            ->where('supplier_number', $request->supplier_number)
                            ->where('issue_serie', $request->issue_serie)
                            ->where('issue_number', $request->issue_number)
                            ->first();

            if ($register) {
                return redirect()->back()->withErrors(['Documento ya se encuentra registrado'])->withInput();
            }

            $viaticRegister->details()->create($data);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors([$e->getMessage()])->withInput();
        }

        return redirect()->to(session('previous_url_viatic_register_detail'))
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
     * @param  \App\Models\ViaticRegisterDetail  $viaticRegisterDetail
     * @return \Illuminate\Http\Response
     */
    public function show(ViaticRegister $viaticRegister, ViaticRegisterDetail $viaticRegisterDetail)
    {
        //
        $viaticRegisterDetail['view'] = true;
        
        return response()->view('admin.viatic_register_detail.edit', [
            'paymentReceiptsTypes' => Parameter::paymentReceiptsType(), //
            'identityCardTypes' => Parameter::identityCardType(), //scopeRequestsType
            'offices' => Office::getListOffices(),
            'goals' => Parameter::goalsType(),
            'measures' => Parameter::measuresType(),
            'typesAssets' => TypeAsset::getAllAssetsType(),
            'viaticRegisterDetail' => $viaticRegisterDetail,
            'viaticRegister' => $viaticRegister
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ViaticRegisterDetail  $viaticRegisterDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(ViaticRegister $viaticRegister, ViaticRegisterDetail $viaticRegisterDetail)
    {
        //
        $viaticRegisterDetail['view'] = $viaticRegister->closed;
        
        return response()->view('admin.viatic_register_detail.edit', [
            'paymentReceiptsTypes' => Parameter::paymentReceiptsType(), //
            'identityCardTypes' => Parameter::identityCardType(), //scopeRequestsType
            'offices' => Office::getListOffices(),
            'goals' => Parameter::goalsType(),
            'measures' => Parameter::measuresType(),
            'typesAssets' => TypeAsset::getAllAssetsType(),
            'viaticRegisterDetail' => $viaticRegisterDetail,
            'viaticRegister' => $viaticRegister
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ViaticRegisterDetail  $viaticRegisterDetail
     * @return \Illuminate\Http\Response
     */
    public function update(ViaticRegisterDetailRequest $request, ViaticRegister $viaticRegister, ViaticRegisterDetail $viaticRegisterDetail)
    {
        //

        //
        try{
            $data = $request->all();

            DB::beginTransaction();

            $register = ViaticRegisterDetail::where('issue_type', $request->issue_type)
                            ->where('supplier_number', $request->supplier_number)
                            ->where('issue_serie', $request->issue_serie)
                            ->where('issue_number', $request->issue_number)
                            ->where('id', '!=' , $viaticRegisterDetail->id)
                            ->first();

            if ($register) {
                return redirect()->back()->withErrors(['Documento ya se encuentra registrado'])->withInput();
            }

            $viaticRegisterDetail->update($data);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors([$e->getMessage()])->withInput();
        }

        return redirect()->to(session('previous_url_viatic_register_detail'))
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
     * @param  \App\Models\ViaticRegisterDetail  $viaticRegisterDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(ViaticRegister $viaticRegister, ViaticRegisterDetail $viaticRegisterDetail)
    {
        // Verifica si hay registros relacionados en almacén

        DB::beginTransaction(); // Inicia la transacción

        try {

            $viaticRegisterDetail->delete();
            
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
