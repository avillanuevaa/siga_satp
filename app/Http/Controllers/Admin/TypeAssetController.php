<?php

namespace App\Http\Controllers\Admin;

use App\Models\TypeAsset;
use Illuminate\Http\Request;

class TypeAssetController extends AdminController
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TypeAsset  $typeAsset
     * @return \Illuminate\Http\Response
     */
    public function show(TypeAsset $typeAsset)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TypeAsset  $typeAsset
     * @return \Illuminate\Http\Response
     */
    public function edit(TypeAsset $typeAsset)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TypeAsset  $typeAsset
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TypeAsset $typeAsset)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TypeAsset  $typeAsset
     * @return \Illuminate\Http\Response
     */
    public function destroy(TypeAsset $typeAsset)
    {
        //
    }

    public function getListByClassifierCode(Request $request){
        
        try {
            // Obtén el parámetro 'classifier_code' de la solicitud
            $classifier_code = $request->classifier_code;
        
            if (!$classifier_code) {
                throw new \InvalidArgumentException('Error: No se ha enviado código de clasificador', 404);
            }
        
            // Obtén el primer registro o arroja una excepción si no se encuentra
            $assets = TypeAsset::getAssetsTypeByClassifier($classifier_code)->firstOrFail();
        
            return response()->json(['assets' => $assets]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
        


    }
}
