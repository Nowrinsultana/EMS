<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\DailyQrCode;
use App\Models\User;
use Carbon\Carbon;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $dptid = $request->route('dptid');
        $date = $request->string('date')->value() ?: now()->format('Y-m-d');

        $attendances = Attendance::with('user')
            ->where('department_id', $dptid)
            ->whereDate('date', $date)
            ->latest('check_in')
            ->get();

        $employees = User::where('department_id', $dptid)
            ->where('superuser', false)
            ->orderBy('name')
            ->get();

        return view('attendance.index', compact('dptid', 'date', 'attendances', 'employees'));
    }

    public function summary(Request $request): View
    {
        $dptid = $request->route('dptid');
        $month = $request->string('month')->value() ?: now()->format('Y-m');

        $startOfMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $employees = User::where('department_id', $dptid)
            ->where('superuser', false)
            ->orderBy('name')
            ->get();

        $summary = [];
        foreach ($employees as $emp) {
            $records = Attendance::where('user_id', $emp->id)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->get();

            $present = $records->where('status', 'present')->count();
            $late = $records->where('status', 'late')->count();
            $absent = $records->where('status', 'absent')->count();

            $summary[] = [
                'user' => $emp,
                'present' => $present,
                'late' => $late,
                'absent' => $absent,
                'total' => $present + $late + $absent,
            ];
        }

        return view('attendance.summary', compact('dptid', 'month', 'summary', 'startOfMonth', 'endOfMonth'));
    }

    public function qr(Request $request): View
    {
        $dptid = $request->route('dptid');
        $today = now()->format('Y-m-d');

        $qrCode = DailyQrCode::firstOrCreate(
            ['date' => $today],
            [
                'check_in_token' => bin2hex(random_bytes(32)),
                'is_active' => true,
            ]
        );

        $appUrl = url('/');

        $checkInUrl = route('attendance.scan', [
            'dptid' => $dptid,
            'token' => $qrCode->check_in_token,
        ], true);

        $checkOutUrl = null;
        if ($qrCode->check_out_token) {
            $checkOutUrl = route('attendance.scan', [
                'dptid' => $dptid,
                'token' => $qrCode->check_out_token,
            ], true);
        }

        $options = new QROptions;
        $options->outputType = QRCode::OUTPUT_MARKUP_SVG;
        $options->scale = 8;

        $qrCodeSvg = (new QRCode($options))->render($checkInUrl);

        return view('attendance.qr', compact('dptid', 'qrCode', 'checkInUrl', 'checkOutUrl', 'qrCodeSvg'));
    }

    public function generateCheckOutQr(Request $request, $dptid): RedirectResponse
    {
        $today = now()->format('Y-m-d');

        $qrCode = DailyQrCode::where('date', $today)->first();
        abort_unless($qrCode, 404);

        $qrCode->update([
            'check_out_token' => bin2hex(random_bytes(32)),
        ]);

        return back()->with('status', 'Check-out QR code generated.');
    }

    public function mark(Request $request): RedirectResponse
    {
        $dptid = $request->route('dptid');

        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'date' => ['required', 'date'],
            'check_in' => ['nullable', 'date_format:H:i'],
            'check_out' => ['nullable', 'date_format:H:i', 'after_or_equal:check_in'],
            'status' => ['required', 'in:present,late,absent'],
        ]);

        abort_unless(
            User::whereKey($data['user_id'])->where('department_id', $dptid)->exists(),
            404,
        );

        $record = Attendance::firstOrNew(
            ['user_id' => $data['user_id'], 'date' => $data['date']],
            ['department_id' => $dptid],
        );

        $record->status = $data['status'];
        if ($data['check_in']) {
            $record->check_in = Carbon::parse($data['date'].' '.$data['check_in']);
        }
        if ($data['check_out']) {
            $record->check_out = Carbon::parse($data['date'].' '.$data['check_out']);
        }
        $record->save();

        return back()->with('status', 'Attendance marked.');
    }
}
