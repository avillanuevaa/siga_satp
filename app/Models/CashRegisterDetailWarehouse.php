<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class CashRegisterDetailWarehouse extends Model implements Auditable
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
        'cash_register_detail_id',
        'lesser_package'
    ];

    protected $hidden = [
        'id',
        'cash_register_detail_id',
        'created_at',
        'updated_at',
    ];
}
