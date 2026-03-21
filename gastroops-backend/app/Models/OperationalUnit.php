<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationalUnit extends Model
{
    protected $fillable = [
        'name',
        'unit_type',
        'city',
        'active',
    ];
}