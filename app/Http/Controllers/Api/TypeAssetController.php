<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TypeAsset;
use Illuminate\Http\Request;

class TypeAssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = TypeAsset::all();

        return response()->json($types);
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
}
