<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BankAccount;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
            $financial_entity_accounts = [
                ['bank' => 'BANCO BBVA PERU', 'account_type' => 'GENERAL', 'number' =>'0011-0278-0100003123'],
                ['bank' => 'BANCO DE CREDITO DEL PERU', 'account_type' => 'ADMINISTRATIVA', 'number' =>'475-1973158-0-66'],
                ['bank' => 'BANCO DE CREDITO DEL PERU', 'account_type' => 'GENERAL', 'number' =>'475-1407049-0-94'],
                ['bank' => 'BANCO DE CREDITO DEL PERU', 'account_type' => 'TRIMESTRAL', 'number' =>'475-1431544-0-18'],
                ['bank' => 'BANCO DE CREDITO DEL PERU', 'account_type' => 'VEHICULAR', 'number' =>'475-1798597-0-23'],
                ['bank' => 'BANCO DE LA NACIÓN', 'account_type' => 'CENTRALIZADORA', 'number' =>'00-631-310834'],
                ['bank' => 'BANCO DE LA NACIÓN', 'account_type' => 'CUT', 'number' =>'00-631-310788'],
                ['bank' => 'BANCO DE LA NACIÓN', 'account_type' => 'DETRACCIONES', 'number' =>'00-631-211836'],
                ['bank' => 'BANCO DE LA NACIÓN', 'account_type' => 'GARANTÍAS', 'number' =>'00-631-331297'],
                ['bank' => 'BANCO DE LA NACIÓN', 'account_type' => 'PROSEGUR', 'number' =>'00-631-230849'],
                ['bank' => 'BANCO DE LA NACIÓN', 'account_type' => 'RECAUDADORA', 'number' =>'00-631-117414'],
                ['bank' => 'BANCO INTERNACIONAL DEL PERU-INTERBANK', 'account_type' => 'EXADMINISTRATIVA', 'number' =>'720-3000570819'],
                ['bank' => 'BANCO INTERNACIONAL DEL PERU-INTERBANK', 'account_type' => 'RECAUDADORA', 'number' =>'720-3030355669'],
                ['bank' => 'CMAC PIURA S.A.C.', 'account_type' => 'GENERAL', 'number' =>'110-01-2473043'],
            ];

            BankAccount::insert($financial_entity_accounts);
       
    }
}
