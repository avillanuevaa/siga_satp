<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = DocumentType::paginate();
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
            'name' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $documentType = DocumentType::create($data);
        return response()->json([
            "message" => "Tipo de documento creado correctamente.",
            "data" => $documentType
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DocumentType  $documentType
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $documentType = DocumentType::find($id);
        if (is_null($documentType)) {
            return response()->json(['error' => 'Tipo de documento no encontrado no existe.'], 400);
        }
        return response()->json([
            "data" => $documentType
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DocumentType  $documentType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DocumentType $documentType)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $documentType->name = $data['name'];
        $documentType->description = $data['description'];
        $documentType->save();
        return response()->json([
            "message" => "Tipo de documento actualizado correctamente.",
            "data" => $documentType
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DocumentType  $documentType
     * @return \Illuminate\Http\Response
     */
    public function destroy(DocumentType $documentType)
    {
        $documentType->delete();
        return response()->json([
            "message" => "Tipo de Documento eliminado correctamente.",
            "data" => $documentType
        ]);
    }
}
