<x-layout>
    <div class="max-w-7xl mx-auto mt-10 px-4">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Departments</h1>
                <a href="{{ route('settings.departments.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">Add Department</a>
            </div>

            @if (session('status'))
                <p class="mb-4 text-green-600 text-sm">{{ session('status') }}</p>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3 font-medium">Name</th>
                            <th class="px-4 py-3 font-medium">Head</th>
                            <th class="px-4 py-3 font-medium">Created By</th>
                            <th class="px-4 py-3 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($departments as $dept)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $dept->name }}</td>
                                <td class="px-4 py-3">{{ $dept->head?->name ?? '—' }}</td>
                                <td class="px-4 py-3">{{ $dept->admin?->name }}</td>
                                <td class="px-4 py-3 flex space-x-2">
                                    <a href="{{ route('settings.departments.edit', $dept) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    <form method="POST" action="{{ route('settings.departments.destroy', $dept) }}" onsubmit="return confirm('Delete this department?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-500">No departments yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>
