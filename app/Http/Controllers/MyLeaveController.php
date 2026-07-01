<?php

namespace App\Http\Controllers;

use App\Enums\LeaveStatus;
use App\Models\Leave;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MyLeaveController extends Controller
{
    public function index(Request $request): View
    {
        $leaves = Leave::where('staff_id', $request->user()->id)
            ->latest()
            ->get();

        return view('leave.my', compact('leaves'));
    }

    public function create(): View
    {
        return view('leave.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $overlapError = $this->checkConflict($user->id, $data['start_date'], $data['end_date']);
        if ($overlapError) {
            return back()->withErrors(['start_date' => $overlapError])->withInput();
        }

        Leave::create([
            'department_id' => $user->department_id,
            'staff_id' => $user->id,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'reason' => $data['reason'],
            'status' => 'pending',
        ]);

        $dptid = $request->route('dptid');

        Notification::create([
            'user_id' => $user->id,
            'type' => 'leave',
            'message' => "Your leave request from {$data['start_date']} to {$data['end_date']} has been submitted.",
            'link' => route('leave.my', ['dptid' => $dptid]),
        ]);

        $admins = User::where('department_id', $dptid)
            ->where(fn ($q) => $q->where('isadmin', true)->orWhere('superuser', true))
            ->where('id', '!=', $user->id)
            ->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'leave',
                'message' => "{$user->name} submitted a leave request from {$data['start_date']} to {$data['end_date']}.",
                'link' => route('leave.index', ['dptid' => $dptid]),
            ]);
        }

        return redirect()->route('leave.my', ['dptid' => $dptid])
            ->with('status', 'Leave request submitted.');
    }

    public function edit($dptid, Leave $leave): View
    {
        abort_if($leave->staff_id !== request()->user()->id, 403);
        abort_if($leave->status !== LeaveStatus::Pending, 403);

        return view('leave.my-edit', compact('leave', 'dptid'));
    }

    public function update(Request $request, $dptid, Leave $leave): RedirectResponse
    {
        abort_if($leave->staff_id !== $request->user()->id, 403);
        abort_if($leave->status !== LeaveStatus::Pending, 403);

        $data = $request->validate([
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $overlapError = $this->checkConflict(
            $request->user()->id,
            $data['start_date'],
            $data['end_date'],
            $leave->id,
        );
        if ($overlapError) {
            return back()->withErrors(['start_date' => $overlapError])->withInput();
        }

        $leave->update($data);

        return redirect()->route('leave.my', ['dptid' => $dptid])
            ->with('status', 'Leave request updated.');
    }

    private function checkConflict(int $userId, string $startDate, string $endDate, ?int $excludeLeaveId = null): ?string
    {
        $conflictDates = [];

        $user = \App\Models\User::find($userId);
        $approvedDates = $user?->leave_dates ?? [];

        $pendingLeaves = Leave::where('staff_id', $userId)
            ->where('status', LeaveStatus::Pending)
            ->when($excludeLeaveId, fn ($q) => $q->where('id', '!=', $excludeLeaveId))
            ->get();

        $period = new \DatePeriod(
            new \DateTime($startDate),
            new \DateInterval('P1D'),
            (new \DateTime($endDate))->modify('+1 day'),
        );
        foreach ($period as $dt) {
            $dateStr = $dt->format('Y-m-d');

            if (in_array($dateStr, $approvedDates)) {
                $conflictDates[] = $dateStr;
                continue;
            }

            foreach ($pendingLeaves as $pl) {
                $plStart = $pl->start_date instanceof \Carbon\Carbon
                    ? $pl->start_date->format('Y-m-d') : $pl->start_date;
                $plEnd = $pl->end_date instanceof \Carbon\Carbon
                    ? $pl->end_date->format('Y-m-d') : $pl->end_date;

                if ($dateStr >= $plStart && $dateStr <= $plEnd) {
                    $conflictDates[] = $dateStr;
                    break;
                }
            }
        }

        if ($conflictDates) {
            $unique = array_unique($conflictDates);
            return 'You already have a leave request on ' . implode(', ', $unique) . '.';
        }

        return null;
    }
}
