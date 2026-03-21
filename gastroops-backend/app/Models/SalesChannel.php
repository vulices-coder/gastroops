<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesChannel extends Model
{
    protected $fillable = [
        'operational_unit_id',
        'channel_type',
        'platform_name',
        'active',
    ];

    public function fees()
    {
        return $this->hasMany(ChannelFee::class);
    }
}