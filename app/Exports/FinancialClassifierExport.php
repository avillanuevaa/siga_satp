<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FinancialClassifierExport implements FromCollection, WithHeadings
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
    * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $req = $this->request;

        // Consulta base
        $query = DB::table('financial_classifiers AS T1')
            ->select('T1.*', 'T2.cParNombre AS type_name')
            ->join('parameters AS T2', function($join) {
                $join->on('T1.type_id', '=', 'T2.nParCodigo')
                    ->on('T2.nParClase','=', DB::raw("'1001'"))
                    ->on('T2.nParTipo','=', DB::raw("'1'"));
            });

        // Filtro global
        if ($search = $req->input('search.value')) {
            $query->where(function($q) use ($search) {
                $q->where('T2.cParNombre', 'like', "%{$search}%")
                    ->orWhere('T1.code',       'like', "%{$search}%")
                    ->orWhere('T1.name',       'like', "%{$search}%");
            });
        }

        // Filtros por columna
        foreach ($req->input('columns', []) as $col) {
            $value = $col['search']['value'] ?? null;
            if ($value === null || $value === '') {
                continue;
            }
            switch ($col['data']) {
                case 'tipo':
                    $query->where('T2.cParNombre', 'like', "%{$value}%");
                    break;
                case 'codigo':
                    $query->where('T1.code', 'like', "%{$value}%");
                    break;
                case 'nombre':
                    $query->where('T1.name', 'like', "%{$value}%");
                    break;
                case 'active':
                    // Aquí active llega '1' o '0'
                    $query->where('T1.active', $value);
                    break;
            }
        }

        return $query->get();
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
            'Fecha Registro Actualizado',
            'Tipo',
        ];
    }
}
