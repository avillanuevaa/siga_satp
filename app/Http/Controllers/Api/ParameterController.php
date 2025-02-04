<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Parameter;
use Illuminate\Support\Facades\Validator;

class ParameterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function GetParameterByClass(Request $request)
    {

        $data = $request->all();

        $validator = Validator::make($data, [
            'nParClase' => 'required|numeric',
            
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $class = $request->nParClase;
        $parameters = Parameter::where(['nParClase'  => $class,
                                        'nParTipo'  => '1'])->get();
        return response()->json([
            "success" => true,
            "message" => "Lista de parámetros.",
            "data" => $parameters
        ]);       

    }
}
