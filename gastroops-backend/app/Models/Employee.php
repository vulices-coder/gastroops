<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'operational_unit_id',
        'first_name',
        'last_name',
        'role',
        'employment_type',
        'monthly_salary',
        'hourly_rate',
        'active',
    ];
}