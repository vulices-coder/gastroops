<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CashFlowEntry;
use Illuminate\Http\Request;

class CashFlowEntryController extends Controller
{
    public function index(Request $request)
    {
        $unitId = $request->query('unit_id');

        $query = CashFlowEntry::query();

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
            'entry_type' => ['required', 'in:in,out'],
            'category' => ['required'],
            'amount' => ['required', 'numeric'],
            'entry_date' => ['nullable', 'date'],
            'notes' => ['nullable'],
        ]);

        $entry = CashFlowEntry::create($validated);

        return response()->json($entry, 201);
    }

    public function update(Request $request, CashFlowEntry $cashFlowEntry)
    {
        $validated = $request->validate([
            'operational_unit_id' => ['required', 'exists:operational_units,id'],
            'entry_type' => ['required', 'in:in,out'],
            'category' => ['required'],
            'amount' => ['required', 'numeric'],
            'entry_date' => ['nullable', 'date'],
            'notes' => ['nullable'],
        ]);

        $cashFlowEntry->update($validated);

        return response()->json($cashFlowEntry);
    }

    public function destroy(CashFlowEntry $cashFlowEntry)
    {
        $cashFlowEntry->delete();

        return response()->json([
            'message' => 'deleted',
        ]);
    }

    public function summary(Request $request)
    {
        $unitId = $request->query('unit_id');

        $query = CashFlowEntry::query();

        if ($unitId) {
            $query->where('operational_unit_id', $unitId);
        }

        $cashIn = (clone $query)->where('entry_type', 'in')->sum('amount');
        $cashOut = (clone $query)->where('entry_type', 'out')->sum('amount');

        return response()->json([
            'cash_in' => (float) $cashIn,
            'cash_out' => (float) $cashOut,
            'net_cashflow' => (float) $cashIn - (float) $cashOut,
        ]);
    }
}