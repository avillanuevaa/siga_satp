<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class YearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('years')->insert([
            'year' => 2022,
            'name' => 'Año del Fortalecimiento de la Soberanía Nacional',
            'description' => 'Año del Fortalecimiento de la Soberanía Nacional',
            'institution_id' => 1
        ]);
    }
}
