{{-- resources/views/trucks/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Truck Details')

@section('content')
<div class="space-y-6">
    <!-- Truck Details -->
    <div class="bg-white shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Truck Details</h2>
                <div>
                    <a href="{{ route('trucks.edit', $truck) }}" class="bg-primary-DEFAULT hover:bg-primary-dark text-white font-bold py-2 px-4 rounded mr-2">
                        Edit Truck
                    </a>
                </div>
            </div>

            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Unit Number</dt>
                    <dd class="mt-1 text-lg text-gray-900">{{ $truck->unit_number }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Year</dt>
                    <dd class="mt-1 text-lg text-gray-900">{{ $truck->year }}</dd>
                </div>
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Notes</dt>
                    <dd class="mt-1 text-lg text-gray-900">{{ $truck->notes ?: 'No notes available' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Subunits Section -->
    <div class="bg-white shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Subunits</h2>
                <a href="{{ route('trucks.subunits.create', $truck) }}" class="bg-primary-DEFAULT hover:bg-primary-dark text-white font-bold py-2 px-4 rounded">
                    Add Subunit
                </a>
            </div>

            @if($truck->subunits->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subunit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($truck->subunits as $subunit)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $subunit->subunitTruck->unit_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $subunit->start_date->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $subunit->end_date->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <form action="{{ route('trucks.subunits.destroy', [$truck, $subunit]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to remove this subunit?')">
                                                Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-4">No subunits assigned yet.</p>
            @endif
        </div>
    </div>
</div>
@endsection