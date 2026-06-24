<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    public function index(Request $request): View
    {
        $dptid = $request->route('dptid');

        $employees = User::where('department_id', $dptid)
            ->where('superuser', false)
            ->latest()
            ->get();

        return view('employees.index', compact('employees', 'dptid'));
    }

    public function create(Request $request): View
    {
        $dptid = $request->route('dptid');
        $department = \App\Models\Department::find($dptid);

        return view('employees.create', compact('dptid', 'department'));
    }

    public function store(Request $request): RedirectResponse
    {
        $dptid = $request->route('dptid');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date'],
            'passport_number' => ['nullable', 'string', 'max:50'],
            'staff_id' => ['nullable', 'string', 'max:50', 'unique:users,staff_id'],
            'leave_balance' => ['nullable', 'integer', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'status' => ['nullable', 'boolean'],
        ]);

        $data['department_id'] = $dptid;
        $data['password'] = Hash::make(Str::password(12));

        $user = User::create($data);

        $token = Str::random(60);
        $user->update(['setup_token' => $token]);

        $setupUrl = route('password.setup', ['token' => $token]);

        return redirect()->route('employees.index', ['dptid' => $dptid])
            ->with('status', 'Employee added successfully.')
            ->with('setup_url', $setupUrl);
    }

    public function edit(Request $request, $dptid, User $employee): View
    {
        abort_if((int) $employee->department_id !== (int) $dptid, 404);

        return view('employees.edit', compact('employee', 'dptid'));
    }

    public function update(Request $request, $dptid, User $employee): RedirectResponse
    {
        abort_if((int) $employee->department_id !== (int) $dptid, 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $employee->id],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date'],
            'passport_number' => ['nullable', 'string', 'max:50'],
            'staff_id' => ['nullable', 'string', 'max:50', 'unique:users,staff_id,' . $employee->id],
            'leave_balance' => ['nullable', 'integer', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'status' => ['nullable', 'boolean'],
        ]);

        $employee->update($data);

        return redirect()->route('employees.index', ['dptid' => $dptid])
            ->with('status', 'Employee updated successfully.');
    }

    public function destroy(Request $request, $dptid, User $employee): RedirectResponse
    {
        abort_if((int) $employee->department_id !== (int) $dptid, 404);

        if ($employee->isadmin) {
            return redirect()->route('employees.index', ['dptid' => $dptid])
                ->with('error', 'Department admins cannot be deleted.');
        }

        $employee->delete();

        return redirect()->route('employees.index', ['dptid' => $dptid])
            ->with('status', 'Employee deleted successfully.');
    }

    public function show(Request $request, $dptid, User $employee): View
    {
        abort_if((int) $employee->department_id !== (int) $dptid, 404);

        $documents = $employee->documents()->latest()->get();

        return view('employees.show', compact('employee', 'dptid', 'documents'));
    }

    public function uploadDocument(Request $request, $dptid, User $employee): RedirectResponse
    {
        abort_if((int) $employee->department_id !== (int) $dptid, 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:50'],
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png', 'max:10240'],
        ]);

        $path = $request->file('file')->store('documents/' . $employee->id, 'public');

        $employee->documents()->create([
            'name' => $data['name'],
            'type' => $data['type'],
            'file_path' => $path,
        ]);

        return redirect()->route('employees.show', ['dptid' => $dptid, 'employee' => $employee])
            ->with('status', 'Document uploaded successfully.');
    }

    public function destroyDocument(Request $request, $dptid, User $employee, Document $document): RedirectResponse
    {
        abort_if((int) $employee->department_id !== (int) $dptid, 404);
        abort_if((int) $document->user_id !== (int) $employee->id, 404);

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return redirect()->route('employees.show', ['dptid' => $dptid, 'employee' => $employee])
            ->with('status', 'Document deleted successfully.');
    }
}
