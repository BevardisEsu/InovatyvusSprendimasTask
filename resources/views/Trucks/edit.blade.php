@extends('layouts.app')

@section('title', 'Edit Truck')

@section('content')
<div class="bg-white shadow-sm rounded-lg max-w-2xl mx-auto">
    <div class="p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Edit Truck</h2>

        <form action="{{ route('trucks.update', $truck) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label for="unit_number" class="block text-sm font-medium text-gray-700">Unit Number</label>
                    <input type="text" name="unit_number" id="unit_number" value="{{ old('unit_number', $truck->unit_number) }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-DEFAULT focus:ring focus:ring-primary-light">
                    @error('unit_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                    <input type="number" name="year" id="year" value="{{ old('year', $truck->year) }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-DEFAULT focus:ring focus:ring-primary-light">
                    @error('year')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="3" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-DEFAULT focus:ring focus:ring-primary-light">{{ old('notes', $truck->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('trucks.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded mr-2">
                        Cancel
                    </a>
                    <button type="submit" class="bg-primary-DEFAULT hover:bg-primary-dark text-white font-bold py-2 px-4 rounded">
                        Update Truck
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>