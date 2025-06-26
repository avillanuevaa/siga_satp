<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FinancialClassifierExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $classifiers = DB::table('financial_classifiers AS T1')
            ->select('T1.*', 'T2.cParNombre AS type_name')
            ->join('parameters AS T2', function($join) {
                $join->on('T1.type_id', '=', 'T2.nParCodigo')
                    ->on('T2.nParClase','=', DB::raw("'1001'"))
                    ->on('T2.nParTipo','=', DB::raw("'1'"));
            })
            ->get();

        return $classifiers;
    }

    public function headings(): array
    {
        return [
            'ID',
            'ID Tipo',
            'Código',
            'Nombre',
            'Estado',
            'Fecha Registro Creado',
            'Fecha registro actualizado',
            'Tipo',
        ];
    }
}
