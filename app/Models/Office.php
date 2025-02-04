<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'logo',
        'phone',
        'annexed',
        'address',
        'code_ue',
        'goal',
        'code_office',
        'father_id',
        'institution_id',
        'active'
    ];

    public static function getListOffices(){

        return static::selectRaw('*, code_ue as value')
            ->selectRaw("CONCAT(code_ue, ' - ', name) AS name")
            ->get();
    }


    public static function getOfficesWithFullNamebySelect()
    {

        return static::select('code_ue as value')
                ->selectRaw("CONCAT(code_ue, ' - ', name) AS name")
                ->get()
                ->pluck('name', 'value')
                ->toArray();
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class, 'institution');
    }

    public function person(){
        return $this->belongsToMany(Person::class, 'person_offices');
    }

   
}
