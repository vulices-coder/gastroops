<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $unitId = $request->query('unit_id');

        $query = Campaign::query();

        if ($unitId) {
            $query->where('operational_unit_id', $unitId);
        }

        return response()->json(
            $query->latest()->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'operational_unit_id' => ['required', 'exists:operational_units,id'],
            'name' => ['required'],
            'platform' => ['nullable'],
            'budget' => ['required', 'numeric'],
            'starts_on' => ['nullable', 'date'],
            'ends_on' => ['nullable', 'date'],
            'objective' => ['nullable'],
            'status' => ['required'],
        ]);

        $campaign = Campaign::create($validated);

        return response()->json($campaign, 201);
    }

    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'operational_unit_id' => ['required', 'exists:operational_units,id'],
            'name' => ['required'],
            'platform' => ['nullable'],
            'budget' => ['required', 'numeric'],
            'starts_on' => ['nullable', 'date'],
            'ends_on' => ['nullable', 'date'],
            'objective' => ['nullable'],
            'status' => ['required'],
        ]);

        $campaign->update($validated);

        return response()->json($campaign);
    }

    public function destroy(Campaign $campaign)
    {
        $campaign->delete();

        return response()->json([
            'message' => 'deleted',
        ]);
    }

    public function summary(Request $request)
    {
        $unitId = $request->query('unit_id');

        $query = Campaign::query();

        if ($unitId) {
            $query->where('operational_unit_id', $unitId);
        }

        $activeCampaigns = (clone $query)
            ->where('status', 'active')
            ->count();

        $totalBudget = (clone $query)->sum('budget');

        return response()->json([
            'active_campaigns' => $activeCampaigns,
            'total_marketing_budget' => (float) $totalBudget,
        ]);
    }
}