<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Institution;
use Illuminate\Support\Facades\Validator;

class InstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $institutions = Institution::all();

        return response()->json([
            "success" => true,
            "message" => "Lita de instituciones",
            "data" => $institutions

        ]);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $institution = Institution::find($id);

        if (is_null($institution)) {
            return response()->json(['error' => "Institucion no encontrdada"], 400);
        }

        return response()->json([
            "success" => true,
            "message" => "Institucion recuperada con éxito.",
            "data" => $institution
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Institution $institution)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'logo' => 'string|nullable|string|max:100',
            'address' => 'string|nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'cellphone' => 'nullable|string|max:20'

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $institution->name = $input['name'];
        $institution->description = $input['description'];
        $institution->logo = $input['logo'];
        $institution->address = $input['address'];
        $institution->phone = $input['phone'];
        $institution->cellphone = $input['cellphone'];
        $institution->save();

        return response()->json([
            "success" => true,
            "message" => "Actualización de institucion con éxito.",
            "data" => $institution
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
