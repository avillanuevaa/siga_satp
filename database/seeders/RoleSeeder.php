<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'Administrador',
            'description' => 'Administrador',
            'institution_id' => 1
        ]);

        DB::table('roles')->insert([
            'name' => 'Usuario',
            'description' => 'Usuario',
            'institution_id' => 1
        ]);
    }
}
