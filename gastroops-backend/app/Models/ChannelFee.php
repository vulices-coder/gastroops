<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelFee extends Model
{
    protected $fillable = [
        'sales_channel_id',
        'percentage',
        'fixed_amount',
        'period',
    ];

    public function channel()
    {
        return $this->belongsTo(SalesChannel::class, 'sales_channel_id');
    }
}