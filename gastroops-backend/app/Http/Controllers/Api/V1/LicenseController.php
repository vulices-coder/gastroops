<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\License;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LicenseController extends Controller
{
    public function index(Request $request)
    {
        $unitId = $request->query('unit_id');

        $query = License::query();

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
            'authority' => ['nullable'],
            'issue_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date'],
            'renewal_cost' => ['required', 'numeric'],
            'status' => ['required'],
        ]);

        $license = License::create($validated);

        return response()->json($license, 201);
    }

    public function update(Request $request, License $license)
    {
        $validated = $request->validate([
            'operational_unit_id' => ['required', 'exists:operational_units,id'],
            'name' => ['required'],
            'authority' => ['nullable'],
            'issue_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date'],
            'renewal_cost' => ['required', 'numeric'],
            'status' => ['required'],
        ]);

        $license->update($validated);

        return response()->json($license);
    }

    public function destroy(License $license)
    {
        $license->delete();

        return response()->json([
            'message' => 'deleted',
        ]);
    }

    public function summary(Request $request)
    {
        $unitId = $request->query('unit_id');

        $query = License::query();

        if ($unitId) {
            $query->where('operational_unit_id', $unitId);
        }

        $activeLicenses = (clone $query)
            ->where('status', 'active')
            ->count();

        $expiringSoon = (clone $query)
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '>=', Carbon::today())
            ->whereDate('expiry_date', '<=', Carbon::today()->addDays(30))
            ->count();

        return response()->json([
            'active_licenses' => $activeLicenses,
            'licenses_expiring_soon' => $expiringSoon,
        ]);
    }
}