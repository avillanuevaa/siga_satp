<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TypeAsset;

class TypeAssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        TypeAsset::truncate();

        $csvFile = fopen(base_path("database/data/type_asset.csv"), "r");

        $firstline = true;
        while(($data = fgetcsv($csvFile, 2000, ";")) !== FALSE){
            if (!$firstline){
                TypeAsset::create([
                    "number" => $data['0'],
                    "idcategory" => $data['1'],
                    "detail" => $data['2'],
                    "classifier" => $data['3'],
                    "description" => $data['4']
                ]);
            }
            $firstline = false;
        }

        fclose($csvFile);
    }
}
