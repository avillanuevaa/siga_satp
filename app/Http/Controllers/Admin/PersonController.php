<?php

namespace App\Http\Controllers\Admin;

use App\Models\Person;
use App\Models\Office;
use App\Models\Parameter;
use Illuminate\Http\Request;
use App\Http\Requests\PersonRequest;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;


class PersonController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Person::query()
                ->join('person_offices', 'person_offices.person_id', '=', 'people.id')
                ->join('offices',        'offices.id',         '=', 'person_offices.office_id')
                ->where('person_offices.active', 1)
                ->where('people.active',         1)
                ->select([
                    'people.id',
                    'people.name as person_name',
                    'people.lastname',
                    'people.document_number',
                    'offices.name as office_name',
                    'people.active',
                ]);

            return DataTables::of($query)
                ->addColumn('dni',   fn($u) => $u->document_number ?? '')
                ->addColumn('nombre', fn($u) => $u->person_name ?? '')
                ->addColumn('apellido', fn($u) => $u->lastname ?? '')
                ->addColumn('oficina',  fn($u) => $u->office_name)
                ->addColumn('estado', function ($u) {
                    $badge = $u->active ? 'success' : 'danger';
                    $text  = $u->active ? 'Activo'  : 'Inactivo';
                    return "<div class='text-center'><span class='badge bg-{$badge}'>{$text}</span></div>";
                })
                ->addColumn('action', function($u) {
                    $editUrl   = route('persons.edit',    $u->id);
                    $deleteUrl = route('persons.destroy', $u->id);

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
                ->filterColumn('person_name', function($query, $keyword) {
                    $query->where('people.name', 'like', "%{$keyword}%");
                })
                ->filterColumn('office_name', function($query, $keyword) {
                    $query->where('offices.name', 'like', "%{$keyword}%");
                })
                ->filter(function ($query) use ($request) {
                    if ($search = $request->input('search.value')) {
                        $query->where(function($q) use ($search) {
                            $q->where('people.name',                'like', "%{$search}%")
                                ->orWhere('people.lastname',        'like', "%{$search}%")
                                ->orWhere('people.document_number', 'like', "%{$search}%")
                                ->orWhere('offices.name',           'like', "%{$search}%");
                        });
                    }
                })
                ->make(true);
        }
        return view('admin.person.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return response()->view('admin.person.create', [
            'documentSupplierTypes' => Parameter::identityCardType(),
            'offices' => Office::select('*', DB::Raw("CONCAT(code_ue, ' - ', name) AS full_name_office"))->get()->pluck('full_name_office', 'id')->toArray(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PersonRequest $request)
    {
        //
        $data = $request->validated();

        $contractsType = Parameter::contractsType();
        $decretoLegislativo728ID =  key($contractsType); // get type decreto legislativo number 728 for default
        $data['person_type_id'] = $decretoLegislativo728ID;

        $person = Person::create($data);

        $officeIds = $request->input('office', []);

        $this->syncOffices($person, $officeIds);

        return redirect()->route('persons.index')
                         ->with([
                            'notif' => [
                                'message' => 'Trabajador creado satisfactoriamente.',
                                'icon' => 'success'
                            ],
                        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $person = Person::findOrFail($id);

        return response()->json([
            'id' => $person->id,
            'name' => $person->name,
            'lastname' => $person->lastname,
            'document_number' => $person->document_number,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function edit(Person $person)
    {
        //
        return response()->view('admin.person.edit', [
            'documentSupplierTypes' => Parameter::identityCardType(),
            'offices' => Office::select('*', DB::Raw("CONCAT(code_ue, ' - ', name) AS full_name_office"))->get()->pluck('full_name_office', 'id')->toArray(),
            'person' => $person
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function update(PersonRequest $request, Person $person)
    {
        //
        $data = $request->validated();

        $officeIds = $request->input('office', []); // Suponiendo que los roles están en un campo llamado 'roles[]' en el formulario

        $this->syncOffices($person, $officeIds);

        $person->update($data);

        return redirect()->route('persons.index')
                         ->with([
                            'notif' => [
                                'message' => 'Trabajador actualizado satisfactoriamente.',
                                'icon' => 'success'
                            ],
                        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function destroy(Person $person)
    {
        //
        $person = Person::findOrFail($person->id);
        try {
            // Iniciar una transacción manual
            DB::beginTransaction();

            // Verificar si la persona tiene relaciones en la tabla intermedia
            if ($person->office->count() > 0) {
                // Si tiene relaciones, eliminarlas usando detach()
                $person->office()->detach();
            }

            // Eliminar la persona
            $person->delete();

            // Confirmar los cambios en la base de datos
            DB::commit();

            // Retornar true para indicar que todo salió correctamente
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollback();

            // Retornar false para indicar que hubo un error
            return response()->json(['success' => false]);
        }
    }

    public function search(Request $request)
    {
        $search = $request->input('term');

        $results = Person::query()
            ->where('document_number', 'like', "%{$search}%")
            ->orWhere('name', 'like', "%{$search}%")
            ->orWhere('lastname', 'like', "%{$search}%")
            ->get()
            ->map(function ($person) {
                return [
                    'id' => $person->id,
                    'text' => $person->document_number . ' - ' . $person->name . ' ' . $person->lastname
                ];
            });

        return response()->json($results);
    }

    public function searchById(Request $request)
    {
        $person = Person::with(['office' => function ($query) {
            $query->select('offices.id', 'name', 'code_ue');
        }])->find($request->id);

        if (!$person) {
            return response()->json(['message' => 'Trabajador no encontrado'], 404);
        }

        $office = $person->office->first();

        return response()->json([
            'id' => $person->id,
            'name' => $person->name,
            'lastname' => $person->lastname,
            'document_number' => $person->document_number,
            'office' => $office ? [
                'id' => $office->id,
                'name' => $office->name,
                'code_ue' => $office->code_ue,
            ] : null,
        ]);
    }

    public function searchByDni(Request $request)
    {
        $person = Person::where('document_number', $request->dni)->with('office')->first();
        return response()->json($person);
    }

    /**
     * Sync offices for the given person.
     *
     * @param  \App\Models\Person  $person
     * @param  array  $officeIds
     * @return void
     */
    private function syncOffices(Person $person, array $officeIds)
    {
        try {
            DB::beginTransaction();

            $person->office()->detach();

            foreach ($officeIds as $officeId) {
                $person->office()->attach($officeId, [
                    'start_date' => now(),
                    'rol_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error.message', 'error|Hubo un error al guardar los cambios.');
        }
    }
}
