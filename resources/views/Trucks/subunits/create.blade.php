@extends('layouts.app')

@section('title', 'Add Subunit')

@section('content')
<div class="bg-white shadow-sm rounded-lg max-w-2xl mx-auto">
    <div class="p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Add Subunit for Truck {{ $truck->unit_number }}</h2>

        @if ($errors->any())
            <div class="bg-red-50 text-red-500 p-4 rounded mb-6">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('trucks.subunits.store', $truck) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="subunit_truck_id" class="block text-sm font-medium text-gray-700">Select Substitute Truck</label>
                    <select name="subunit_truck_id" id="subunit_truck_id" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-DEFAULT focus:ring focus:ring-primary-light">
                        <option value="">Select a truck</option>
                        @foreach($availableTrucks as $availableTruck)
                            <option value="{{ $availableTruck->id }}" {{ old('subunit_truck_id') == $availableTruck->id ? 'selected' : '' }}>
                                {{ $availableTruck->unit_number }} ({{ $availableTruck->year }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-DEFAULT focus:ring focus:ring-primary-light">
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-DEFAULT focus:ring focus:ring-primary-light">
                </div>

                <div class="flex justify-end space-x-3 pt-4">
    <a href="{{ route('trucks.show', $truck) }}" 
       class="bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded">
        Cancel
    </a>
    <button type="submit" 
    class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg transition duration-300 ease-in-out">
        Add Subunit
    </button>
</div>
            </div>
        </form>
    </div>
</div>
@endsection