<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class CashRegisterDetail extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $perPage = 20;

    protected $fillable = [
        'issue_date',
        'issue_type',
        'issue_description',
        'issue_serie',
        'issue_number',
        'supplier_type',
        'supplier_number',
        'supplier_name',
        'taxed_base',
        'igv',
        'untaxed_base',
        'impbp',
        'other_concepts',
        'total',
        'cost_center_code',
        'cost_center_description',
        'goal_code',
        'goal_description',
        'classifier_code',
        'classifier_descripcion',
        'classifier_amount',
        'expense_description',
        'cash_register_id',
        'enter_to_warehouse',
    ];

    // Definir la relación inversa hacia CashRegister
    public function cashRegister(){
        return $this->belongsTo(CashRegister::class);
    }

    public function warehouses(){
        return $this->hasMany(CashRegisterDetailWarehouse::class, 'cash_register_detail_id');
    }
}