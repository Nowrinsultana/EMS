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
                                @if (Auth::user()->isadmin)
                                    <a href="{{ route('employees.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Employees</a>
                                    <a href="{{ route('leave.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Leave</a>
                                    <a href="{{ route('attendance.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Attendance</a>
                                    <a href="{{ route('recruitment.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Recruitment</a>
                                    <a href="{{ route('settings.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Settings</a>
                                @else
                                    <a href="{{ route('leave.my') }}" class="text-sm text-gray-600 hover:text-gray-900">My Leave</a>
                                    <a href="{{ route('attendance.my') }}" class="text-sm text-gray-600 hover:text-gray-900">My Attendance</a>
                                @endif
                            </div>
                        @endauth
                    </div>
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ route('profile.edit') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ Auth::user()->name }}</a>
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
