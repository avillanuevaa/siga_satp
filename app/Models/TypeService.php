<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeService extends Model
{
    use HasFactory;

    protected $fillable =[
        'number',
        'idcategory',
        'detail',
        'classifier',
        'description'
        ];
}
