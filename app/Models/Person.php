<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $table = 'people';

    protected $fillable = [
        'name',
        'lastname',
        'person_type_id',
        'document_type_id',
        'document_number',
        'address',
        'phone',
        'cellphone',
        'image',
        'active',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function office()
    {
        return $this->belongsToMany(Office::class, 'person_offices');
    }
}
