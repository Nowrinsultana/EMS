<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\DailyQrCode;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MyAttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $attendances = Attendance::where('user_id', $request->user()->id)
            ->latest('date')
            ->get();

        $today = now()->format('Y-m-d');
        $todayRecord = $attendances->firstWhere('date', $today);

        $dptid = $request->route('dptid');
        $checkInUrl = null;

        $qr = DailyQrCode::where('date', $today)->first();
        if ($qr && $qr->is_active) {
            $checkInUrl = route('attendance.scan', ['dptid' => $dptid, 'token' => $qr->check_in_token]);
        }

        return view('attendance.my', compact('attendances', 'todayRecord', 'checkInUrl'));
    }

    public function checkIn(Request $request): RedirectResponse
    {
        $dptid = $request->route('dptid');
        $user = $request->user();
        $today = now()->format('Y-m-d');

        $existing = Attendance::firstOrNew(
            ['user_id' => $user->id, 'date' => $today],
            ['department_id' => $dptid],
        );

        if ($existing->check_in) {
            return back()->with('error', 'Already checked in today.');
        }

        $existing->check_in = now();
        $existing->status = 'present';
        $existing->save();

        return back()->with('status', 'Checked in successfully.');
    }

    public function checkOut(Request $request): RedirectResponse
    {
        $dptid = $request->route('dptid');
        $user = $request->user();
        $today = now()->format('Y-m-d');

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if (!$attendance || !$attendance->check_in) {
            return back()->with('error', 'Not checked in yet.');
        }

        if ($attendance->check_out) {
            return back()->with('error', 'Already checked out today.');
        }

        $attendance->check_out = now();
        $attendance->save();

        return back()->with('status', 'Checked out successfully.');
    }

    public function scan(Request $request, $dptid, string $token): RedirectResponse
    {
        $qr = DailyQrCode::where('check_in_token', $token)->first();

        abort_unless($qr && $qr->is_active, 404);

        $user = $request->user();
        $today = $qr->date->format('Y-m-d');

        if ($today !== now()->format('Y-m-d')) {
            return back()->with('error', 'This QR code has expired.');
        }

        $attendance = Attendance::firstOrNew(
            ['user_id' => $user->id, 'date' => $today],
            ['department_id' => $dptid],
        );

        if (!$attendance->check_in) {
            $attendance->check_in = now();
            $attendance->qr_check_in = true;
            $attendance->status = 'present';
            $attendance->save();

            return redirect()->route('attendance.my', ['dptid' => $dptid])
                ->with('status', 'Successfully checked in via QR.');
        }

        if (!$attendance->check_out) {
            $attendance->check_out = now();
            $attendance->qr_check_out = true;
            $attendance->save();

            return redirect()->route('attendance.my', ['dptid' => $dptid])
                ->with('status', 'Successfully checked out via QR.');
        }

        return redirect()->route('attendance.my', ['dptid' => $dptid])
            ->with('error', 'Already checked in and out today.');
    }
}
