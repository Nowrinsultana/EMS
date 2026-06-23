<x-layout>
    <div class="max-w-4xl mx-auto mt-10 px-4">
        <h1 class="text-2xl font-bold mb-6">Settings</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="{{ route('settings.departments.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition block">
                <h2 class="text-lg font-semibold text-gray-800">Departments</h2>
                <p class="text-sm text-gray-500 mt-1">Manage departments, assign heads and employees</p>
            </a>
        </div>
    </div>
</x-layout>
