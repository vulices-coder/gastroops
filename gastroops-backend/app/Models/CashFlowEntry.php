<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashFlowEntry extends Model
{
    protected $fillable = [
        'operational_unit_id',
        'entry_type',
        'category',
        'amount',
        'entry_date',
        'notes',
    ];
}