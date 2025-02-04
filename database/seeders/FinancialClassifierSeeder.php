<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\FinancialClassifier;

class FinancialClassifierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // IncomeExpenseClassifier::delete();
        DB::table('financial_classifiers')->delete();
  
        $csvFile = fopen(base_path("database/data/income_expense_classifier.csv"), "r");
  
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
            if (!$firstline) {
                FinancialClassifier::create([
                    "type_id" => $data['0'],
                    "code" => $data['1'],
                    "name" => $data['2'],
                    "active" => $data['3']
                ]);
            }
            $firstline = false;
        }
   
        fclose($csvFile);
    }
}
