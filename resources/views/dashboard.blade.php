@php
    use App\Models\Department;
    $user = Auth::user();
    $isSuperuser = $user->superuser;
    $isDeptAdmin = $user->isadmin;
    $routeDptid = request()->route('dptid');
    $currentDptid = $routeDptid ?? ($isSuperuser ? Department::value('id') : $user?->department_id);
@endphp
<x-layout>
    <div class="max-w-7xl mx-auto mt-10 px-4">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-600 mt-1">Welcome, {{ $user->name }}!</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Employees --}}
            <div class="bg-white rounded-lg shadow p-6 flex flex-col">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Employees</h2>
                </div>
                <p class="text-sm text-gray-600 mb-1"><span class="font-medium">{{ $totalEmployees }}</span> total</p>
                <p class="text-sm text-gray-600 mb-4"><span class="font-medium">{{ $activeEmployees }}</span> active</p>
                <div class="mt-auto">
                    @if (($isSuperuser || $isDeptAdmin) && $currentDptid)
                        <a href="{{ route('employees.index', ['dptid' => $currentDptid]) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">View Employees &rarr;</a>
                    @else
                        <span class="text-sm text-gray-400">Contact your admin for employee details</span>
                    @endif
                </div>
            </div>

            {{-- Leave --}}
            <div class="bg-white rounded-lg shadow p-6 flex flex-col">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Leave</h2>
                </div>
                @if ($isSuperuser || $isDeptAdmin)
                    <p class="text-sm text-gray-600 mb-1"><span class="font-medium">{{ $pendingLeaves }}</span> pending requests</p>
                @endif
                <p class="text-sm text-gray-600 mb-4"><span class="font-medium">{{ $myPendingLeaves }}</span> my pending</p>
                <div class="mt-auto flex gap-3">
                    @if ($currentDptid)
                        <a href="{{ route('leave.my', ['dptid' => $currentDptid]) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">My Leave &rarr;</a>
                    @endif
                    @if (($isSuperuser || $isDeptAdmin) && $currentDptid)
                        <a href="{{ route('leave.index', ['dptid' => $currentDptid]) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Manage &rarr;</a>
                    @endif
                </div>
            </div>

            {{-- Attendance --}}
            <div class="bg-white rounded-lg shadow p-6 flex flex-col">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Attendance</h2>
                </div>
                @if ($myAttendance && $myAttendance->check_in)
                    <p class="text-sm text-green-600 mb-1">Checked in at {{ $myAttendance->check_in->format('H:i') }}</p>
                    @if ($myAttendance->check_out)
                        <p class="text-sm text-gray-600 mb-4">Checked out at {{ $myAttendance->check_out->format('H:i') }}</p>
                    @else
                        <p class="text-sm text-yellow-600 mb-4">Not yet checked out</p>
                    @endif
                @else
                    <p class="text-sm text-gray-600 mb-4">Not checked in today</p>
                @endif
                @if ($isSuperuser || $isDeptAdmin)
                    <p class="text-sm text-gray-600 mb-1"><span class="font-medium">{{ $todayCheckedIn }}</span> checked in today</p>
                @endif
                <div class="mt-auto flex gap-3">
                    @if ($currentDptid)
                        <a href="{{ route('attendance.my', ['dptid' => $currentDptid]) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">My Attendance &rarr;</a>
                    @endif
                    @if (($isSuperuser || $isDeptAdmin) && $currentDptid)
                        <a href="{{ route('attendance.index', ['dptid' => $currentDptid]) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Manage &rarr;</a>
                    @endif
                </div>
            </div>

            {{-- Payroll --}}
            <div class="bg-white rounded-lg shadow p-6 flex flex-col">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Payroll</h2>
                </div>
                @if ($latestPayroll)
                    <p class="text-sm text-gray-600 mb-1">Latest: {{ $latestPayroll->payroll_month->format('M Y') }}</p>
                    <p class="text-sm text-gray-600 mb-4">Net: ${{ number_format($latestPayroll->net_salary, 2) }}</p>
                @else
                    <p class="text-sm text-gray-600 mb-4">No payroll records yet</p>
                @endif
                @if ($totalPayrolls !== null)
                    <p class="text-sm text-gray-600 mb-1"><span class="font-medium">{{ $totalPayrolls }}</span> total records</p>
                @endif
                <div class="mt-auto">
                    @if ($currentDptid)
                        @if ($isSuperuser || $isDeptAdmin)
                            <a href="{{ route('payroll.index', ['dptid' => $currentDptid]) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">View Payroll &rarr;</a>
                        @else
                            <span class="text-sm text-gray-400">Contact admin for payroll details</span>
                        @endif
                    @else
                        <span class="text-sm text-gray-400">Contact admin for payroll details</span>
                    @endif
                </div>
            </div>

            {{-- Recruitment --}}
            <div class="bg-white rounded-lg shadow p-6 flex flex-col">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-pink-100 rounded-lg">
                        <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Recruitment</h2>
                </div>
                <p class="text-sm text-gray-600 mb-1"><span class="font-medium">{{ $activeVacancies }}</span> active vacancies</p>
                <p class="text-sm text-gray-600 mb-4"><span class="font-medium">{{ $totalApplications }}</span> applications</p>
                <div class="mt-auto">
                    @if ($currentDptid && ($isSuperuser || $isDeptAdmin))
                        <a href="{{ route('recruitment.index', ['dptid' => $currentDptid]) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">View Recruitment &rarr;</a>
                    @else
                        <a href="{{ route('jobs.list') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Browse Jobs &rarr;</a>
                    @endif
                </div>
            </div>

            {{-- Settings --}}
            @if ($isSuperuser)
                <div class="bg-white rounded-lg shadow p-6 flex flex-col">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 bg-gray-100 rounded-lg">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900">Settings</h2>
                    </div>
                    <p class="text-sm text-gray-600 mb-4"><span class="font-medium">{{ $totalDepartments }}</span> departments</p>
                    <div class="mt-auto">
                        <a href="{{ route('settings.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">View Settings &rarr;</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layout>
