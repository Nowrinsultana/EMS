<x-layout>
    <div class="max-w-4xl mx-auto mt-10 px-4">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold mb-2">Open Positions</h1>
            <p class="text-gray-500 text-sm mb-6">Join our team — browse current vacancies below.</p>

            @if ($vacancies->isEmpty())
                <p class="text-gray-400 text-center py-10">No open positions right now. Check back later.</p>
            @else
                <div class="space-y-4">
                    @foreach ($vacancies as $vacancy)
                        <div class="border rounded-lg p-5 hover:border-indigo-300 transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h2 class="text-lg font-semibold">{{ $vacancy->title }}</h2>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $vacancy->department->name }}
                                        @if ($vacancy->location) · {{ $vacancy->location }} @endif
                                        · {{ $vacancy->employment_type }}
                                    </p>
                                </div>
                                <a href="{{ route('jobs.apply', ['vacancy' => $vacancy]) }}"
                                   class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm font-medium shrink-0">Apply</a>
                            </div>
                            @if ($vacancy->description)
                                <p class="text-sm text-gray-600 mt-3 line-clamp-3">{{ Str::limit($vacancy->description, 300) }}</p>
                            @endif
                            @if ($vacancy->closing_date)
                                <p class="text-xs text-gray-400 mt-2">Closes {{ $vacancy->closing_date->format('M j, Y') }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layout>
