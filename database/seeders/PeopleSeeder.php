<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Person;
use App\Models\Office;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PeopleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Person::truncate();

        $csvFile = fopen(base_path("database/data/people.csv"), 'r');

        $firstline = true;
        while(($data = fgetcsv($csvFile,2000,";")) !== FALSE){
            if(!$firstline) {               

                $person = Person::where('document_number', $data['4'])->first();
                if($person == null){
                    $person = new Person([
                        "name" => $data['0'],	
                        "lastname" => $data['1'],	
                        "person_type_id" => $data['2'],	
                        "document_type_id" => $data['3'],	
                        "document_number" => $data['4'],           	
                        "address" => $data['5'],	
                        "phone" => $data['6'],	
                        "cellphone" => $data['7'],	
                        "image" => $data['8'],	                        	
                        "active" => $data['10'],

                    ]);
                    $person->save();

                    DB::table('users')->insert([
                        'username' => Person::find($person->id)->document_number,
                        'email' => '',
                        'person_id' => $person->id,
                        'rol_id' => 2,
                        'password' => Hash::make('123456'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);


                    DB::table('person_offices')->insert([
                        'person_id' => $person->id,
                        'office_id' => DB::table('offices')->where('code_ue',$data['9'])->value('id'),
                        'start_date' => date('Y-m-d H:i:s'),
                        'end_date' => date('Y-m-d H:i:s'),
                        'rol_id' => 2,
                        'active' => 1,
                        'status' => 1,  
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),  
                    ]);
                }

                else{
                    DB::table('person_offices')->insert([
                        'person_id' => $person->id,
                        'office_id' => DB::table('offices')->where('code_ue',$data['9'])->value('id'),
                        'start_date' => date('Y-m-d H:i:s'),
                        'end_date' => date('Y-m-d H:i:s'),
                        'rol_id' => 2,
                        'active' => 1,
                        'status' => 1,  
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),  
                    ]);

                }

            }
            $firstline = false;
        }

        fclose($csvFile);

    }

}
