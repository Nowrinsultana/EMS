<x-layout>
    <div class="max-w-4xl mx-auto mt-10 px-4 space-y-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold mb-6">My Panel</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-lg font-semibold mb-4 text-gray-700">Personal Information</h2>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm text-gray-500">Name</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Email</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $user->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Staff ID</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $user->staff_id ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Department</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $user->department?->name ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Phone Number</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $user->phone_number ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Date of Birth</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $user->date_of_birth?->format('d M Y') ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Passport Number</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $user->passport_number ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Start Date</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $user->start_date?->format('d M Y') ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Leave Balance</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $user->leave_balance ?? 0 }} days</dd>
                        </div>
                    </dl>
                    <div class="mt-4">
                        <a href="{{ route('profile.edit') }}"
                           class="inline-flex items-center px-3 py-1.5 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            Edit Profile
                        </a>
                    </div>
                </div>

                <div>
                    <h2 class="text-lg font-semibold mb-4 text-gray-700">Quick Links</h2>
                    <div class="space-y-3">
                        <a href="{{ route('leave.my', ['dptid' => request()->route('dptid')]) }}"
                           class="block px-4 py-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                            <span class="font-medium text-blue-700">My Leave</span>
                            <p class="text-sm text-blue-500 mt-0.5">View and manage your leave requests</p>
                        </a>
                        <a href="{{ route('attendance.my', ['dptid' => request()->route('dptid')]) }}"
                           class="block px-4 py-3 bg-green-50 rounded-lg hover:bg-green-100 transition">
                            <span class="font-medium text-green-700">My Attendance</span>
                            <p class="text-sm text-green-500 mt-0.5">Check in/out and view attendance records</p>
                        </a>
                        <a href="{{ route('documents.my', ['dptid' => request()->route('dptid')]) }}"
                           class="block px-4 py-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                            <span class="font-medium text-purple-700">My Documents</span>
                            <p class="text-sm text-purple-500 mt-0.5">Upload and manage your documents</p>
                        </a>
                        <a href="{{ route('password.edit') }}"
                           class="block px-4 py-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <span class="font-medium text-gray-700">Change Password</span>
                            <p class="text-sm text-gray-500 mt-0.5">Update your account password</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                {{ session('status') }}
            </div>
        @endif
    </div>
</x-layout>
