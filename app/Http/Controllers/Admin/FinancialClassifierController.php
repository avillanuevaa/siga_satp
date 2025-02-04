<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Models\FinancialClassifier;
use App\Models\Parameter;
use Illuminate\Http\Request;
use App\Http\Requests\FinancialClassifierRequest;
use Illuminate\Support\Facades\Validator;



class FinancialClassifierController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $name = $request->name;
        $code = $request->code;
        $data = DB::table('financial_classifiers AS T1')
                    ->select('T1.*', 'T2.cParNombre AS type_name')
                    ->join('parameters AS T2', function($join)
                        {
                            $join->on('T1.type_id', '=', 'T2.nParCodigo');
                            $join->on('T2.nParClase','=',DB::raw("'1001'"));
                            $join->on('T2.nParTipo','=',DB::raw("'1'"));
                        })
                    // ->where('T1.active', 1)
                    ->when($name, function($query, $name) {
                        return $query->where('name','LIKE', "%{$name}%");
                    })
                    ->when($code, function($query, $code) {
                        return $query->where('code','LIKE', "%{$code}%");
                    })
                    ->paginate(20);

        return response()->view('admin.financial_classifier.index', [
            'financialClassifiers' => $data,
            'request' => $request
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->view('admin.financial_classifier.create', [
            'classifierTypes' => Parameter::classifiersType()
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FinancialClassifierRequest $request)
    {
        //
        $data = $request->validated();
        $data['active'] = isset($data['active']);
        
        FinancialClassifier::create($data);

        return redirect()->route('financialClassifiers.index')
                         ->with([
                            'notif' => [
                                'message' => 'Clasificador creado satisfactoriamente.',
                                'icon' => 'success'
                            ],
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FinancialClassifier  $financialClassifier
     * @return \Illuminate\Http\Response
     */
    public function edit(FinancialClassifier $financialClassifier)
    {
        //
        return response()->view('admin.financial_classifier.edit', [
            'classifierTypes' => Parameter::classifiersType(),
            'financialClassifier' => $financialClassifier,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\FinancialClassifierRequest;  $request
     * @param  \App\Models\FinancialClassifier  $financialClassifier
     * @return \Illuminate\Http\Response
     */
    public function update(FinancialClassifierRequest $request, FinancialClassifier $financialClassifier)
    {
        //
        $data = $request->validated();
        $data['active'] = isset($data['active']);
        
        $financialClassifier->update($data);

        return redirect()->route('financialClassifiers.index')
                         ->with([
                            'notif' => [
                                'message' => 'Classificador actualizado satisfactoriamente.',
                                'icon' => 'success'
                            ],
                        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FinancialClassifier  $financialClassifier
     * @return \Illuminate\Http\Response
     */
    public function destroy(FinancialClassifier $financialClassifier)
    {
        //
        $delete = $financialClassifier->delete();

        if ($delete) {
            return response()->json(['success' => true]);
        }
        
    }

    public function search(Request $request)
    {
        $data = $request->validate([
            'term' => 'required|string|max:255',
        ]);

        $search = $request->input('term');

        $data = FinancialClassifier::getFindbyTerm($search);

        return response()->json($data);
    }
}
