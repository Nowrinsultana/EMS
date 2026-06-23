<x-layout>
    <div class="max-w-md mx-auto mt-10 bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-6">Edit Department</h1>

        <form method="POST" action="{{ route('settings.departments.update', $department) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Department Name</label>
                <input id="name" type="text" name="name" value="{{ old('name', $department->name) }}" required
                       class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="department_head_id" class="block text-sm font-medium text-gray-700">Department Head</label>
                <select id="department_head_id" name="department_head_id"
                        class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">— Select Head —</option>
                    @foreach ($employees as $emp)
                        <option value="{{ $emp->id }}" {{ old('department_head_id', $department->department_head_id) == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                    @endforeach
                </select>
                @error('department_head_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white rounded hover:bg-indigo-700">Update Department</button>
        </form>
    </div>
</x-layout>
