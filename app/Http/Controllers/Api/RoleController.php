<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    
    /**
     * 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();

        return response()->json([
            "success" => true,
            "message" => "Lista de roles",
            "data" => $roles
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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255'
            
        ]);

        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $role = Role::create($input);

        return response()->json([
            "success" => true,
            "message" => "Rol creado con éxito.",
            "data" => $role
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);

        if(is_null($role)){
            return response()->json(['error' => "Rol no encontrado."], 400);
        }

        return response()->json([
            "success" => true,
            "message" => "Rol recuperado con éxito.",
            "data" => $role
        ]);
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'institution_id' => 'required|exists:institution,id',
            
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        } 

        $role->name = $input['name'];
        $role->description = $input['description'];
        $role->institution_id = $input['institution_id'];
        
        $role->save();

        return response()-> json([
            "success" => true,
            "message" => "Actualización de rol con éxito.",
            "data" => $role
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json([
            "success" => true,
            "message" => "Rol eliminado con éxito.",
            "data" => $role
        ]);

    }
}
