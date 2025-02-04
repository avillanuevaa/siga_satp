<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Person;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Client as OClient;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //php artisan make:migration create_flights_table
    //php artisan migrate:refresh --seed
    //php artisan make:model Product -m
    //php artisan make:controller API/TestController --api --model=Test
    public function register(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'person_type_id' => 'required|numeric',
            'document_type_id' => 'required|numeric',
            'document_number' => 'required|numeric|unique:people',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'cellphone' => 'nullable|numeric|regex:/[0-9]{9}/|digits:9',            
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

        $person = Person::create($data);
        
        $data ['password'] = Hash::make($request->password);
        $data['person_id'] = $person->id;

        $user = User::create($data);
        $accessToken = $user->createToken('authToken')->accessToken;

        return response([
            'user' => $user,
            'access_token' => $accessToken
        ]);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|max:255',
            'password' => 'required',
        ]);

        if(!auth()->attempt($data)){
            return response()->json(['message' => 'Usuario o clave incorrectos'], 401);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        $user = auth()->user();
        $user->person->office; //falta revisar relacion $office->$institution   
        

        $url_image = public_path('images/users/') . $user->person->image;
        $user->person['image_base64'] = 'data:image/png;base64,' . base64_encode(file_get_contents($url_image));

        return response([
            'user' => $user,
            'access_token' => $accessToken
        ]);
    }

    public function logout(){
        auth()->user()->token()->revoke();
        return response([
            'message' => 'Sesión cerrada correctamente'
        ]);
    }

    public function getTokenAndRefreshToken($username, $password) { 
        $base_url = url('');
        $oClient = OClient::where('password_client', 1)->first();
        $http = new Client;
        $response = $http->request('POST', $base_url.'/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'username' => $username,
                'password' => $password,
                'scope' => '*',
            ],
        ]);
        $result = json_decode((string) $response->getBody(), true);
        return response()->json($result, 200);
    }
}
