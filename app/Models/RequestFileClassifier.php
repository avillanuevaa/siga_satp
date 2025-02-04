<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestFileClassifier extends Model
{
    use HasFactory;

    protected $table = 'request_classifiers';

    protected $fillable = [
        'request_id',
        'financial_classifier_id',
        'code_classify',
        'name_classify',
        'goal_one',
        'goal_two',
        'goal_three',      
    ];

    protected $hidden = [
        'id',
        'request_id',
        'created_at',
        'updated_at'
    ];
}
