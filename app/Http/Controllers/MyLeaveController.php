<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
        ]);

        $leaveDates = $user->leave_dates ?? [];
        $period = new \DatePeriod(
            new \DateTime($data['start_date']),
            new \DateInterval('P1D'),
            (new \DateTime($data['end_date']))->modify('+1 day'),
        );
        foreach ($period as $dt) {
            $dateStr = $dt->format('Y-m-d');
            if (in_array($dateStr, $leaveDates)) {
                return back()
                    ->withErrors(['start_date' => "You already have an approved leave on $dateStr."])
                    ->withInput();
            }
        }

        Leave::create([
            'department_id' => $user->department_id,
            'staff_id' => $user->id,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'status' => 'pending',
        ]);

        return redirect()->route('leave.my', ['dptid' => $request->route('dptid')])
            ->with('status', 'Leave request submitted.');
    }
}
