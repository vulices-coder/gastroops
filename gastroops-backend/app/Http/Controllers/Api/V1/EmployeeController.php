<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $unitId = $request->query('unit_id');

        $query = Employee::query();

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
            'first_name' => ['required'],
            'last_name' => ['required'],
            'role' => ['required'],
            'employment_type' => ['required'],
            'monthly_salary' => ['required', 'numeric'],
            'hourly_rate' => ['required', 'numeric'],
            'active' => ['required', 'boolean'],
        ]);

        $employee = Employee::create($validated);

        return response()->json($employee, 201);
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'operational_unit_id' => ['required', 'exists:operational_units,id'],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'role' => ['required'],
            'employment_type' => ['required'],
            'monthly_salary' => ['required', 'numeric'],
            'hourly_rate' => ['required', 'numeric'],
            'active' => ['required', 'boolean'],
        ]);

        $employee->update($validated);

        return response()->json($employee);
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return response()->json([
            'message' => 'deleted',
        ]);
    }

    public function summary(Request $request)
    {
        $unitId = $request->query('unit_id');

        $query = Employee::query()->where('active', true);

        if ($unitId) {
            $query->where('operational_unit_id', $unitId);
        }

        $employees = $query->get();

        $monthlyTotal = $employees->sum('monthly_salary');
        $employeeCount = $employees->count();

        return response()->json([
            'monthly_labor_cost' => (float) $monthlyTotal,
            'employee_count' => $employeeCount,
        ]);
    }
}