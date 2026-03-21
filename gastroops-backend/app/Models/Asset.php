<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'operational_unit_id',
        'name',
        'asset_type',
        'purchase_price',
        'purchase_date',
        'active',
    ];
}