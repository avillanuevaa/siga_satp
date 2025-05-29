<?php

namespace App\Http\Controllers\Admin;

use App\Models\Person;
use App\Models\Office;
use App\Models\Parameter;
use Illuminate\Http\Request;
use App\Http\Requests\PersonRequest;
use Illuminate\Support\Facades\DB;


class PersonController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $documentNumber = $request->document_number;
        $fullName = $request->fullname;

        $data = Person::with("office")
                ->where('active', "1")
                ->when($documentNumber, function ($query) use ($documentNumber) {
                    return $query->where('document_number', 'LIKE', "%{$documentNumber}%");
                })
                ->when($fullName, function ($query) use ($fullName) {
                    return $query->whereRaw('CONCAT(name," ",lastname) LIKE ?', ["%{$fullName}%"]);
                })
                ->paginate(20);


        return response()->view('admin.person.index', [
            'persons' => $data,
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
