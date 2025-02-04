<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RequestFile extends Model
{
    use HasFactory;

    protected $table = 'requests';

    protected $fillable = [
        'request_type',
        'request_settlement_type',
        'number_correlative',
        'request_date',
        'year',
        'request_amount',
        'person_id',
        'reference_document',
        'purpose',
        'justification',
        'viatic_type',
        'destination',
        'means_of_transport',
        'number_days',
        'departure_date',
        'return_date',
        'authorization_date',
        'authorization_detail',
        'budget_certificate',
        'format_number_two'        
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function requestFileClassifier(){
        return $this->hasMany(RequestFileClassifier::class, 'request_id');
    }

    public function requestType(){
        return $this->hasOne(Parameter::class, 'nParCodigo', 'request_type')
                        ->where('nParClase', '1010') // Filtro para la clase '1010'
                        ->where('nParTipo', '1');    // Filtro para el tipo '1'
    }

}
