<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\CandidateApplication;
use App\Models\Department;
use App\Models\JobVacancy;
use App\Models\Leave;
use App\Models\Payroll;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user->superuser && !$user->isadmin) {
            if ($user->department_id) {
                return redirect()->route('panel.index', ['dptid' => $user->department_id]);
            }
            return redirect()->route('profile.edit');
        }

        $isSuperuser = $user->superuser;
        $isDeptAdmin = $user->isadmin;
        $departmentId = $user->department_id;

        $totalEmployees = $isSuperuser
            ? User::count()
            : User::where('department_id', $departmentId)->count();

        $activeEmployees = $isSuperuser
            ? User::where('status', true)->count()
            : User::where('department_id', $departmentId)->where('status', true)->count();

        $pendingLeaves = $isSuperuser
            ? Leave::where('status', 'pending')->count()
            : Leave::where('department_id', $departmentId)->where('status', 'pending')->count();

        $myPendingLeaves = Leave::where('staff_id', $user->id)->where('status', 'pending')->count();

        $today = now()->format('Y-m-d');
        $myAttendance = Attendance::where('user_id', $user->id)->where('date', $today)->first();

        $todayCheckedIn = $isSuperuser
            ? Attendance::where('date', $today)->whereNotNull('check_in')->count()
            : Attendance::where('department_id', $departmentId)->where('date', $today)->whereNotNull('check_in')->count();

        $latestPayroll = Payroll::where('user_id', $user->id)->latest('payroll_month')->first();

        $totalPayrolls = $isSuperuser || $isDeptAdmin
            ? ($isSuperuser
                ? Payroll::count()
                : Payroll::where('department_id', $departmentId)->count())
            : null;

        $activeVacancies = $isSuperuser
            ? JobVacancy::where('status', 'open')->count()
            : JobVacancy::where('department_id', $departmentId)->where('status', 'open')->count();

        $totalApplications = $isSuperuser
            ? CandidateApplication::count()
            : CandidateApplication::whereHas('vacancy', fn ($q) => $q->where('department_id', $departmentId))->count();

        $totalDepartments = Department::count();

        return view('dashboard', [
            'totalEmployees' => $totalEmployees,
            'activeEmployees' => $activeEmployees,
            'pendingLeaves' => $pendingLeaves,
            'myPendingLeaves' => $myPendingLeaves,
            'myAttendance' => $myAttendance,
            'todayCheckedIn' => $todayCheckedIn,
            'latestPayroll' => $latestPayroll,
            'totalPayrolls' => $totalPayrolls,
            'activeVacancies' => $activeVacancies,
            'totalApplications' => $totalApplications,
            'totalDepartments' => $totalDepartments,
            'isSuperuser' => $isSuperuser,
            'isDeptAdmin' => $isDeptAdmin,
        ]);
    }
}
