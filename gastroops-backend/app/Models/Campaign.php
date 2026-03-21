<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'operational_unit_id',
        'name',
        'platform',
        'budget',
        'starts_on',
        'ends_on',
        'objective',
        'status',
    ];
}