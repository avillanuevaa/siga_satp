<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    use HasFactory;

    protected $fillable =[
        'nParCodigo',
        'nParClase',
        'cParJerarquia',
        'cParNombre',
        'cParDescripcion',
        'cParValor',
        'institution_id',
        'nParTipo',
    ];


    public function scopeGetParametersByClassAndType($query, $nParClase, $nParTipo){
        return $query->selectRaw('cParValor as value, cParNombre AS name')
                     ->where('nParClase', $nParClase)
                     ->where('nParTipo', $nParTipo)
                     ->orderBy('cParJerarquia', 'ASC')
                     ->get()
                     ->pluck('name', 'value')
                     ->toArray();
    }

    public function scopeIdentityCardType($query){
        // 1004 ..:: Tipo de documento de identidad ::..
        return $query->getParametersByClassAndType('1004', '1');
    }

    public function scopePaymentReceiptsType($query){
        // 1006 ..:: Tipo de comprobante de pago o documento ::..
        return $query->getParametersByClassAndType('1006', '1');
    }
    
    public function scopeClassifiersType($query){
        // 1001 ..:: Tipo de clasificadores de ingresos y gastos ::..
        return $query->getParametersByClassAndType('1001', '1');
    }
    
    public function scopeContractsType($query){
        // 1003 ..:: Tipo de contratacion ::..
        return $query->getParametersByClassAndType('1003', '1');
    }

    public function scopeRequestsType($query){
        // 1010 ..::  Tipo de liquidaciones  ::..
        return $query->getParametersByClassAndType('1010', '1');
    }
    
    public function scopeViaticsType($query){
        // 1011 ..::  Tipo de viaticos  ::..
        return $query->getParametersByClassAndType('1011', '1');
    }

    public function scopeTransportationsMean($query){
        // 1012 ..::  Medios de Transporte  ::..
        return $query->getParametersByClassAndType('1012', '1');
    }

    public function scopeGoalsType($query){
        // 1002 ..:: Meta presupuestaria ::..
        return $query->getParametersByClassAndType('1002', '1');
    }

    public function scopeMeasuresType($query){
        // 1005 ..:: Codigo de la unidad de medida ::..
        return $query->getParametersByClassAndType('1005', '1');
    }

    
}
