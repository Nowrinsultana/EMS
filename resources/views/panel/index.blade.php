<x-layout>
    <div class="max-w-4xl mx-auto mt-10 px-4 space-y-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold mb-6">My Panel</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-lg font-semibold mb-4 text-gray-700">Personal Information</h2>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm text-gray-500">Name</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Email</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $user->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Staff ID</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $user->staff_id ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Department</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $user->department?->name ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Phone Number</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $user->phone_number ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Date of Birth</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $user->date_of_birth?->format('d M Y') ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Passport Number</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $user->passport_number ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Start Date</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $user->start_date?->format('d M Y') ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Leave Balance</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $user->leave_balance ?? 0 }} days</dd>
                        </div>
                    </dl>
                    <div class="mt-4">
                        <a href="{{ route('profile.edit') }}"
                           class="inline-flex items-center px-3 py-1.5 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            Edit Profile
                        </a>
                    </div>
                </div>

                <div>
                    <h2 class="text-lg font-semibold mb-4 text-gray-700">Quick Links</h2>
                    <div class="space-y-3">
                        <a href="{{ route('leave.my', ['dptid' => request()->route('dptid')]) }}"
                           class="block px-4 py-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                            <span class="font-medium text-blue-700">My Leave</span>
                            <p class="text-sm text-blue-500 mt-0.5">View and manage your leave requests</p>
                        </a>
                        <a href="{{ route('attendance.my', ['dptid' => request()->route('dptid')]) }}"
                           class="block px-4 py-3 bg-green-50 rounded-lg hover:bg-green-100 transition">
                            <span class="font-medium text-green-700">My Attendance</span>
                            <p class="text-sm text-green-500 mt-0.5">Check in/out and view attendance records</p>
                        </a>
                        <a href="{{ route('password.edit') }}"
                           class="block px-4 py-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <span class="font-medium text-gray-700">Change Password</span>
                            <p class="text-sm text-gray-500 mt-0.5">Update your account password</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-700">My Documents</h2>

            <form method="POST" action="{{ route('panel.upload', ['dptid' => request()->route('dptid')]) }}" enctype="multipart/form-data" class="mb-6 p-4 bg-gray-50 rounded-lg">
                @csrf
                <div class="flex items-end gap-4 flex-wrap">
                    <div class="flex-1 min-w-0">
                        <label for="document" class="block text-sm font-medium text-gray-700 mb-1">Upload Document</label>
                        <input id="document" type="file" name="document" required
                               class="block w-full text-sm text-gray-900 border border-gray-300 rounded cursor-pointer bg-white focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @error('document')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex-1 min-w-0">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Display Name (optional)</label>
                        <input id="name" type="text" name="name" placeholder="e.g. Contract, ID Copy"
                               class="block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    <button type="submit"
                            class="shrink-0 px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Upload
                    </button>
                </div>
            </form>

            @if ($user->documents->isEmpty())
                <p class="text-gray-500 text-sm">No documents uploaded yet.</p>
            @else
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Uploaded</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($user->documents as $doc)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $doc->name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $doc->mime_type }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500">
                                        @php
                                            $size = $doc->size;
                                            if ($size >= 1048576) {
                                                echo round($size / 1048576, 1) . ' MB';
                                            } elseif ($size >= 1024) {
                                                echo round($size / 1024, 1) . ' KB';
                                            } else {
                                                echo $size . ' B';
                                            }
                                        @endphp
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $doc->created_at->format('d M Y') }}</td>
                                    <td class="px-4 py-3 text-right text-sm space-x-2">
                                        <a href="{{ route('panel.download', ['dptid' => request()->route('dptid'), 'document' => $doc->id]) }}"
                                           class="text-indigo-600 hover:text-indigo-900">Download</a>
                                        <form method="POST" action="{{ route('panel.destroy', ['dptid' => request()->route('dptid'), 'document' => $doc->id]) }}"
                                              class="inline" onsubmit="return confirm('Delete this document?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                {{ session('status') }}
            </div>
        @endif
    </div>
</x-layout>
