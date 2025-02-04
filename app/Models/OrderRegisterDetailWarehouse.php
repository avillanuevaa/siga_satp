<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class OrderRegisterDetailWarehouse extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  use HasFactory;

  protected $perPage = 20;

  protected $fillable = [
    'package',
    'package_text',
    'detail',
    'measure',
    'quantity',
    'unit_value',
    'total',
    'order_register_detail_id',
  ];

  protected $hidden = [
    'id',
    'order_register_detail_id',
    'created_at',
    'updated_at',
  ];
}
