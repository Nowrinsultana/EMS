<?php

namespace App\Http\Controllers;

use App\Enums\LeaveStatus;
use App\Models\Leave;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveController extends Controller
{
    public function index(Request $request): View
    {
        $dptid = $request->route('dptid');

        $leaves = Leave::with('staff')
            ->where('department_id', $dptid)
            ->latest()
            ->get();

        return view('leave.index', compact('leaves', 'dptid'));
    }

    public function edit(Request $request, $dptid, Leave $leave): View
    {
        abort_if((int) $leave->department_id !== (int) $dptid, 404);

        return view('leave.edit', compact('leave', 'dptid'));
    }

    public function update(Request $request, $dptid, Leave $leave): RedirectResponse
    {
        abort_if((int) $leave->department_id !== (int) $dptid, 404);

        $data = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'string', 'in:pending,approved,declined'],
        ]);

        $leave->update($data);

        return redirect()->route('leave.index', ['dptid' => $dptid])
            ->with('status', 'Leave updated successfully.');
    }

    public function approve($dptid, Leave $leave): RedirectResponse
    {
        abort_if((int) $leave->department_id !== (int) $dptid, 404);

        $leave->update(['status' => LeaveStatus::Approved]);

        return back()->with('status', 'Leave approved.');
    }

    public function decline($dptid, Leave $leave): RedirectResponse
    {
        abort_if((int) $leave->department_id !== (int) $dptid, 404);

        $leave->update(['status' => LeaveStatus::Declined]);

        return back()->with('status', 'Leave declined.');
    }
}
