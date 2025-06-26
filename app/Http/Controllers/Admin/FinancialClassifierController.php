<?php

namespace App\Http\Controllers\Admin;

use App\Exports\FinancialClassifierExport;
use Illuminate\Support\Facades\DB;
use App\Models\FinancialClassifier;
use App\Models\Parameter;
use Illuminate\Http\Request;
use App\Http\Requests\FinancialClassifierRequest;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;


class FinancialClassifierController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('financial_classifiers AS T1')
                ->select('T1.*', 'T2.cParNombre AS type_name')
                ->join('parameters AS T2', function($join) {
                    $join->on('T1.type_id', '=', 'T2.nParCodigo')
                        ->on('T2.nParClase','=', DB::raw("'1001'"))
                        ->on('T2.nParTipo','=', DB::raw("'1'"));
                });

            return DataTables::of($query)
                ->addColumn('tipo',   fn($u) => $u->type_name ?? '')
                ->addColumn('codigo', fn($u) => $u->code)
                ->addColumn('nombre', fn($u) => $u->name ?? '')
                ->addColumn('estado', function ($u) {
                    $badge = $u->active ? 'success' : 'danger';
                    $text  = $u->active ? 'Activo'  : 'Inactivo';
                    return "<div class='text-center'><span class='badge bg-{$badge}'>{$text}</span></div>";
                })
                ->addColumn('action', function($u) {
                    $editUrl   = route('financialClassifiers.edit',    $u->id);
                    $deleteUrl = route('financialClassifiers.destroy', $u->id);

                    $edit = '<a href="'.$editUrl.'" class="btn btn-sm btn-success btn-edit">
                                <i class="fas fa-edit"></i>
                             </a>';

                    $del = '<button type="button"
                                data-url="'.$deleteUrl.'"
                                class="btn btn-sm btn-danger btn-delete">
                            <i class="fas fa-trash-alt"></i>
                            </button>';

                    return "<div class='btn-group'>{$edit}{$del}</div>";
                })

                ->addColumn('active', fn($u) => $u->active)
                ->rawColumns(['estado','action'])
                ->filterColumn('T2.cParNombre', function($query, $keyword) {
                    $query->where('T2.cParNombre', 'like', "%{$keyword}%");
                })
                ->filterColumn('T1.code', function($query, $keyword) {
                    $query->where('T1.code', 'like', "%{$keyword}%");
                })
                ->filterColumn('T1.name', function($query, $keyword) {
                    $query->where('T1.name', 'like', "%{$keyword}%");
                })
                ->filterColumn('active', function($query, $keyword) {
                    if ($keyword !== '') {
                        $query->where('T1.active', $keyword);
                    }
                })

                ->filter(function ($query) use ($request) {
                    $search = $request->input('search.value');
                    if (!empty($search)) {
                        $query->where(function($q) use ($search) {
                            $q->where('T2.cParNombre', 'like', "%{$search}%")
                                ->orWhere('T1.code',       'like', "%{$search}%")
                                ->orWhere('T1.name',       'like', "%{$search}%");
                        });
                    }
                })
                ->toJson();
        }

        return view('admin.financial_classifier.index');
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

    public function exportPrint()
    {
        $classifiers = DB::table('financial_classifiers AS T1')
            ->select('T1.*', 'T2.cParNombre AS type_name')
            ->join('parameters AS T2', function($join) {
                $join->on('T1.type_id', '=', 'T2.nParCodigo')
                    ->on('T2.nParClase','=', DB::raw("'1001'"))
                    ->on('T2.nParTipo','=', DB::raw("'1'"));
            })
            ->get();

        return view('admin.financial_classifier.print_classifiers', compact('classifiers'));
    }

    public function exportExcel()
    {
        return Excel::download(new FinancialClassifierExport, 'clasificadores.xlsx');
    }
}
