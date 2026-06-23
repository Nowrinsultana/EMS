<x-layout>
    <div class="max-w-7xl mx-auto mt-10 px-4">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Attendance Management</h1>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('attendance.summary', ['dptid' => $dptid]) }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">Monthly Summary</a>
                    <a href="{{ route('attendance.qr', ['dptid' => $dptid]) }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 text-sm">QR Code</a>
                </div>
            </div>

            @if (session('status'))
                <p class="mb-4 text-green-600 text-sm">{{ session('status') }}</p>
            @endif

            <form method="GET" class="mb-6 flex items-center space-x-3">
                <label class="text-sm text-gray-600">Date:</label>
                <input type="date" name="date" value="{{ $date }}" class="rounded border-gray-300 text-sm"
                       onchange="this.form.submit()">
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3 font-medium">Staff</th>
                            <th class="px-4 py-3 font-medium">Check In</th>
                            <th class="px-4 py-3 font-medium">Check Out</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            <th class="px-4 py-3 font-medium">QR</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($attendances as $att)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $att->user?->name ?? '—' }}</td>
                                <td class="px-4 py-3">{{ $att->check_in?->format('H:i') ?? '—' }}</td>
                                <td class="px-4 py-3">{{ $att->check_out?->format('H:i') ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                        {{ $att->status === 'present' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $att->status === 'late' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $att->status === 'absent' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($att->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-400">
                                    @if ($att->qr_check_in) CI@if ($att->qr_check_out) / CO @endif
                                    @else —
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-center text-gray-400 text-sm">No records for this date.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($employees->isNotEmpty())
                <hr class="my-8">
                <h2 class="text-lg font-bold mb-4">Mark Attendance</h2>
                <form method="POST" action="{{ route('attendance.mark', ['dptid' => $dptid]) }}" class="grid grid-cols-5 gap-3 items-end">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Staff</label>
                        <select name="user_id" required class="w-full rounded border-gray-300 text-sm">
                            <option value="">Select...</option>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" name="date" value="{{ $date }}" required class="w-full rounded border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Check In</label>
                        <input type="time" name="check_in" class="w-full rounded border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Check Out</label>
                        <input type="time" name="check_out" class="w-full rounded border-gray-300 text-sm">
                    </div>
                    <div class="flex items-center space-x-2">
                        <select name="status" required class="rounded border-gray-300 text-sm">
                            <option value="present">Present</option>
                            <option value="late">Late</option>
                            <option value="absent">Absent</option>
                        </select>
                        <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">Mark</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</x-layout>
