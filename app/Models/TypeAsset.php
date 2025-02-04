<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeAsset extends Model
{
    use HasFactory;

    protected $fillable =[
    'number',
    'idcategory',
    'detail',
    'classifier',
    'description'
    ];

    public static function getAllAssetsType(){
        return self::all();
    }

    public static function getAssetsTypeByClassifier($classifier){
        return self::where(['classifier' => $classifier]);
    }

}
