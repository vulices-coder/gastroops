<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\UtilityBill;
use Illuminate\Http\Request;

class UtilityBillController extends Controller
{
    public function index(Request $request)
    {
        $unitId = $request->query('unit_id');

        $query = UtilityBill::query();

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
            'utility_type' => ['required'],
            'billing_period_start' => ['nullable', 'date'],
            'billing_period_end' => ['nullable', 'date'],
            'amount' => ['required', 'numeric'],
            'usage_value' => ['nullable', 'numeric'],
            'provider_name' => ['nullable'],
        ]);

        $bill = UtilityBill::create($validated);

        return response()->json($bill, 201);
    }

    public function update(Request $request, UtilityBill $utilityBill)
    {
        $validated = $request->validate([
            'operational_unit_id' => ['required', 'exists:operational_units,id'],
            'utility_type' => ['required'],
            'billing_period_start' => ['nullable', 'date'],
            'billing_period_end' => ['nullable', 'date'],
            'amount' => ['required', 'numeric'],
            'usage_value' => ['nullable', 'numeric'],
            'provider_name' => ['nullable'],
        ]);

        $utilityBill->update($validated);

        return response()->json($utilityBill);
    }

    public function destroy(UtilityBill $utilityBill)
    {
        $utilityBill->delete();

        return response()->json([
            'message' => 'deleted',
        ]);
    }

    public function summary(Request $request)
    {
        $unitId = $request->query('unit_id');

        $query = UtilityBill::query();

        if ($unitId) {
            $query->where('operational_unit_id', $unitId);
        }

        return response()->json([
            'monthly_utilities' => (float) $query->sum('amount'),
            'utility_bills_count' => $query->count(),
        ]);
    }
}