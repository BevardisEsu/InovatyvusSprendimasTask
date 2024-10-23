@extends('layouts.app')

@section('title', 'Truck List')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold text-gray-800">Truck List</h1>
        <a href="{{ route('trucks.create') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg transition duration-300 ease-in-out">
            Add New Truck
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Number</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($trucks as $truck)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $truck->unit_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $truck->year }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('trucks.show', $truck) }}" class="text-blue-600 hover:text-blue-900">View</a>
                            <a href="{{ route('trucks.edit', $truck) }}" class="text-blue-600 hover:text-blue-900 ml-2">Edit</a>
                            <form action="{{ route('trucks.destroy', $truck) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
