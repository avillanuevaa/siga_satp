<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Person;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $person = new Person([
            'name' => 'Administrador',
            'lastname' => 'Del Sistema',
            'person_type_id' => 1,
            'document_type_id' => 1,
            'document_number' => '00000000',
            'image' => 'default.png',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $person->save();

        DB::table('users')->insert([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'person_id' => $person->id,
            'rol_id' => 1,
            'password' => Hash::make('123456'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('person_offices')->insert([
            'person_id' => $person->id,
            'office_id' => 12,
            'start_date' => date('Y-m-d H:i:s'),
            'end_date' => date('Y-m-d H:i:s'),
            'rol_id' => 1,
            'active' => 1,
            'status' => 1,  
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),  
        ]);
    }
}
