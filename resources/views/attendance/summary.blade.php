<x-layout>
    <div class="max-w-7xl mx-auto mt-10 px-4">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Monthly Attendance Summary</h1>
                <a href="{{ route('attendance.index', ['dptid' => $dptid]) }}" class="text-sm text-indigo-600 hover:text-indigo-900">Back</a>
            </div>

            <form method="GET" class="mb-6 flex items-center space-x-3">
                <label class="text-sm text-gray-600">Month:</label>
                <input type="month" name="month" value="{{ $month }}" class="rounded border-gray-300 text-sm"
                       onchange="this.form.submit()">
            </form>

            <p class="text-sm text-gray-500 mb-4">{{ $startOfMonth->format('F Y') }}</p>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3 font-medium">Staff</th>
                            <th class="px-4 py-3 font-medium text-green-700">Present</th>
                            <th class="px-4 py-3 font-medium text-yellow-700">Late</th>
                            <th class="px-4 py-3 font-medium text-red-700">Absent</th>
                            <th class="px-4 py-3 font-medium">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($summary as $row)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $row['user']->name }}</td>
                                <td class="px-4 py-3 font-medium text-green-700">{{ $row['present'] }}</td>
                                <td class="px-4 py-3 font-medium text-yellow-700">{{ $row['late'] }}</td>
                                <td class="px-4 py-3 font-medium text-red-700">{{ $row['absent'] }}</td>
                                <td class="px-4 py-3">{{ $row['total'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">No data for this month.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>
