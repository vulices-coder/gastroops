<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $fillable = [
        'operational_unit_id',
        'name',
        'authority',
        'issue_date',
        'expiry_date',
        'renewal_cost',
        'status',
    ];
}