@extends('layouts.app')

@section('title', 'Add New Truck')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-sm rounded-lg max-w-2xl mx-auto">
        <div class="p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Create New Truck</h2>

            <form action="{{ route('trucks.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="unit_number" class="block text-sm font-medium text-gray-700">Unit Number</label>
                        <input type="text" name="unit_number" id="unit_number" value="{{ old('unit_number') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-DEFAULT focus:ring focus:ring-primary-light">
                        @error('unit_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                        <div class="relative mt-1">
                            <select name="year" id="year"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-DEFAULT focus:ring focus:ring-primary-light appearance-none cursor-pointer">
                                <option value="">Select Year</option>
                                <optgroup label="Future Years">
                                    @foreach(range(date('Y') + 5, date('Y')) as $year)
                                        <option value="{{ $year }}" {{ old('year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Past 30 Years">
                                    @foreach(range(date('Y') - 1, date('Y') - 30) as $year)
                                        <option value="{{ $year }}" {{ old('year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Older Years">
                                    @foreach(range(date('Y') - 31, 1900) as $year)
                                        <option value="{{ $year }}" {{ old('year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        @error('year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea name="notes" id="notes" rows="3" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-DEFAULT focus:ring focus:ring-primary-light">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('trucks.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded mr-2">
                            Cancel
                        </a>
                        <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg transition duration-300 ease-in-out">
                            Create Truck
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const yearSelect = document.getElementById('year');
    
    yearSelect.addEventListener('change', function() {
        if (this.value) {
            this.classList.add('text-gray-900');
        } else {
            this.classList.remove('text-gray-900');
        }
    });
    if (yearSelect.value) {
        yearSelect.classList.add('text-gray-900');
    }
});
</script>
@endpush
@endsection