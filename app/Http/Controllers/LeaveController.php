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

        $oldStatus = $leave->status;
        $oldStartDate = $leave->start_date->format('Y-m-d');
        $oldEndDate = $leave->end_date->format('Y-m-d');

        $data = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'string', 'in:pending,approved,declined'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $leave->update($data);

        $newStatus = $leave->status;
        $newStartDate = $leave->start_date->format('Y-m-d');
        $newEndDate = $leave->end_date->format('Y-m-d');

        $wasApproved = $oldStatus === LeaveStatus::Approved;
        $nowApproved = $newStatus === LeaveStatus::Approved;

        if ($wasApproved !== $nowApproved || ($wasApproved && ($oldStartDate !== $newStartDate || $oldEndDate !== $newEndDate))) {
            $leave->staff->syncLeaveDates(
                nowApproved: $nowApproved,
                startDate: $newStartDate,
                endDate: $newEndDate,
                wasApproved: $wasApproved,
                oldStartDate: $oldStartDate,
                oldEndDate: $oldEndDate,
            );
        }

        return redirect()->route('leave.index', ['dptid' => $dptid])
            ->with('status', 'Leave updated successfully.');
    }

    public function approve($dptid, Leave $leave): RedirectResponse
    {
        abort_if((int) $leave->department_id !== (int) $dptid, 404);

        $wasApproved = $leave->status === LeaveStatus::Approved;

        $leave->update(['status' => LeaveStatus::Approved]);

        if (!$wasApproved) {
            $leave->staff->syncLeaveDates(
                nowApproved: true,
                startDate: $leave->start_date->format('Y-m-d'),
                endDate: $leave->end_date->format('Y-m-d'),
            );
        }

        return back()->with('status', 'Leave approved.');
    }

    public function decline($dptid, Leave $leave): RedirectResponse
    {
        abort_if((int) $leave->department_id !== (int) $dptid, 404);

        $wasApproved = $leave->status === LeaveStatus::Approved;

        $leave->update(['status' => LeaveStatus::Declined]);

        if ($wasApproved) {
            $leave->staff->syncLeaveDates(
                nowApproved: false,
                startDate: $leave->start_date->format('Y-m-d'),
                endDate: $leave->end_date->format('Y-m-d'),
                wasApproved: true,
            );
        }

        return back()->with('status', 'Leave declined.');
    }
}
