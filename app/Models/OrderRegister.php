<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class OrderRegister extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  use HasFactory;

  protected $perPage = 20;

  protected $fillable = [
    'year',
    'number',
    'opening_date',
    'closing_date',
    'settlement_id',
    'siaf_date',
    'siaf_number',
    'voucher_date',
    'voucher_number',
    'order_pay_electronic_date',
    'user_id',
    'status',
  ];

  public function details()
  {
      return $this->hasMany(OrderRegisterDetail::class);
  }

  public function settlement()
  {
      return $this->hasOne(Settlement::class, 'id', 'settlement_id');
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
