<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    use HasFactory;

    protected $table = 'settlements';

    protected $fillable = [
        'number_correlative',
        'request_id',
        'request_type',
        'year',
        'approved_amount',
        'budget_certificate',
        'reason',
        'person_id',
        'authorization_date',
        'authorization_detail',
        'viatic_type',
        'destination',
        'means_of_transport',
        'number_days',
        'departure_date',
        'return_date',
        'authorization_date',
        'format_number_two',
        'approval',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function requestFile()
    {
        return $this->hasOne(RequestFile::class, 'id', 'request_id');
    }

    public function settlementClassifier(){
        return $this->hasMany(SettlementClassifier::class, 'settlement_id');
    }

    public function requestType(){
        return $this->hasOne(Parameter::class, 'nParCodigo', 'request_type')
                        ->where('nParClase', '1010') // Filtro para la clase '1010'
                        ->where('nParTipo', '1');    // Filtro para el tipo '1'
    }

    public function viaticType(){
        return $this->hasOne(Parameter::class, 'nParCodigo', 'viatic_type')
                        ->where('nParClase', '1011') // Filtro para la clase '1011'
                        ->where('nParTipo', '1');    // Filtro para el tipo '1'
    }

    public function transportationsMeansType(){
        return $this->hasOne(Parameter::class, 'nParCodigo', 'means_of_transport')
                        ->where('nParClase', '1012') // Filtro para la clase '1012'
                        ->where('nParTipo', '1');    // Filtro para el tipo '1'
    }

}
