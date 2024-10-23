<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Truck;
use App\Models\TruckSubunit;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
class TruckController extends Controller
{
    public function index()
    {
        $trucks = Truck::orderBy('created_at', 'desc')->get();
        return view('trucks.index', compact('trucks'));
    }

    public function create()
    {
        return view('trucks.create');
    }

    public function store(Request $request)
    {
        $maxYear = now()->addYears(5)->year;
        
        $validated = $request->validate([
            'unit_number' => 'required|string|max:255|unique:trucks',
            'year' => ['required', 'integer', 'min:1900', 'max:' . $maxYear],
            'notes' => 'nullable|string'
        ]);

        $truck = Truck::create($validated);

        return redirect()->route('trucks.index')
            ->with('success', 'Truck created successfully.');
    }

    public function show(Truck $truck)
    {
        $subunits = $truck->subunits()
            ->with(['subunitTruck', 'creator'])
            ->orderBy('start_date', 'desc')
            ->get();
            
        return view('trucks.show', compact('truck', 'subunits'));
    }

    public function edit(Truck $truck)
    {
        return view('trucks.edit', compact('truck'));
    }

    public function update(Request $request, Truck $truck)
    {
        $maxYear = now()->addYears(5)->year;
        
        $validated = $request->validate([
            'unit_number' => ['required', 'string', 'max:255', Rule::unique('trucks')->ignore($truck)],
            'year' => ['required', 'integer', 'min:1900', 'max:' . $maxYear],
            'notes' => 'nullable|string'
        ]);

        $truck->update($validated);

        return redirect()->route('trucks.index')
            ->with('success', 'Truck updated successfully.');
    }

    public function destroy(Truck $truck)
    {
        $truck->delete();

        return redirect()->route('trucks.index')
            ->with('success', 'Truck deleted successfully.');
    }
}