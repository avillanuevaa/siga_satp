<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class DocumentSiaf extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $perPage = 20;
    protected $table = 'documents_siaf';

    protected $fillable = [
        'month',
        'siaf',
        'ruc',
        'type',
        'type_new',
        'serie',
        'number',
        'date',
        'amount',
        'file_upload_id',
        'business_name',
        'taxable_basis',
        'igv',
        'untaxed_basis',
        'impbp',
        'other_concepts',
        'doc_code',
        'num_doc',
        'ha_1',
        'ha_2',
        'ha_3',
        'detraction_date',
        'num_operation',
        'payment_date',
        'doc_modify_date_of_issue',
        'doc_modify_type',
        'doc_modify_serie',
        'doc_modify_number',
        'last_name',
        'mother_last_name',
        'name',
        'total_honorary',
        'have_retention',
        'retention',
        'net_honorary',
        'source',
        'status',
        'active',
    ];

    // $Base_Imponible_Adq_Gravadas_No_BV = ($value->type_new != '03' ? '0' : $value->untaxed_basis);

    public static function getExcelPlebyMonth($month, $year)
    {

        return DB::table('documents_siaf')->select('id', 'siaf', 'parameters.cParNombre as NombreTipo', 'date', 'type_new', 'serie', 'number', 'ruc', 'business_name', 'untaxed_basis', 'taxable_basis', 'impbp', 'igv', 'other_concepts', 'amount', 'payment_date', 'doc_code', 'num_doc', 'doc_modify_date_of_issue', 'doc_modify_type', 'doc_modify_serie', 'doc_modify_number')
            ->join('parameters',  function ($join) {
                $join->on('documents_siaf.type_new', '=', 'parameters.cParValor');
                $join->on('parameters.nParClase', '=', DB::raw('1006'));
                $join->on('parameters.nParTipo', '=', DB::raw('1'));
            })
            ->whereMonth('payment_date', $month)
            ->whereYear('payment_date', '=', $year)
            ->where('documents_siaf.active', 1)
            ->whereNot('type_new', 'R')->whereNot('type_new', 'N');
    }

    public static function getExcelPlamebyMonth($month, $year)
    {

        return DB::table('documents_siaf')->select('id', 'siaf', 'date', 'type_new', 'serie', 'number', 'ruc', 'last_name', 'mother_last_name', 'name', 'total_honorary', 'retention', 'net_honorary', 'payment_date', 'doc_code', 'num_doc', 'doc_modify_date_of_issue', 'doc_modify_type', 'doc_modify_serie', 'doc_modify_number', 'igv', 'taxable_basis')->whereMonth('payment_date', $month)->whereYear('payment_date', '=', $year)->where('active', 1)->where(function ($query) {
            $query->where('type_new', 'R')->orWhere('type_new', 'N');
        });
    }

    public static function getExcelPending($year)
    {

        return DB::table('documents_siaf')->select('id', 'siaf', 'parameters.cParNombre as NombreTipo', 'date', 'type_new', 'serie', 'number', 'ruc', 'business_name', 'untaxed_basis', 'taxable_basis', 'impbp', 'igv', 'other_concepts', 'amount', 'payment_date', 'doc_code', 'num_doc', 'doc_modify_date_of_issue', 'doc_modify_type', 'doc_modify_serie', 'doc_modify_number')
            ->join('parameters',  function ($join) {
                $join->on('documents_siaf.type_new', '=', 'parameters.cParValor');
                $join->on('parameters.nParClase', '=', DB::raw('1006'));
                $join->on('parameters.nParTipo', '=', DB::raw('1'));
            })
            ->whereYear('date', '=', $year)
            ->where('documents_siaf.active', 1)->where('documents_siaf.status', 1);
    }
}
