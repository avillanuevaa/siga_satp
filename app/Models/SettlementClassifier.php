<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettlementClassifier extends Model
{
  use HasFactory;

  protected $table = 'settlement_classifiers';

  protected $fillable = [
    'settlement_id',
    'financial_classifier_id',
    'code_classify',
    'name_classify',
    'goal_one',
    'goal_two',
    'goal_three',
  ];

  protected $hidden = [
    'id',
    'settlement_id',
    'created_at',
    'updated_at'
  ];
}
