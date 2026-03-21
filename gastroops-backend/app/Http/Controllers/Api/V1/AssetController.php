<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $unitId = $request->query('unit_id');

        $query = Asset::query();

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
            'asset_type' => ['required'],
            'purchase_price' => ['required', 'numeric'],
            'purchase_date' => ['nullable', 'date'],
            'active' => ['required', 'boolean'],
        ]);

        $asset = Asset::create($validated);

        return response()->json($asset, 201);
    }

    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'operational_unit_id' => ['required', 'exists:operational_units,id'],
            'name' => ['required'],
            'asset_type' => ['required'],
            'purchase_price' => ['required', 'numeric'],
            'purchase_date' => ['nullable', 'date'],
            'active' => ['required', 'boolean'],
        ]);

        $asset->update($validated);

        return response()->json($asset);
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();

        return response()->json([
            'message' => 'deleted',
        ]);
    }

    public function summary(Request $request)
    {
        $unitId = $request->query('unit_id');

        $query = Asset::query()->where('active', true);

        if ($unitId) {
            $query->where('operational_unit_id', $unitId);
        }

        $assets = $query->get();

        return response()->json([
            'active_assets' => $assets->count(),
            'total_asset_value' => (float) $assets->sum('purchase_price'),
        ]);
    }
}