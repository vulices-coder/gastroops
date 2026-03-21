<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SalesChannel;
use Illuminate\Http\Request;

class SalesChannelController extends Controller
{
    public function index(Request $request)
    {
        $unitId = $request->query('unit_id');

        $query = SalesChannel::query()->with('fees');

        if ($unitId) {
            $query->where('operational_unit_id', $unitId);
        }

        return response()->json($query->latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'operational_unit_id' => ['required', 'exists:operational_units,id'],
            'channel_type' => ['required'],
            'platform_name' => ['nullable'],
            'active' => ['required', 'boolean'],
            'fee_percentage' => ['nullable', 'numeric'],
            'fee_fixed_amount' => ['nullable', 'numeric'],
            'fee_period' => ['required'],
        ]);

        $channel = SalesChannel::create([
            'operational_unit_id' => $validated['operational_unit_id'],
            'channel_type' => $validated['channel_type'],
            'platform_name' => $validated['platform_name'] ?? null,
            'active' => $validated['active'],
        ]);

        $channel->fees()->create([
            'percentage' => $validated['fee_percentage'] ?? null,
            'fixed_amount' => $validated['fee_fixed_amount'] ?? null,
            'period' => $validated['fee_period'],
        ]);

        return response()->json($channel->load('fees'), 201);
    }

    public function update(Request $request, SalesChannel $salesChannel)
    {
        $validated = $request->validate([
            'operational_unit_id' => ['required', 'exists:operational_units,id'],
            'channel_type' => ['required'],
            'platform_name' => ['nullable'],
            'active' => ['required', 'boolean'],
            'fee_percentage' => ['nullable', 'numeric'],
            'fee_fixed_amount' => ['nullable', 'numeric'],
            'fee_period' => ['required'],
        ]);

        $salesChannel->update([
            'operational_unit_id' => $validated['operational_unit_id'],
            'channel_type' => $validated['channel_type'],
            'platform_name' => $validated['platform_name'] ?? null,
            'active' => $validated['active'],
        ]);

        $fee = $salesChannel->fees()->first();

        if ($fee) {
            $fee->update([
                'percentage' => $validated['fee_percentage'] ?? null,
                'fixed_amount' => $validated['fee_fixed_amount'] ?? null,
                'period' => $validated['fee_period'],
            ]);
        } else {
            $salesChannel->fees()->create([
                'percentage' => $validated['fee_percentage'] ?? null,
                'fixed_amount' => $validated['fee_fixed_amount'] ?? null,
                'period' => $validated['fee_period'],
            ]);
        }

        return response()->json($salesChannel->load('fees'));
    }

    public function destroy(SalesChannel $salesChannel)
    {
        $salesChannel->delete();

        return response()->json([
            'message' => 'deleted',
        ]);
    }

    public function summary(Request $request)
    {
        $unitId = $request->query('unit_id');

        $channels = SalesChannel::query()
            ->with('fees')
            ->when($unitId, fn ($q) => $q->where('operational_unit_id', $unitId))
            ->where('active', true)
            ->get();

        $monthlyFees = 0;

        foreach ($channels as $channel) {
            foreach ($channel->fees as $fee) {
                if ($fee->period === 'monthly') {
                    $monthlyFees += (float) ($fee->fixed_amount ?? 0);
                }

                if ($fee->period === 'yearly') {
                    $monthlyFees += (float) (($fee->fixed_amount ?? 0) / 12);
                }
            }
        }

        return response()->json([
            'active_channels' => $channels->count(),
            'monthly_channel_fees' => $monthlyFees,
        ]);
    }
}