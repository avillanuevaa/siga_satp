<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonOffice extends Model
{
    use HasFactory;

    protected $table = 'person_offices';

    protected $fillable = [
        'person_id',
        'office_id',
        'start_date',
        'end_date',
        'rol_id',
    ];

    public function people()
    {
        return $this->belongsTo(Person::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }
}