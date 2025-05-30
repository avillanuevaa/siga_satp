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
                ->editColumn('created_at', fn($u) => $u->created_at->format('d/m/Y H:i'))
                ->addColumn('action', function($u) {
                    $edit = '<a href="#" data-id="'.$u->id.'" class="btn btn-sm btn-success btn-edit"><i class="fas fa-edit"></i></a>';
                    $del  = '<button data-id="'.$u->id.'" class="btn btn-sm btn-danger btn-delete"><i class="fas fa-trash-alt"></i></button>';
                    return "<div class='btn-group'>{$edit}{$del}</div>";
                })
                ->rawColumns(['action'])
                ->make(true);
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
        $person = Person::find($request->trabajador_id);

        if (!$person) {
            return response()->json([
                'errors' => ['trabajador_id' => ['El trabajador no existe.']],
            ], 422);
        }

        // Crear nuevo usuario
        $user = new User();
        $user->username   = $person->document_number;
        $user->email      = $request->email;
        $user->person_id  = $person->id;
        $user->rol_id     = 2;
        $user->password   = Hash::make($request->password);
        $user->active     = 1;
        $user->save();

        return response()->json([
            'message' => 'Usuario creado exitosamente.',
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
        $user = User::with('person')->findOrFail($id);
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
        $input = $request->all();

        $validator = Validator::make($input, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user->email = $input['email'];
        $user->password = Hash::make($input['password']);

        $user->save();

        return response()->json([
            "success" => true,
            "message" => "Actualización de usuario con éxito.",
            "data" => $user
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
        $user->delete();

        return response()->json([
            "succes" => true,
            "message" => "Usuario eliminado.",
            "data" => $user
        ]);
    }

    public function exportPrint()
    {
        $users = User::with(['person','role'])->get();
        return view('admin.security.users.print_users', compact('users'));
    }

    public function exportCopy()
    {
        $users = User::with(['person','role'])->get();

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
