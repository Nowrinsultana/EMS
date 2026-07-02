@php
    use App\Models\Department;
    use App\Models\Notification as NotificationModel;
    $user = Auth::user();
    $isSuperuser = $user?->superuser;
    $isDeptAdmin = $user?->isadmin;
    $routeDptid = request()->route('dptid');
    $currentDptid = $routeDptid ?? ($isSuperuser ? Department::value('id') : $user?->department_id);
    $routeName = request()->route()?->getName();
    $onPersonalPage = $routeName && (str_starts_with($routeName, 'leave.my') || $routeName === 'attendance.my' || str_starts_with($routeName, 'panel.') || $routeName === 'documents.my');
    $isActive = fn ($patterns) => collect((array) $patterns)->contains(fn ($p) => str_starts_with($routeName ?? '', $p));
    $unreadCount = $user ? NotificationModel::forUser($user)->unread()->count() : 0;
    $recentNotifications = $user ? NotificationModel::forUser($user)->latest()->take(5)->get() : collect();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'EMS') }}</title>
    @php
        $manifestPath = public_path('build/manifest.json');
        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);
            $base = '/build/';
            $done = [];
            foreach ($manifest as $key => $entry) {
                if (str_ends_with($key, '.css') && !in_array($entry['file'], $done)) {
                    $done[] = $entry['file'];
                    echo '<link rel="stylesheet" href="' . $base . $entry['file'] . '">';
                }
            }
            $entry = $manifest['resources/js/app.js'] ?? null;
            if ($entry) {
                echo '<script type="module" src="' . $base . $entry['file'] . '"></script>';
            }
        }
    @endphp
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50">
        <nav class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-xs">
            <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6">
                <div class="flex items-center justify-between h-12 lg:h-14">

                    <div class="flex items-center gap-1 sm:gap-2 lg:gap-4">
                        <a href="{{ url('/') }}" class="flex items-center gap-1 sm:gap-1.5 shrink-0">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6" viewBox="0 0 32 32" aria-hidden="true">
                                <rect x="0" y="0" width="32" height="32" rx="8" fill="#121826"/>
                                <path d="M5 9 L16 16 L5 23 Z" fill="#C8893D"/>
                                <path d="M27 9 L16 16 L27 23 Z" fill="#FFFFFF"/>
                                <line x1="16" y1="6" x2="16" y2="26" stroke="#C8893D" stroke-width="1" stroke-opacity="0.5"/>
                            </svg>
                            <span class="text-xs sm:text-sm lg:text-base font-bold text-gray-900 tracking-tight">PALINDROME</span>
                        </a>

                        @auth
                            <div class="hidden lg:flex items-center gap-1">
                                @php
                                    $navItems = [];
                                    $allItems = [
                                        ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'dashboard'],
                                    ];
                                    if ($isSuperuser || ($isDeptAdmin && $currentDptid)) {
                                    if ($onPersonalPage) {
                                        $allItems = array_merge($allItems, [
                                            ['route' => 'panel.index', 'label' => 'My Panel', 'icon' => 'panel'],
                                            ['route' => 'documents.my', 'label' => 'My Documents', 'icon' => 'documents'],
                                            ['route' => 'leave.my', 'label' => 'My Leave', 'icon' => 'leave'],
                                            ['route' => 'attendance.my', 'label' => 'My Attendance', 'icon' => 'attendance'],
                                        ]);
                                        if ($isSuperuser) {
                                            $allItems[] = ['route' => 'settings.index', 'label' => 'Settings', 'icon' => 'settings'];
                                        }
                                    } else {
                                            $allItems = array_merge($allItems, [
                                                ['route' => 'employees', 'label' => 'Employees', 'icon' => 'employees'],
                                                ['route' => 'documents', 'label' => 'Documents', 'icon' => 'documents'],
                                                ['route' => 'leave', 'label' => 'Leave', 'icon' => 'leave'],
                                                ['route' => 'attendance', 'label' => 'Attendance', 'icon' => 'attendance'],
                                                ['route' => 'payroll', 'label' => 'Payroll', 'icon' => 'payroll'],
                                                ['route' => 'recruitment', 'label' => 'Recruitment', 'icon' => 'recruitment'],
                                            ]);
                                            if ($isSuperuser) {
                                                $allItems[] = ['route' => 'settings.index', 'label' => 'Settings', 'icon' => 'settings'];
                                            }
                                        }
                                        $navItems = $allItems;
                                    } elseif ($currentDptid) {
                                        $navItems = [
                                            ['route' => 'panel.index', 'label' => 'My Panel', 'icon' => 'panel'],
                                            ['route' => 'documents.my', 'label' => 'My Documents', 'icon' => 'documents'],
                                            ['route' => 'leave.my', 'label' => 'My Leave', 'icon' => 'leave'],
                                            ['route' => 'attendance.my', 'label' => 'My Attendance', 'icon' => 'attendance'],
                                        ];
                                    }
                                @endphp

                                @foreach ($navItems as $item)
                                    @php
                                        $href = match (true) {
                                            $item['route'] === 'dashboard' => route('dashboard'),
                                            $item['route'] === 'settings.index' => route('settings.index'),
                                            $item['route'] === 'employees' => route('employees.index', ['dptid' => $currentDptid]),
                                            $item['route'] === 'documents' => route('documents.index', ['dptid' => $currentDptid]),
                                            $item['route'] === 'leave' => route('leave.index', ['dptid' => $currentDptid]),
                                            $item['route'] === 'attendance' => route('attendance.index', ['dptid' => $currentDptid]),
                                            $item['route'] === 'payroll' => route('payroll.index', ['dptid' => $currentDptid]),
                                            $item['route'] === 'recruitment' => route('recruitment.index', ['dptid' => $currentDptid]),
                                            $item['route'] === 'panel.index' => route('panel.index', ['dptid' => $currentDptid]),
                                            $item['route'] === 'documents.my' => route('documents.my', ['dptid' => $currentDptid]),
                                            $item['route'] === 'leave.my' => route('leave.my', ['dptid' => $currentDptid]),
                                            $item['route'] === 'attendance.my' => route('attendance.my', ['dptid' => $currentDptid]),
                                            default => '#',
                                        };
                                        $active = $isActive($item['route']);
                                    @endphp
                                    <a href="{{ $href }}"
                                       class="flex items-center gap-1 px-2 py-1.5 text-xs font-medium rounded-lg transition-colors duration-150 {{ $active ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                                        <span class="shrink-0">{!! svg_icon($item['icon'], $active ? '#4338CA' : '#6B7280') !!}</span>
                                        {{ $item['label'] }}
                                    </a>
                                @endforeach

                                @if ($onPersonalPage && ($isSuperuser || $isDeptAdmin))
                                    <span class="mx-0.5 w-px h-4 bg-gray-200"></span>
                                    <a href="{{ route('employees.index', ['dptid' => $currentDptid]) }}"
                                       class="flex items-center gap-1 px-2 py-1.5 text-xs font-medium text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors duration-150">
                                        &larr; Admin Panel
                                    </a>
                                @elseif (!$onPersonalPage && ($isSuperuser || $isDeptAdmin))
                                    <span class="mx-0.5 w-px h-4 bg-gray-200"></span>
                                    <a href="{{ route('panel.index', ['dptid' => $currentDptid]) }}"
                                       class="flex items-center gap-1 px-2 py-1.5 text-xs font-medium text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors duration-150">
                                        My Panel &rarr;
                                    </a>
                                @endif
                            </div>
                        @endauth
                    </div>

                    <div class="flex items-center gap-1 sm:gap-2">
                        @auth
                            @if ($isSuperuser)
                                <form method="GET" action="{{ url('/' . $currentDptid . '/employees') }}" class="hidden sm:block">
                                    <select name="dpt_switch" onchange="this.form.action='/'+this.value+'/employees'; this.form.submit()"
                                            class="text-xs border border-gray-300 rounded-lg py-1 pl-1.5 pr-5 bg-white text-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer">
                                        @foreach (Department::all() as $dept)
                                            <option value="{{ $dept->id }}" {{ $dept->id == $currentDptid ? 'selected' : '' }}>
                                                {{ $dept->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            @endif

                            <div class="relative" id="user-dropdown">
                                <button id="user-dropdown-btn"
                                        class="flex items-center gap-1.5 px-2 py-1 text-xs font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-150">
                                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    <span class="hidden sm:inline">{{ $user->name }}</span>
                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div id="user-dropdown-menu"
                                     class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 py-1 z-50">
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        Profile
                                    </a>
                                    <a href="{{ route('password.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        Change Password
                                    </a>
                                    <hr class="my-1 border-gray-100">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <button id="mobile-menu-btn" class="lg:hidden p-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg id="menu-icon-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                                <svg id="menu-icon-close" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                            <div class="relative" id="notif-dropdown">
                                <button id="notif-dropdown-btn"
                                        class="flex items-center justify-center text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors p-1.5">
                                    <span class="relative block" style="width:20px;height:20px;">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                        @if ($unreadCount > 0)
                                            <span class="absolute -top-1 -right-1 flex items-center justify-center w-[18px] h-[18px] text-xs font-bold text-white bg-red-500 rounded-full">{{ min($unreadCount, 99) }}</span>
                                        @endif
                                    </span>
                                </button>
                                <div id="notif-dropdown-menu"
                                     class="hidden absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-200 py-1 z-50 max-h-96 overflow-y-auto">
                                    <div class="px-4 py-2 border-b border-gray-100 flex items-center justify-between">
                                        <span class="text-xs font-semibold text-gray-700">Notifications</span>
                                        @if ($unreadCount > 0)
                                            <form method="POST" action="{{ route('notifications.read-all') }}">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="text-xs text-indigo-600 hover:text-indigo-900">Mark all read</button>
                                            </form>
                                        @endif
                                    </div>
                                    @forelse ($recentNotifications as $notif)
                                        <a href="{{ route('notifications.go', $notif) }}"
                                           class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition-colors {{ $notif->is_read ? '' : 'bg-indigo-50/50' }}">
                                            <div class="min-w-0 flex-1">
                                                @if ($notif->type)
                                                    <span class="text-xs font-medium text-gray-500 uppercase">{{ $notif->type }}</span>
                                                @endif
                                                <p class="text-sm {{ $notif->is_read ? 'text-gray-600' : 'text-gray-900 font-medium' }} truncate">
                                                    {{ $notif->message }}
                                                </p>
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $notif->created_at->diffForHumans() }}</p>
                                            </div>
                                            @unless ($notif->is_read)
                                                <span class="w-2 h-2 mt-1.5 rounded-full bg-indigo-500 shrink-0"></span>
                                            @endunless
                                        </a>
                                    @empty
                                        <p class="text-center text-gray-500 py-6 text-sm">No notifications yet.</p>
                                    @endforelse
                                    <a href="{{ route('notifications.index') }}"
                                       class="block text-center text-xs text-indigo-600 hover:text-indigo-900 py-2 border-t border-gray-100 font-medium">
                                        View all notifications
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('login') }}" class="px-2 py-1 text-xs font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">Login</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="px-2 py-1 text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors">Register</a>
                                @endif
                            </div>
                        @endauth
                    </div>
                </div>
            </div>

            @auth
                <div id="mobile-menu" class="hidden lg:hidden border-t border-gray-200 bg-white">
                    <div class="px-3 py-2 space-y-0.5">
                        @if ($isSuperuser)
                            <form method="GET" action="{{ url('/' . $currentDptid . '/employees') }}" class="pb-2">
                                <select name="dpt_switch_mobile" onchange="this.form.action='/'+this.value+'/employees'; this.form.submit()"
                                        class="w-full text-xs border border-gray-300 rounded-lg py-1.5 px-2 bg-white text-gray-700">
                                    @foreach (Department::all() as $dept)
                                        <option value="{{ $dept->id }}" {{ $dept->id == $currentDptid ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        @endif

                        @if ($isSuperuser || $isDeptAdmin)
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-2 py-2 text-sm font-medium rounded-lg {{ $isActive('dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                {!! svg_icon('dashboard', $isActive('dashboard') ? '#4338CA' : '#6B7280') !!}
                                Dashboard
                            </a>
                        @endif

                        @foreach ($navItems as $item)
                            @php
                                $href = match (true) {
                                    $item['route'] === 'settings.index' => route('settings.index'),
                                    $item['route'] === 'employees' => route('employees.index', ['dptid' => $currentDptid]),
                                    $item['route'] === 'documents' => route('documents.index', ['dptid' => $currentDptid]),
                                    $item['route'] === 'leave' => route('leave.index', ['dptid' => $currentDptid]),
                                    $item['route'] === 'attendance' => route('attendance.index', ['dptid' => $currentDptid]),
                                    $item['route'] === 'payroll' => route('payroll.index', ['dptid' => $currentDptid]),
                                    $item['route'] === 'recruitment' => route('recruitment.index', ['dptid' => $currentDptid]),
                                    $item['route'] === 'panel.index' => route('panel.index', ['dptid' => $currentDptid]),
                                    $item['route'] === 'documents.my' => route('documents.my', ['dptid' => $currentDptid]),
                                    $item['route'] === 'leave.my' => route('leave.my', ['dptid' => $currentDptid]),
                                    $item['route'] === 'attendance.my' => route('attendance.my', ['dptid' => $currentDptid]),
                                    default => '#',
                                };
                                $active = $isActive($item['route']);
                            @endphp
                            <a href="{{ $href }}"
                               class="flex items-center gap-2 px-2 py-2 text-sm font-medium rounded-lg {{ $active ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                <span class="shrink-0">{!! svg_icon($item['icon'], $active ? '#4338CA' : '#6B7280') !!}</span>
                                {{ $item['label'] }}
                            </a>
                        @endforeach

                        @if ($onPersonalPage && ($isSuperuser || $isDeptAdmin))
                            <a href="{{ route('employees.index', ['dptid' => $currentDptid]) }}"
                               class="flex items-center gap-2 px-2 py-2 text-xs font-medium text-indigo-600 hover:bg-indigo-50 rounded-lg">
                                &larr; Admin Panel
                            </a>
                        @elseif (!$onPersonalPage && ($isSuperuser || $isDeptAdmin))
                            <a href="{{ route('panel.index', ['dptid' => $currentDptid]) }}"
                               class="flex items-center gap-2 px-2 py-2 text-xs font-medium text-indigo-600 hover:bg-indigo-50 rounded-lg">
                                My Panel &rarr;
                            </a>
                        @endif
                    </div>
                </div>
            @endauth
        </nav>

        <main>
            {{ $slot }}
        </main>
    </div>

    @auth
        <script>
            (function() {
                const menuBtn = document.getElementById('mobile-menu-btn');
                const menu = document.getElementById('mobile-menu');
                const iconOpen = document.getElementById('menu-icon-open');
                const iconClose = document.getElementById('menu-icon-close');
                if (menuBtn && menu) {
                    menuBtn.addEventListener('click', function() {
                        const isOpen = !menu.classList.contains('hidden');
                        menu.classList.toggle('hidden', isOpen);
                        iconOpen.classList.toggle('hidden', !isOpen);
                        iconClose.classList.toggle('hidden', isOpen);
                    });
                }

                const dropdownBtn = document.getElementById('user-dropdown-btn');
                const dropdownMenu = document.getElementById('user-dropdown-menu');
                if (dropdownBtn && dropdownMenu) {
                    dropdownBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const isOpen = !dropdownMenu.classList.contains('hidden');
                        dropdownMenu.classList.toggle('hidden', isOpen);
                    });
                    document.addEventListener('click', function(e) {
                        if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                            dropdownMenu.classList.add('hidden');
                        }
                    });
                }

                const notifBtn = document.getElementById('notif-dropdown-btn');
                const notifMenu = document.getElementById('notif-dropdown-menu');
                if (notifBtn && notifMenu) {
                    notifBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        notifMenu.classList.toggle('hidden');
                    });
                    document.addEventListener('click', function(e) {
                        if (!notifBtn.contains(e.target) && !notifMenu.contains(e.target)) {
                            notifMenu.classList.add('hidden');
                        }
                    });
                }
            })();
        </script>
    @endauth
</body>
</html>

@php
    function svg_icon(string $name, string $color = '#6B7280'): string {
        $icons = [
            'documents' => '<svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="'.$color.'" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>',
            'panel' => '<svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="'.$color.'" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>',
            'dashboard' => '<svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="'.$color.'" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>',
            'employees' => '<svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="'.$color.'" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>',
            'leave' => '<svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="'.$color.'" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/></svg>',
            'attendance' => '<svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="'.$color.'" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
            'payroll' => '<svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="'.$color.'" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>',
            'recruitment' => '<svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="'.$color.'" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/></svg>',
            'settings' => '<svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="'.$color.'" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06A1.65 1.65 0 0019.32 9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z"/></svg>',
        ];
        return $icons[$name] ?? '';
    }
@endphp
