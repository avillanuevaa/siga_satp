<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class ViaticRegister extends Model implements Auditable
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
    'affidavit_description_lost_documents',
    'affidavit_amount_lost_documents',
    'affidavit_amount_undocumented_expenses',
    'service_commission_a',
    'service_commission_from',
    'service_commission_date',
    'service_commission_activities_performed',
    'service_commission_results_obtained',
    'user_id',
    'status',
  ];

  public function details()
  {
      return $this->hasMany(ViaticRegisterDetail::class);
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
