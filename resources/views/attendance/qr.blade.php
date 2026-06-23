<x-layout>
    <div class="max-w-2xl mx-auto mt-10 px-4">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Daily QR Code</h1>
                <a href="{{ route('attendance.index', ['dptid' => $dptid]) }}" class="text-sm text-indigo-600 hover:text-indigo-900">Back</a>
            </div>

            @if (session('status'))
                <p class="mb-4 text-green-600 text-sm">{{ session('status') }}</p>
            @endif

            <p class="text-sm text-gray-500 mb-6">Date: <strong>{{ now()->format('Y-m-d') }}</strong></p>

            <div class="flex flex-col items-center space-y-6">
                <div class="bg-white border-2 border-gray-200 rounded-lg p-4">
                    <h2 class="text-sm font-medium text-gray-700 text-center mb-3">Check In</h2>
                    <div id="qr-checkin" class="w-64 h-64 flex items-center justify-center"></div>
                    <p class="text-xs text-gray-400 text-center mt-3">Scan to check in</p>
                </div>

                @if ($checkOutUrl)
                    <div class="bg-white border-2 border-gray-200 rounded-lg p-4">
                        <h2 class="text-sm font-medium text-gray-700 text-center mb-3">Check Out</h2>
                        <div id="qr-checkout" class="w-64 h-64 flex items-center justify-center"></div>
                        <p class="text-xs text-gray-400 text-center mt-3">Scan to check out</p>
                    </div>
                @else
                    <form method="POST" action="{{ route('attendance.qr.checkout', ['dptid' => $dptid]) }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">Generate Check-Out QR</button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
        new QRCode(document.getElementById('qr-checkin'), {
            text: '{{ $checkInUrl }}',
            width: 256,
            height: 256,
        });
        @if ($checkOutUrl)
            new QRCode(document.getElementById('qr-checkout'), {
                text: '{{ $checkOutUrl }}',
                width: 256,
                height: 256,
            });
        @endif
    </script>
</x-layout>
