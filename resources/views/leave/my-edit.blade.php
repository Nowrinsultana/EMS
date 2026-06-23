<x-layout>
    <div class="max-w-md mx-auto mt-10 bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-6">Edit Leave Request</h1>

        <form method="POST" action="{{ route('leave.my.update', ['dptid' => $dptid, 'leave' => $leave]) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input id="start_date" type="date" name="start_date" value="{{ old('start_date', $leave->start_date->format('Y-m-d')) }}" required
                       class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('start_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                <input id="end_date" type="date" name="end_date" value="{{ old('end_date', $leave->end_date->format('Y-m-d')) }}" required
                       class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('end_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full py-2 px-4 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                Update Request
            </button>
        </form>
    </div>
</x-layout>
