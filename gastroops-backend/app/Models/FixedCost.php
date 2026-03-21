<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FixedCost extends Model
{
    protected $fillable = [
        'operational_unit_id',
        'name',
        'amount',
        'currency',
        'category',
        'period',
        'active',
    ];
}