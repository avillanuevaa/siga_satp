<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('institutions')->insert([
            'ruc' => '20441554436',
            'name' => 'Servicio de Administración Tributaria de Piura',
            'description' => 'Servicio de Administración Tributaria de Piura',
            'logo' => 'logo_institution.png',
            'address' => 'Jr. Arequipa 1052, Piura 20001',
            'phone' => '(073) 285400',
            'cellphone' => '',
        ]);
    }
}
