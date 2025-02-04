<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\FinancialClassifier;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class FinancialClassifierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = DB::table('financial_classifiers AS T1')
                    ->select('T1.*', 'T2.cParNombre AS type_name')
                    ->join('parameters AS T2', function($join)
                         {
                             $join->on('T1.type_id', '=', 'T2.nParCodigo');
                             $join->on('T2.nParClase','=',DB::raw("'1001'"));
                             $join->on('T2.nParTipo','=',DB::raw("'1'"));
                         })
                    // ->where('T1.active', 1)
                    ->where('name','LIKE', "%{$request->name}%")
		            ->where('code','LIKE', "%{$request->code}%")
                    ->paginate($request->per_page, ['*'], 'page', $request->pageNumber);
                    
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
            'type_id' => 'required|numeric',
            'code' => 'required',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // $financialClassifier = FinancialClassifier::create($data);
        $financialClassifier = FinancialClassifier::updateOrCreate(['id' => $request->id], $data);
        return response()->json([
            "message" => "Clasificador registrado correctamente.",
            "data" => $financialClassifier
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FinancialClassifier  $financialClassifier
     * @return \Illuminate\Http\Response
     */
    public function show(FinancialClassifier $financialClassifier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FinancialClassifier  $financialClassifier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FinancialClassifier $financialClassifier)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FinancialClassifier  $financialClassifier
     * @return \Illuminate\Http\Response
     */
    public function destroy(FinancialClassifier $financialClassifier)
    {
       $financialClassifier->update(['active' => $financialClassifier->active == 1 ? 0 : 1]);

        return response()->json([
            "message" => "Clasificador actualizado correctamente.",
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'term' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $search = $request->term;

        $data = FinancialClassifier::getFindbyTerm($search);

        return response()->json($data);
    }
}
