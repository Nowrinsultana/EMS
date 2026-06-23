@php
    use App\Models\Department;
    $user = Auth::user();
    $isSuperuser = $user?->superuser;
    $isDeptAdmin = $user?->isadmin;
    $routeDptid = request()->route('dptid');
    $currentDptid = $routeDptid ?? ($isSuperuser ? Department::value('id') : $user?->department_id);
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
                        <a href="{{ url('/') }}" class="text-xl font-bold text-gray-800 shrink-0">{{ config('app.name', 'EMS') }}</a>
                        @auth
                            <div class="hidden sm:flex items-center space-x-4">
                                <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">Dashboard</a>

                                @if ($isSuperuser)
                                    <a href="{{ route('employees.index', ['dptid' => $currentDptid]) }}" class="text-sm text-gray-600 hover:text-gray-900">Employees</a>
                                    <a href="{{ route('leave.index', ['dptid' => $currentDptid]) }}" class="text-sm text-gray-600 hover:text-gray-900">Leave</a>
                                    <a href="{{ route('attendance.index', ['dptid' => $currentDptid]) }}" class="text-sm text-gray-600 hover:text-gray-900">Attendance</a>
                                    <a href="{{ route('recruitment.index', ['dptid' => $currentDptid]) }}" class="text-sm text-gray-600 hover:text-gray-900">Recruitment</a>
                                    <a href="{{ route('settings.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Settings</a>
                                @elseif ($isDeptAdmin && $currentDptid)
                                    <a href="{{ route('employees.index', ['dptid' => $currentDptid]) }}" class="text-sm text-gray-600 hover:text-gray-900">Employees</a>
                                    <a href="{{ route('leave.index', ['dptid' => $currentDptid]) }}" class="text-sm text-gray-600 hover:text-gray-900">Leave</a>
                                    <a href="{{ route('attendance.index', ['dptid' => $currentDptid]) }}" class="text-sm text-gray-600 hover:text-gray-900">Attendance</a>
                                    <a href="{{ route('recruitment.index', ['dptid' => $currentDptid]) }}" class="text-sm text-gray-600 hover:text-gray-900">Recruitment</a>
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
