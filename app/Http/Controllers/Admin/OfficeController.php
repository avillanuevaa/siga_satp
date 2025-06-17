<?php

namespace App\Http\Controllers\Admin;

use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class OfficeController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Office::query();

            $datatable = DataTables::of($query)
                ->addColumn('office', fn($u) => $u->name ?? '')
                ->addColumn('description', fn($u) => $u->description ?? '')
                ->addColumn('phone', fn($u) => $u->phone ?? '')
                ->addColumn('status', function($u) {
                    return $u->active
                        ? '<span class="badge bg-success">Activo</span>'
                        : '<span class="badge bg-danger">Inactivo</span>';
                })
                ->addColumn('action', function($u) {
                    $editUrl   = route('offices.edit', $u->id);
                    $deleteUrl = route('offices.destroy', $u->id);

                    $edit = '<a href="'.$editUrl.'" class="btn btn-sm btn-info btn-edit me-1">
                                <i class="fas fa-edit"></i>
                             </a>';

                    $del = '<button type="button"
                                data-url="'.$deleteUrl.'"
                                class="btn btn-sm btn-danger btn-delete">
                            <i class="fas fa-trash-alt"></i>
                            </button>';

                    return "<div class='btn-group'>{$edit}{$del}</div>";
                })
                ->rawColumns(['status', 'action'])
                ->toJson();

            return $datatable;
        }

        return view('admin.offices.index');
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code_ue' => 'required|string|max:50|unique:offices,code_ue',
            'description' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'code_office' => 'nullable|string|max:50',
            'annexed' => 'nullable|string|max:50',
            'goal' => 'nullable|string|max:500',
            'active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $office = Office::create([
                'name' => $request->name,
                'code_ue' => $request->code_ue,
                'description' => $request->description,
                'phone' => $request->phone,
                'code_office' => $request->code_office,
                'annexed' => $request->annexed,
                'institution_id' => 1,
                'goal' => $request->goal,
                'active' => $request->has('active') ? 1 : 0,
                'father_id' => null, // Puedes modificar esto según tus necesidades
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Oficina creada exitosamente',
                'office' => $office
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la oficina: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Office  $office
     * @return \Illuminate\Http\Response
     */
    public function show(Office $office)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Office  $office
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Office $office)
    {
        try {
            return response()->json([
                'success' => true,
                'office' => $office
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar la oficina: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Office  $office
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Office $office)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code_ue' => 'required|string|max:50|unique:offices,code_ue,' . $office->id,
            'description' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'code_office' => 'nullable|string|max:50',
            'annexed' => 'nullable|string|max:50',
            'goal' => 'nullable|string|max:500',
            'active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $office->update([
                'name' => $request->name,
                'code_ue' => $request->code_ue,
                'description' => $request->description,
                'phone' => $request->phone,
                'code_office' => $request->code_office,
                'annexed' => $request->annexed,
                'institution_id' => 1,
                'goal' => $request->goal,
                'active' => $request->has('active') ? 1 : 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Oficina actualizada exitosamente',
                'office' => $office
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la oficina: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Office  $office
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Office $office)
    {
        try {
            // Eliminación lógica - cambiar el estado activo a 0
            $office->update(['active' => 0]);

            return response()->json([
                'success' => true,
                'message' => 'Oficina deshabilitada exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al deshabilitar la oficina: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getOfficeAndParent(Request $request){

        $code_ue = $request->code_ue;

        if($code_ue){

            $office = Office::where('code_ue', $code_ue)->first();

            $father = null;

            if ($office) {
                if ($office->father_id) {
                    $father = Office::find($office->father_id);
                }

                return response()->json(['office' => $office, 'father' => $father]);
            } else {
                return response()->json(['error' => 'Oficina no encontrada'], 404);
            }

        }else{
            return response()->json(['error' => 'Error no se ha enviado codigo ue'], 404);
        }



    }
}
