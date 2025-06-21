<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::with(['person','role'])->select('users.*');

            return DataTables::of($query)
                ->addColumn('dni',       fn($u) => $u->person->document_number ?? '')
                ->addColumn('user',      fn($u) => $u->username)
                ->addColumn('nombres',   fn($u) => $u->person->name ?? '')
                ->addColumn('apellidos', fn($u) => $u->person->lastname ?? '')
                ->addColumn('telefono',  fn($u) => $u->person->phone ?? '')
                ->addColumn('rol',       fn($u) => $u->role->description ?? $u->role->name ?? '')
                ->addColumn('estado', function($item) {
                    return match($item->active) {
                        1 => '<span class="badge bg-success">Activo</span>',
                        0 => '<span class="badge bg-danger">Inactivo</span>',
                    };
                })
                ->editColumn('created_at', fn($u) => $u->created_at->format('d/m/Y H:i'))
                ->addColumn('action', function($u) {
                    $edit = '<a href="#" data-id="'.$u->id.'" class="btn btn-sm btn-success btn-edit"><i class="fas fa-edit"></i></a>';
                    $del  = '<button data-id="'.$u->id.'" class="btn btn-sm btn-danger btn-delete"><i class="fas fa-trash-alt"></i></button>';
                    return "<div class='btn-group'>{$edit}{$del}</div>";
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && $search = $request->get('search')['value']) {
                        $query->where(function ($q) use ($search) {
                            $q->where('username', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhereHas('person', function ($sub) use ($search) {
                                    $sub->where('document_number', 'like', "%{$search}%")
                                        ->orWhere('name', 'like', "%{$search}%")
                                        ->orWhere('lastname', 'like', "%{$search}%")
                                        ->orWhere('phone', 'like', "%{$search}%");
                                })
                                ->orWhereHas('role', function ($sub) use ($search) {
                                    $sub->where('description', 'like', "%{$search}%")
                                        ->orWhere('name', 'like', "%{$search}%");
                                });
                        });
                    }
                })
                ->rawColumns(['action', 'estado'])
                ->toJson();
        }

        return view('admin.security.users.index');
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
            'trabajador_id' => 'required|exists:people,id',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'active' => 'boolean'
        ], [
            'trabajador_id.required' => 'Debe seleccionar un trabajador.',
            'trabajador_id.exists' => 'El trabajador seleccionado no existe.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe tener un formato válido.',
            'email.unique' => 'Este email ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $person = Person::findOrFail($request->trabajador_id);

        if (User::where('person_id', $person->id)->exists()) {
            return response()->json([
                'errors' => ['trabajador_id' => ['Este trabajador ya tiene un usuario asignado.']]
            ], 422);
        }

        $user = User::create([
            'username' => $person->document_number,
            'email' => $request->email,
            'person_id' => $person->id,
            'rol_id' => 2,
            'password' => Hash::make($request->password),
            'active' => (bool) $request->active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Usuario creado exitosamente.',
            'data' => $user
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::with(['person', 'role'])->findOrFail($id);
        return response()->json($user);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id)
            ],
            'password' => 'nullable|min:6|confirmed',
            'active' => 'required|boolean'
        ], [
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe tener un formato válido.',
            'email.unique' => 'Este email ya está registrado.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'active.required' => 'El estado es obligatorio.',
            'active.boolean' => 'El estado debe ser válido.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $updateData = [
            'email' => $request->email,
            'active' => (bool) $request->active
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Usuario actualizado exitosamente.',
            'data' => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $user->update(['active' => 0]);

        return response()->json([
            'success' => true,
            'message' => 'Usuario desactivado correctamente.',
            'data' => $user
        ]);
    }

    public function exportPrint()
    {
        $users = User::with(['person','role'])->get();
        return view('admin.security.users.print_users', compact('users'));
    }

    public function exportCopy()
    {
        $users = User::with(['person', 'role'])->get();

        $lines = [];
        foreach ($users as $u) {
            $lines[] = implode("\t", [
                $u->person->document_number ?? '',
                $u->username,
                $u->person->name ?? '',
                $u->person->lastname ?? '',
                $u->person->phone ?? '',
                $u->role->description ?? $u->role->name ?? '',
                $u->created_at->format('d/m/Y H:i'),
            ]);
        }

        return response(implode("\n", $lines), 200)
            ->header('Content-Type', 'text/plain');
    }

    public function exportExcel()
    {
        return Excel::download(new UsersExport, 'usuarios.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new UsersExport, 'usuarios.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPdf()
    {
        $users = User::with(['person','role'])->get();
        $pdf   = PDF::loadView('admin.security.users.users-pdf', compact('users'));
        return $pdf->stream('usuarios.pdf');
    }
}
