<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FinancialClassifier extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',
        'code',
        'name',
        'active'
    ];

    public static function getFindbyTerm($term)
    {
        return DB::table('financial_classifiers')
            ->select('*', DB::raw("CONCAT(code, ' -- ', name) as bindLabel"))
            ->where('active', 1)
            ->where(function ($query) use ($term) {
                $query->where('name', 'LIKE', '%' . $term . '%')
                ->orWhere('code', 'LIKE', $term . '%');
            })->limit(20)->get();
    }
}
