<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use App\Models\Person;
use App\Models\PersonOffice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Person::select("*")
                        ->with("office")
                        ->where('active','=', "1")
                        ->where('document_number','LIKE', "%{$request->document_number}%")
                        ->where(DB::raw('CONCAT(name," ",lastname)'),'LIKE', "%{$request->fullname}%")
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
        try {
            $data = $request->all();

            $validator = Validator::make($data, [
                'name' => 'required|string',
                'lastname' => 'required|string',
                'document_type_id' => 'required|numeric',
                'document_number' => 'required|string|max:11',
                'office' => 'required|array|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            
            DB::beginTransaction();
            $person = Person::updateOrCreate(['id' => $request->id], $data);
            PersonOffice::where('person_id', $person->id)->delete();
            $offices_parameter = $request->office;
            $offices = [];
            
            foreach ($offices_parameter as &$value) {
                $personOffice = new PersonOffice();
                $personOffice->person_id = $person->id;
                $personOffice->office_id = $value;
                $personOffice->start_date = date('Y-m-d');
                $personOffice->end_date = null;
                $personOffice->rol_id = 1;
                $personOffice->created_at = date('Y-m-d H:i:s');
                $personOffice->updated_at = date('Y-m-d H:i:s');
                $personOffice->save();
            }

            DB::commit();
            return response()->json($person);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $person = Person::find($id);

        if(is_null($person)){
            return response()->json(['error' => "Persona no encontrada."], 400);
        }

        $user= Person::find($person->id)->user;
        $person->user;
        
        return response()->json([
            "success" => true,
            "message" => "Persona recuperada con éxito.",
            "data" => $person
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Person $person)
    {
        $data = $request->all();        
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'person_type_id' => 'required|numeric',
            'document_type_id' => 'required|numeric',
            'document_number' => 'required|numeric',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'cellphone' => 'nullable|numeric|regex:/[0-9]{9}/|digits:9',
            'office_id' => 'required|exists:offices,id',
            'active' => 'required|numeric',
            'email' => 'required|email',
            'rol_id' => 'required|exists:roles,id',
            'password' => 'required|string|confirmed|min:6',
            'username' => 'required|string|unique:users',
            
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        if($request->hasFile('image')){
            $imageUser = $request->file('image');
            $filename = date('YmdHi').'_'.$imageUser->getClientOriginalName();
            $imageUser-> move(public_path('images/users'), $filename);
            $data['image'] = $filename;
        }else{
            $data['image'] = "default.png";
        }

        $person->name = $data['name'];
        $person->lastname = $data['lastname'];
        $person->person_type_id = $data['person_type_id'];
        $person->document_type_id = $data['document_type_id'];    
        $person->document_number = $data['document_number'];
        $person->address = $data['address'];
        $person->phone = $data['phone'];
        $person->cellphone = $data['cellphone'];
        $person->office_id = $data['office_id'];        
        $person->image = $data['image'];
        $person->save();

        $user= Person::find($person->id)->user;
        $user->email = $data['email'];
        $user->rol_id = $data['rol_id'];
        $user->password = Hash::make($data['password']);        
        $user->username = $data['username'];
        
        $user->save();
       
        return response()->json([
            "success" => true,
            "message" => "Actualización de persona con éxito.",
            "data" => $person
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Person $person)
    {
        $person->update(['active' => 0]);

        return response()->json([
            "message" => "Persona eliminada correctamente.",
        ]);
    }

    public function searchByDni(Request $request)
    {
        $person = Person::where('document_number', $request->dni)->with('office')->first();
        return response()->json($person);
    }
}
