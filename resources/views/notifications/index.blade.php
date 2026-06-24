<x-layout>
    <div class="max-w-4xl mx-auto mt-10 px-4">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Notifications</h1>
                @if ($notifications->total() > 0)
                    <form method="POST" action="{{ route('notifications.read-all') }}">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-900">Mark all as read</button>
                    </form>
                @endif
            </div>

            @if (session('status'))
                <p class="mb-4 text-green-600 text-sm">{{ session('status') }}</p>
            @endif

            <div class="space-y-3">
                @forelse ($notifications as $notification)
                    <div class="flex items-start justify-between p-4 border rounded-lg {{ $notification->is_read ? 'bg-gray-50' : 'bg-indigo-50 border-indigo-200' }}">
                        <div class="flex-1">
                            @if ($notification->type)
                                <span class="text-xs font-medium text-gray-500 uppercase">{{ $notification->type }}</span>
                            @endif
                            <p class="text-sm {{ $notification->is_read ? 'text-gray-600' : 'text-gray-900 font-medium' }}">
                                {{ $notification->message }}
                            </p>
                            <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        @unless ($notification->is_read)
                            <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="text-xs text-indigo-600 hover:text-indigo-900 ml-4 shrink-0">Mark read</button>
                            </form>
                        @endunless
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-8">No notifications yet.</p>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</x-layout>
