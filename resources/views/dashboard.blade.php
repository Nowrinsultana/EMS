<x-layout>
    <div class="max-w-7xl mx-auto mt-10 px-4">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
            <p class="text-gray-600 mb-6">Welcome, {{ Auth::user()->name }}!</p>

            <div class="flex space-x-4">
                <a href="{{ route('profile.edit') }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                    Edit Staff
                </a>
                <a href="{{ route('password.edit') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                    Change Password
                </a>
            </div>
        </div>
    </div>
</x-layout>
