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

    protected $casts = [
        'active' => 'boolean',
    ];

    // Scopes para consultas comunes
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('active', false);
    }

    public static function getListOffices()
    {
        return static::selectRaw('*, code_ue as value')
            ->selectRaw("CONCAT(code_ue, ' - ', name) AS name")
            ->where('active', true)
            ->get();
    }

    public static function getOfficesWithFullNamebySelect()
    {
        return static::select('code_ue as value')
            ->selectRaw("CONCAT(code_ue, ' - ', name) AS name")
            ->where('active', true)
            ->get()
            ->pluck('name', 'value')
            ->toArray();
    }

    // Relaciones
    public function institution()
    {
        return $this->belongsTo(Institution::class, 'institution_id');
    }

    public function person()
    {
        return $this->belongsToMany(Person::class, 'person_offices');
    }

    public function father()
    {
        return $this->belongsTo(Office::class, 'father_id');
    }

    public function children()
    {
        return $this->hasMany(Office::class, 'father_id');
    }

    // Accessors
    public function getStatusTextAttribute()
    {
        return $this->active ? 'Activo' : 'Inactivo';
    }

    public function getFullNameAttribute()
    {
        return $this->code_ue . ' - ' . $this->name;
    }
}
