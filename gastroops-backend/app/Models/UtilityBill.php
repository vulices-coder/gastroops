<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UtilityBill extends Model
{
    protected $fillable = [
        'operational_unit_id',
        'utility_type',
        'billing_period_start',
        'billing_period_end',
        'amount',
        'usage_value',
        'provider_name',
    ];
}