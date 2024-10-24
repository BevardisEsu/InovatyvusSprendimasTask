@extends('layouts.app')

@section('title', 'Activity Logs')

@section('content')
<div class="bg-white shadow-sm rounded-lg max-w-6xl mx-auto">
    <div class="p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Activity Logs</h2>

        <!--Date Filter Form -->
        <form action="{{ route('activity-logs.show') }}" method="GET" class="mb-6">
            <div class="flex gap-4 items-end">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">From Date</label>
                    <input type="date" name="start_date" id="start_date" 
                           value="{{ request('start_date') }}"
                           class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-primary-DEFAULT focus:ring focus:ring-primary-light">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">To Date</label>
                    <input type="date" name="end_date" id="end_date" 
                           value="{{ request('end_date') }}"
                           class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-primary-DEFAULT focus:ring focus:ring-primary-light">
                </div>
                <button type="submit"  class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg transition duration-300 ease-in-out">
                    Filter Logs
                </button>
                @if(request('start_date') || request('end_date'))
                    <a href="{{ route('activity-logs.show') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded">
                        Clear Filter
                    </a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Changes</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($logs as $log)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ optional($log->causer)->name ?? 'System' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->event }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $log->description }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                @if($log->properties && count($log->properties) > 0)
                                    <button 
                                        onclick="toggleDetails('{{ $log->id }}')"
                                        class="text-primary-DEFAULT hover:text-primary-dark focus:outline-none"
                                    >
                                        <span id="button-text-{{ $log->id }}">View Changes</span>
                                    </button>
                                    <div id="details-{{ $log->id }}" class="hidden mt-2 bg-gray-50 p-3 rounded-md">
                                        @if($log->properties['attributes'] ?? null)
                                            <div class="mb-2">
                                                <strong class="text-gray-700">New Values:</strong>
                                                <pre class="text-xs mt-1 text-gray-600">{{ json_encode($log->properties['attributes'], JSON_PRETTY_PRINT) }}</pre>
                                            </div>
                                        @endif
                                        
                                        @if($log->properties['old'] ?? null)
                                            <div>
                                                <strong class="text-gray-700">Old Values:</strong>
                                                <pre class="text-xs mt-1 text-gray-600">{{ json_encode($log->properties['old'], JSON_PRETTY_PRINT) }}</pre>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400">No changes recorded</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No activity logs found for the selected period.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="mt-4">
                {{ $logs->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function toggleDetails(logId) {
        const detailsDiv = document.getElementById(`details-${logId}`);
        const buttonText = document.getElementById(`button-text-${logId}`);
        
        if (detailsDiv.classList.contains('hidden')) {
            detailsDiv.classList.remove('hidden');
            buttonText.textContent = 'Hide Changes';
        } else {
            detailsDiv.classList.add('hidden');
            buttonText.textContent = 'View Changes';
        }
    }
</script>
@endpush
@endsection