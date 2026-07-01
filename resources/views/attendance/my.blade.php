<x-layout>
    <div class="max-w-7xl mx-auto mt-10 px-4">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">My Attendance</h1>
                <div class="flex items-center space-x-3">
                    @if (!$todayRecord || !$todayRecord->check_in)
                        <form method="POST" action="{{ route('attendance.check-in', ['dptid' => request()->route('dptid')]) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm font-medium">Check In</button>
                        </form>
                    @elseif ($todayRecord->check_in && !$todayRecord->check_out)
                        <form method="POST" action="{{ route('attendance.check-out', ['dptid' => request()->route('dptid')]) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-700 text-sm font-medium">Check Out</button>
                        </form>
                    @endif
                </div>
            </div>

            @if (session('status'))
                <p class="mb-4 text-green-600 text-sm">{{ session('status') }}</p>
            @endif
            @if (session('error'))
                <p class="mb-4 text-red-600 text-sm">{{ session('error') }}</p>
            @endif

            @if ($todayRecord)
                <div class="bg-gray-50 rounded p-4 mb-6">
                    <h2 class="text-sm font-medium text-gray-700 mb-2">Today</h2>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Check In:</span>
                            <span class="ml-1 font-medium">{{ $todayRecord->check_in?->format('H:i') ?? '—' }}</span>
                            @if ($todayRecord->qr_check_in)
                                <span class="text-xs text-gray-400 ml-1">(QR)</span>
                            @endif
                        </div>
                        <div>
                            <span class="text-gray-500">Check Out:</span>
                            <span class="ml-1 font-medium">{{ $todayRecord->check_out?->format('H:i') ?? '—' }}</span>
                            @if ($todayRecord->qr_check_out)
                                <span class="text-xs text-gray-400 ml-1">(QR)</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if ($checkInUrl)
                <div class="mb-6">
                    <div class="bg-white border-2 border-gray-200 rounded-lg p-4 flex flex-col items-center mx-auto max-w-xs">
                        @if (!$todayRecord || !$todayRecord->check_in)
                            <h3 class="text-sm font-medium text-gray-700 mb-3">Scan to Check In</h3>
                        @elseif (!$todayRecord->check_out)
                            <h3 class="text-sm font-medium text-gray-700 mb-3">Scan to Check Out</h3>
                        @else
                            <h3 class="text-sm font-medium text-gray-700 mb-3">Today's QR Code</h3>
                        @endif
                        <div id="qr-code" class="w-48 h-48"></div>
                        <p class="text-xs text-gray-400 mt-3">
                            @if (!$todayRecord || !$todayRecord->check_in)
                                Open your phone camera and scan to check in
                            @elseif (!$todayRecord->check_out)
                                Open your phone camera and scan to check out
                            @else
                                You're all done for today
                            @endif
                        </p>
                    </div>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3 font-medium">Date</th>
                            <th class="px-4 py-3 font-medium">Check In</th>
                            <th class="px-4 py-3 font-medium">Check Out</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($attendances as $att)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $att->date->format('Y-m-d') }}</td>
                                <td class="px-4 py-3">
                                    {{ $att->check_in?->format('H:i') ?? '—' }}
                                    @if ($att->qr_check_in)
                                        <span class="text-xs text-gray-400 ml-1">(QR)</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    {{ $att->check_out?->format('H:i') ?? '—' }}
                                    @if ($att->qr_check_out)
                                        <span class="text-xs text-gray-400 ml-1">(QR)</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                        {{ $att->status === 'present' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $att->status === 'late' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $att->status === 'absent' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($att->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-500">No attendance records yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($checkInUrl)
        <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
        <script>
            new QRCode(document.getElementById('qr-code'), {
                text: '{{ $checkInUrl }}',
                width: 192,
                height: 192,
            });
        </script>
    @endif
</x-layout>
