@php
    use App\Models\Department;
    use App\Models\Notification as NotificationModel;
    $user = Auth::user();
    $isSuperuser = $user?->superuser;
    $isDeptAdmin = $user?->isadmin;
    $routeDptid = request()->route('dptid');
    $currentDptid = $routeDptid ?? ($isSuperuser ? Department::value('id') : $user?->department_id);
    $routeName = request()->route()?->getName();
    $onPersonalPage = $routeName && (str_starts_with($routeName, 'leave.my') || $routeName === 'attendance.my');
    $unreadCount = $user ? NotificationModel::forUser($user)->unread()->count() : 0;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'EMS') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center space-x-8">
                        <a href="{{ url('/') }}" class="flex items-center gap-2 shrink-0 text-xl font-bold text-gray-800">
                            <svg class="shrink-0" viewBox="0 0 32 32" width="26" height="26" aria-hidden="true"><rect x="0" y="0" width="32" height="32" rx="8" fill="#121826"/><path d="M5 9 L16 16 L5 23 Z" fill="#C8893D"/><path d="M27 9 L16 16 L27 23 Z" fill="#FFFFFF"/><line x1="16" y1="6" x2="16" y2="26" stroke="#C8893D" stroke-width="1" stroke-opacity="0.5"/></svg>PALINDROME
                        </a>
                        @auth
                            <div class="hidden sm:flex items-center space-x-4">
                                <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">Dashboard</a>

                                @if ($isSuperuser || ($isDeptAdmin && $currentDptid))
                                    @if ($onPersonalPage)
                                        <a href="{{ route('leave.my', ['dptid' => $currentDptid]) }}" class="text-sm text-gray-600 hover:text-gray-900">My Leave</a>
                                        <a href="{{ route('attendance.my', ['dptid' => $currentDptid]) }}" class="text-sm text-gray-600 hover:text-gray-900">My Attendance</a>
                                        @if ($isSuperuser)
                                            <a href="{{ route('settings.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Settings</a>
                                        @endif
                                        <span class="text-xs text-gray-300">|</span>
                                        <a href="{{ route('employees.index', ['dptid' => $currentDptid]) }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-800">← Admin</a>
                                    @else
                                        <a href="{{ route('employees.index', ['dptid' => $currentDptid]) }}" class="text-sm text-gray-600 hover:text-gray-900">Employees</a>
                                        <a href="{{ route('leave.index', ['dptid' => $currentDptid]) }}" class="text-sm text-gray-600 hover:text-gray-900">Leave</a>
                                        <a href="{{ route('attendance.index', ['dptid' => $currentDptid]) }}" class="text-sm text-gray-600 hover:text-gray-900">Attendance</a>
                                        <a href="{{ route('payroll.index', ['dptid' => $currentDptid]) }}" class="text-sm text-gray-600 hover:text-gray-900">Payroll</a>
                                        <a href="{{ route('recruitment.index', ['dptid' => $currentDptid]) }}" class="text-sm text-gray-600 hover:text-gray-900">Recruitment</a>
                                        @if ($isSuperuser)
                                            <a href="{{ route('settings.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Settings</a>
                                        @endif
                                        <span class="text-xs text-gray-300">|</span>
                                        <a href="{{ route('leave.my', ['dptid' => $currentDptid]) }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-800">My Panel →</a>
                                    @endif
                                @elseif ($currentDptid)
                                    <a href="{{ route('leave.my', ['dptid' => $currentDptid]) }}" class="text-sm text-gray-600 hover:text-gray-900">My Leave</a>
                                    <a href="{{ route('attendance.my', ['dptid' => $currentDptid]) }}" class="text-sm text-gray-600 hover:text-gray-900">My Attendance</a>
                                @endif
                            </div>
                        @endauth
                    </div>
                    <div class="flex items-center space-x-4">
                        @auth
                            @if ($isSuperuser)
                                <form method="GET" action="{{ url('/' . $currentDptid . '/employees') }}" class="flex items-center">
                                    <select name="dpt_switch" onchange="this.form.action='/'+this.value+'/employees'; this.form.submit()"
                                            class="text-sm border-gray-300 rounded">
                                        @foreach (Department::all() as $dept)
                                            <option value="{{ $dept->id }}" {{ $dept->id == $currentDptid ? 'selected' : '' }}>
                                                {{ $dept->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            @endif
                            <a href="{{ route('notifications.index') }}" class="relative text-sm text-gray-600 hover:text-gray-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                @if ($unreadCount > 0)
                                    <span class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">{{ min($unreadCount, 9) }}</span>
                                @endif
                            </a>
                            <a href="{{ route('profile.edit') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ $user->name }}</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-sm text-gray-600 hover:text-gray-900">Register</a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <main>
            {{ $slot }}
        </main>
    </div>
</body>
</html>
