<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\OperationalUnit;
use Illuminate\Http\Request;

class OperationalUnitController extends Controller
{
    public function index()
    {
        return response()->json(
            OperationalUnit::query()->latest()->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'unit_type' => ['required', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'active' => ['required', 'boolean'],
        ]);

        $unit = OperationalUnit::create($validated);

        return response()->json($unit, 201);
    }

    public function update(Request $request, OperationalUnit $operationalUnit)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'unit_type' => ['required', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'active' => ['required', 'boolean'],
        ]);

        $operationalUnit->update($validated);

        return response()->json($operationalUnit);
    }

    public function destroy(OperationalUnit $operationalUnit)
    {
        $operationalUnit->delete();

        return response()->json([
            'message' => 'Operational unit deleted successfully.',
        ]);
    }
}