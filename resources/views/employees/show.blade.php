<x-layout>
    <div class="max-w-5xl mx-auto mt-10 px-4 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('employees.index', ['dptid' => $dptid]) }}" class="text-sm text-indigo-600 hover:text-indigo-800">&larr; Back to Employees</a>
                <h1 class="text-2xl font-bold mt-1">{{ $employee->name }}</h1>
                <p class="text-sm text-gray-500">{{ $employee->staff_id ?? 'No Staff ID' }}</p>
            </div>
            <a href="{{ route('employees.edit', ['dptid' => $dptid, 'employee' => $employee]) }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">Edit Info</a>
        </div>

        @if (session('status'))
            <p class="text-green-600 text-sm">{{ session('status') }}</p>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Personal Information</h2>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-gray-500">Email</dt>
                    <dd class="font-medium">{{ $employee->email }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Phone</dt>
                    <dd class="font-medium">{{ $employee->phone_number ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Date of Birth</dt>
                    <dd class="font-medium">{{ $employee->date_of_birth?->format('Y-m-d') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Passport Number</dt>
                    <dd class="font-medium">{{ $employee->passport_number ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Start Date</dt>
                    <dd class="font-medium">{{ $employee->start_date?->format('Y-m-d') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Leave Balance</dt>
                    <dd class="font-medium">{{ $employee->leave_balance ?? 0 }} days</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Status</dt>
                    <dd class="font-medium">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $employee->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $employee->status ? 'Active' : 'Inactive' }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</x-layout>
