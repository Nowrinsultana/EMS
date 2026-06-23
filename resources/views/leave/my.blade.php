<x-layout>
    <div class="max-w-7xl mx-auto mt-10 px-4">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">My Leave</h1>
                <a href="{{ route('leave.my.create', ['dptid' => request()->route('dptid')]) }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">Request Leave</a>
            </div>

            @if (session('status'))
                <p class="mb-4 text-green-600 text-sm">{{ session('status') }}</p>
            @endif

            <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-3 font-medium">Start Date</th>
                                <th class="px-4 py-3 font-medium">End Date</th>
                                <th class="px-4 py-3 font-medium">Days</th>
                                <th class="px-4 py-3 font-medium">Reason</th>
                                <th class="px-4 py-3 font-medium">Status</th>
                                <th class="px-4 py-3 font-medium"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse ($leaves as $leave)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">{{ $leave->start_date->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3">{{ $leave->end_date->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3">{{ $leave->start_date->diffInDays($leave->end_date) + 1 }}</td>
                                    <td class="px-4 py-3 max-w-xs truncate">{{ $leave->reason ?? '—' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                            {{ $leave->status->value === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $leave->status->value === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $leave->status->value === 'declined' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst($leave->status->value) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($leave->status->value === 'pending')
                                            <a href="{{ route('leave.my.edit', ['dptid' => request()->route('dptid'), 'leave' => $leave]) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">No leave requests yet.</td>
                                </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>
