<x-layout>
    <div class="max-w-7xl mx-auto mt-10 px-4">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Employees</h1>
                <a href="{{ route('employees.create', ['dptid' => $dptid]) }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">Add Employee</a>
            </div>

            @if (session('status'))
                <p class="mb-4 text-green-600 text-sm">{{ session('status') }}</p>
            @endif

            @if (session('error'))
                <p class="mb-4 text-red-600 text-sm">{{ session('error') }}</p>
            @endif

            @if (session('setup_url'))
                <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded">
                    <p class="text-sm text-blue-700 font-medium mb-1">Share this link with the employee to set their password:</p>
                    <div class="flex items-center space-x-2">
                        <input id="setup-url" type="text" value="{{ session('setup_url') }}" readonly
                               class="flex-1 text-sm border-blue-300 rounded bg-white px-3 py-2">
                        <button onclick="navigator.clipboard.writeText('{{ session('setup_url') }}').then(() => this.textContent='Copied!').catch(() => {})"
                                class="px-3 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">Copy</button>
                    </div>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3 font-medium">Name</th>
                            <th class="px-4 py-3 font-medium">Email</th>
                            <th class="px-4 py-3 font-medium">Staff ID</th>
                            <th class="px-4 py-3 font-medium">Phone</th>
                            <th class="px-4 py-3 font-medium">Start Date</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            <th class="px-4 py-3 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($employees as $emp)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $emp->name }}</td>
                                <td class="px-4 py-3">{{ $emp->email }}</td>
                                <td class="px-4 py-3">{{ $emp->staff_id ?? '—' }}</td>
                                <td class="px-4 py-3">{{ $emp->phone_number ?? '—' }}</td>
                                <td class="px-4 py-3">{{ $emp->start_date?->format('Y-m-d') ?? '—' }}</td>
                                <td class="px-4 py-3">{{ $emp->status ? 'Active' : 'Inactive' }}</td>
                                <td class="px-4 py-3 flex space-x-3">
                                    <a href="{{ route('employees.show', ['dptid' => $dptid, 'employee' => $emp]) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View</a>
                                    <a href="{{ route('employees.edit', ['dptid' => $dptid, 'employee' => $emp]) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</a>
                                    <form method="POST" action="{{ route('employees.destroy', ['dptid' => $dptid, 'employee' => $emp]) }}" onsubmit="return confirm('Delete this employee?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-gray-500">No employees in this department.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>
