<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        $departments = Department::with(['admin', 'head'])->latest()->get();

        return view('settings.departments.index', compact('departments'));
    }

    public function create(): View
    {
        $employees = User::where('isadmin', false)
            ->where('superuser', false)
            ->get();

        return view('settings.departments.create', compact('employees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'department_head_id' => ['nullable', 'exists:users,id'],
        ]);

        $data['admin_id'] = $request->user()->id;

        $department = Department::create($data);

        if ($department->department_head_id) {
            User::where('id', $department->department_head_id)->update(['isadmin' => true]);
        }

        return redirect()->route('settings.departments.index')->with('status', 'Department created successfully.');
    }

    public function edit(Department $department): View
    {
        $employees = User::where('isadmin', false)
            ->where('superuser', false)
            ->orWhere('id', $department->department_head_id)
            ->get();

        return view('settings.departments.edit', compact('department', 'employees'));
    }

    public function update(Request $request, Department $department): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'department_head_id' => ['nullable', 'exists:users,id'],
        ]);

        $oldHeadId = $department->department_head_id;
        $newHeadId = $data['department_head_id'];

        if ($oldHeadId !== $newHeadId) {
            if ($oldHeadId) {
                User::where('id', $oldHeadId)->update(['isadmin' => false]);
            }
            if ($newHeadId) {
                User::where('id', $newHeadId)->update(['isadmin' => true]);
            }
        }

        $department->update($data);

        return redirect()->route('settings.departments.index')->with('status', 'Department updated successfully.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        if ($department->department_head_id) {
            User::where('id', $department->department_head_id)->update(['isadmin' => false]);
        }
        User::where('department_id', $department->id)->update(['department_id' => null]);
        $department->delete();

        return redirect()->route('settings.departments.index')->with('status', 'Department deleted successfully.');
    }
}
