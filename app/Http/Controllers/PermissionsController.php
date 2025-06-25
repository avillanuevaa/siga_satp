<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermissionsRequest;
use App\Http\Requests\UpdatePermissionsRequest;
use App\Models\Permissions;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class PermissionsController extends Controller
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
                ->addColumn('rol',       fn($u) => $u->role->name ?? '')
                ->addColumn('estado', function($item) {
                    return match($item->active) {
                        1 => '<span class="badge bg-success">Activo</span>',
                        0 => '<span class="badge bg-danger">Inactivo</span>',
                    };
                })
                ->addColumn('action', function($u) {
                    $seeOptions = '<a
                                    href="#" data-id="'.$u->id.'"
                                    class="btn btn-sm btn-info btn-show"
                                    data-bs-toggle="modal"
                                    data-bs-target="#permissionsModal">
                                        <i class="fas fa-eye"></i>
                                   </a>';
                    return "<div class='btn-group'>{$seeOptions}</div>";
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

        return view('admin.security.permissions.index');
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
     * @param  \App\Http\Requests\StorePermissionsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePermissionsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permissions  $permissions
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $user = User::with(['person', 'role'])->findOrFail($id);
            $permissions = [
                'verDashboard' => $user->verDashboard ?? 0,
                'verMantenimientoClasificadores' => $user->verMantenimientoClasificadores ?? 0,
                'verMantenimientoTrabajadores' => $user->verMantenimientoTrabajadores ?? 0,
                'verMantenimientoOficinas' => $user->verMantenimientoOficinas ?? 0,
                'verContabilidadSiaf' => $user->verContabilidadSiaf ?? 0,
                'verContabilidadExportacion' => $user->verContabilidadExportacion ?? 0,
                'verRendicionesSolicitudes' => $user->verRendicionesSolicitudes ?? 0,
                'verRendicionesLiquidaciones' => $user->verRendicionesLiquidaciones ?? 0,
                'verRendicionesCajaChica' => $user->verRendicionesCajaChica ?? 0,
                'verRendicionesEncargos' => $user->verRendicionesEncargos ?? 0,
                'verRendicionesViaticos' => $user->verRendicionesViaticos ?? 0,
                'verSeguridad' => $user->verSeguridad ?? 0,
            ];

            return response()->json([
                'success' => true,
                'user' => $user,
                'permissions' => $permissions
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener permisos del usuario: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los datos del usuario'
            ], 500);
        }
    }

    public function updatePermissions(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'permissions' => 'required|array',
                'role' => 'nullable|string'
            ]);

            $userId = $request->user_id;
            $permissions = $request->permissions;
            $roleName = $request->role;

            DB::beginTransaction();
            $user = User::findOrFail($userId);
            if ($roleName) {
                $role = Role::where('name', $roleName)->first();
                if ($role) {
                    $user->rol_id = $role->id;
                }
            }

            // Actualizar permisos
            $permissionFields = [
                'verDashboard',
                'verMantenimientoClasificadores',
                'verMantenimientoTrabajadores',
                'verMantenimientoOficinas',
                'verContabilidadSiaf',
                'verContabilidadExportacion',
                'verRendicionesSolicitudes',
                'verRendicionesLiquidaciones',
                'verRendicionesCajaChica',
                'verRendicionesEncargos',
                'verRendicionesViaticos',
                'verSeguridad',
            ];

            foreach ($permissionFields as $field) {
                if (isset($permissions[$field])) {
                    $user->$field = $permissions[$field] ? 1 : 0;
                }
            }

            $user->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Permisos actualizados correctamente',
                'user' => $user->fresh(['person', 'role'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar permisos: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar los permisos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function applyRolePermissions(Request $request, $userId)
    {
        try {
            $request->validate([
                'role' => 'required|string'
            ]);

            $roleName = $request->role;
            $user = User::findOrFail($userId);

            // Definir permisos por rol
            $rolePermissions = [
                'Administrador' => [
                    'verDashboard' => 1,
                    'verMantenimientoClasificadores' => 1,
                    'verMantenimientoTrabajadores' => 1,
                    'verMantenimientoOficinas' => 1,
                    'verContabilidadSiaf' => 1,
                    'verContabilidadExportacion' => 1,
                    'verRendicionesSolicitudes' => 1,
                    'verRendicionesLiquidaciones' => 1,
                    'verRendicionesCajaChica' => 1,
                    'verRendicionesEncargos' => 1,
                    'verRendicionesViaticos' => 1,
                    'verSeguridad' => 1,
                ],
                'Contador' => [
                    'verDashboard' => 1,
                    'verMantenimientoClasificadores' => 0,
                    'verMantenimientoTrabajadores' => 0,
                    'verMantenimientoOficinas' => 0,
                    'verContabilidadSiaf' => 1,
                    'verContabilidadExportacion' => 1,
                    'verRendicionesSolicitudes' => 1,
                    'verRendicionesLiquidaciones' => 1,
                    'verRendicionesCajaChica' => 1,
                    'verRendicionesEncargos' => 1,
                    'verRendicionesViaticos' => 1,
                    'verSeguridad' => 0,
                ],
                'Usuario' => [
                    'verDashboard' => 1,
                    'verMantenimientoClasificadores' => 0,
                    'verMantenimientoTrabajadores' => 0,
                    'verMantenimientoOficinas' => 0,
                    'verContabilidadSiaf' => 0,
                    'verContabilidadExportacion' => 0,
                    'verRendicionesSolicitudes' => 1,
                    'verRendicionesLiquidaciones' => 0,
                    'verRendicionesCajaChica' => 0,
                    'verRendicionesEncargos' => 0,
                    'verRendicionesViaticos' => 0,
                    'verSeguridad' => 0,
                ]
            ];

            if (!isset($rolePermissions[$roleName])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rol no válido'
                ], 400);
            }

            $permissions = $rolePermissions[$roleName];

            // Aplicar permisos
            foreach ($permissions as $permission => $value) {
                $user->$permission = $value;
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => "Permisos de {$roleName} aplicados correctamente",
                'permissions' => $permissions
            ]);

        } catch (\Exception $e) {
            Log::error('Error al aplicar permisos de rol: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al aplicar los permisos del rol'
            ], 500);
        }
    }

    public function getRoles()
    {
        try {
            $roles = Role::select('id', 'name', 'description')->get();

            return response()->json([
                'success' => true,
                'roles' => $roles
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener roles: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los roles'
            ], 500);
        }
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permissions  $permissions
     * @return \Illuminate\Http\Response
     */
    public function edit(Permissions $permissions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePermissionsRequest  $request
     * @param  \App\Models\Permissions  $permissions
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePermissionsRequest $request, Permissions $permissions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permissions  $permissions
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permissions $permissions)
    {
        //
    }
}
