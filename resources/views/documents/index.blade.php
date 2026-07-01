<x-layout>
    <div class="max-w-6xl mx-auto mt-10 px-4 space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Documents</h1>
            <button onclick="document.getElementById('upload-form').classList.toggle('hidden')" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">Upload Document</button>
        </div>

        @if (session('status'))
            <p class="text-green-600 text-sm">{{ session('status') }}</p>
        @endif

        <form id="upload-form" method="POST" action="{{ route('documents.upload', ['dptid' => $dptid]) }}" enctype="multipart/form-data" class="hidden p-6 bg-white rounded-lg shadow border">
            @csrf
            <h2 class="text-lg font-semibold mb-4">Upload a Document</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="employee_id" class="block text-sm font-medium text-gray-700">Employee *</label>
                    <select id="employee_id" name="employee_id" required
                            class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">Select employee...</option>
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->name }} ({{ $emp->staff_id ?? 'No ID' }})
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Document Name *</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required
                           class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700">File *</label>
                    <input id="file" type="file" name="file" required
                           class="mt-1 block w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    @error('file')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="mt-4 flex justify-end">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">Upload</button>
            </div>
        </form>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if ($documents->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-3 font-medium">Employee</th>
                                <th class="px-4 py-3 font-medium">Name</th>
                                <th class="px-4 py-3 font-medium">Type</th>
                                <th class="px-4 py-3 font-medium">Uploaded</th>
                                <th class="px-4 py-3 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach ($documents as $doc)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">{{ $doc->user->name }}</td>
                                    <td class="px-4 py-3 font-medium">{{ $doc->name }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $doc->mime_type ?? '—' }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $doc->created_at->format('d M Y') }}</td>
                                    <td class="px-4 py-3 flex space-x-2">
                                        <a href="{{ route('documents.download', ['dptid' => $dptid, 'document' => $doc]) }}"
                                           class="text-indigo-600 hover:text-indigo-900 text-sm">Download</a>
                                        <form method="POST" action="{{ route('documents.destroy', ['dptid' => $dptid, 'document' => $doc]) }}"
                                              onsubmit="return confirm('Delete this document?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-sm text-center py-8">No documents found.</p>
            @endif
        </div>
    </div>
</x-layout>
