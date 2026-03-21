<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\FixedCost;
use Illuminate\Http\Request;

class FixedCostController extends Controller
{
    public function index(Request $request)
    {
        $unitId = $request->query('unit_id');

        $query = FixedCost::query();

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
            'amount' => ['required', 'numeric'],
            'currency' => ['required'],
            'category' => ['nullable'],
            'period' => ['required'],
            'active' => ['required', 'boolean'],
        ]);

        $cost = FixedCost::create($validated);

        return response()->json($cost, 201);
    }

    public function update(Request $request, FixedCost $fixedCost)
    {
        $validated = $request->validate([
            'operational_unit_id' => ['required', 'exists:operational_units,id'],
            'name' => ['required'],
            'amount' => ['required', 'numeric'],
            'currency' => ['required'],
            'category' => ['nullable'],
            'period' => ['required'],
            'active' => ['required', 'boolean'],
        ]);

        $fixedCost->update($validated);

        return response()->json($fixedCost);
    }

    public function destroy(FixedCost $fixedCost)
    {
        $fixedCost->delete();

        return response()->json([
            'message' => 'deleted',
        ]);
    }

    public function summary(Request $request)
    {
        $unitId = $request->query('unit_id');

        $query = FixedCost::query()->where('active', true);

        if ($unitId) {
            $query->where('operational_unit_id', $unitId);
        }

        $monthly = (clone $query)
            ->where('period', 'monthly')
            ->sum('amount');

        $yearly = (clone $query)
            ->where('period', 'yearly')
            ->sum('amount');

        return response()->json([
            'monthly_total' => (float) $monthly + ((float) $yearly / 12),
            'yearly_total' => (float) $yearly + ((float) $monthly * 12),
        ]);
    }
}