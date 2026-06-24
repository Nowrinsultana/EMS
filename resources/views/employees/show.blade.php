<x-layout>
    <div class="max-w-5xl mx-auto mt-10 px-4 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('employees.index', ['dptid' => $dptid]) }}" class="text-sm text-indigo-600 hover:text-indigo-800">&larr; Back to Employees</a>
                <h1 class="text-2xl font-bold mt-1">{{ $employee->name }}</h1>
                <p class="text-sm text-gray-500">{{ $employee->staff_id ?? 'No Staff ID' }}</p>
            </div>
            <a href="{{ route('employees.edit', ['dptid' => $dptid, 'employee' => $employee]) }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">Edit Info</a>
        </div>

        @if (session('status'))
            <p class="text-green-600 text-sm">{{ session('status') }}</p>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Personal Information</h2>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-gray-500">Email</dt>
                    <dd class="font-medium">{{ $employee->email }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Phone</dt>
                    <dd class="font-medium">{{ $employee->phone_number ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Date of Birth</dt>
                    <dd class="font-medium">{{ $employee->date_of_birth?->format('Y-m-d') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Passport Number</dt>
                    <dd class="font-medium">{{ $employee->passport_number ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Start Date</dt>
                    <dd class="font-medium">{{ $employee->start_date?->format('Y-m-d') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Leave Balance</dt>
                    <dd class="font-medium">{{ $employee->leave_balance ?? 0 }} days</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Status</dt>
                    <dd class="font-medium">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $employee->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $employee->status ? 'Active' : 'Inactive' }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">Documents</h2>
                <button onclick="document.getElementById('upload-form').classList.toggle('hidden')" class="px-3 py-1.5 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">Upload Document</button>
            </div>

            <form id="upload-form" method="POST" action="{{ route('employees.documents.upload', ['dptid' => $dptid, 'employee' => $employee]) }}" enctype="multipart/form-data" class="hidden mb-6 p-4 bg-gray-50 rounded border">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Document Name *</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required
                               class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                        <input id="type" type="text" name="type" value="{{ old('type') }}" placeholder="e.g. Contract, ID"
                               class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @error('type')
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
                <div class="mt-3 flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">Upload</button>
                </div>
            </form>

            @if ($documents->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-3 font-medium">Name</th>
                                <th class="px-4 py-3 font-medium">Type</th>
                                <th class="px-4 py-3 font-medium">Uploaded</th>
                                <th class="px-4 py-3 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach ($documents as $doc)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">{{ $doc->name }}</td>
                                    <td class="px-4 py-3">{{ $doc->type ?? '—' }}</td>
                                    <td class="px-4 py-3">{{ $doc->created_at->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3 flex space-x-2">
                                        <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-sm">View</a>
                                        <form method="POST" action="{{ route('employees.documents.destroy', ['dptid' => $dptid, 'employee' => $employee, 'document' => $doc]) }}" onsubmit="return confirm('Delete this document?')">
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
                <p class="text-sm text-gray-500">No documents uploaded yet.</p>
            @endif
        </div>
    </div>
</x-layout>
