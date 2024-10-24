<?php

namespace App\Http\Controllers;

use App\Models\Truck;
use App\Models\TruckSubunit;
use Illuminate\Http\Request;

class TruckSubunitController extends Controller
{
    public function create(Truck $truck)
    {
        // Get all trucks except the current one and those that are already subunits
        $availableTrucks = Truck::where('id', '!=', $truck->id)->get();
        
        return view('truck-subunits.create', compact('truck', 'availableTrucks'));
    }

    public function store(Request $request, Truck $truck)
    {
        $validated = $request->validate([
            'subunit_truck_id' => [
                'required',
                'exists:trucks,id',
                'different:truck.id',
            ],
            'start_date' => [
                'required',
                'date',
                'after_or_equal:today'
            ],
            'end_date' => [
                'required',
                'date',
                'after:start_date'
            ],
        ]);

        // Check if main truck already has a subunit for these dates
        $mainTruckOverlap = TruckSubunit::where('main_truck_id', $truck->id)
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_date', '<=', $validated['start_date'])
                            ->where('end_date', '>=', $validated['end_date']);
                    });
            })
            ->exists();

        if ($mainTruckOverlap) {
            return back()
                ->withErrors(['dates' => 'This truck already has a subunit assigned for these dates'])
                ->withInput();
        }

        // Check if the selected subunit is available for these dates and check for subunit being used somewhere else
        $subunitOverlap = TruckSubunit::where(function($query) use ($validated) {
            $query->where('subunit_truck_id', $validated['subunit_truck_id'])
                ->orWhere('main_truck_id', $validated['subunit_truck_id']);
        })
        ->where(function ($query) use ($validated) {
            $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                ->orWhere(function ($q) use ($validated) {
                    $q->where('start_date', '<=', $validated['start_date'])
                        ->where('end_date', '>=', $validated['end_date']);
                });
        })
        ->exists();

        if ($subunitOverlap) {
            return back()
                ->withErrors(['subunit_truck_id' => 'The selected truck is not available for these dates'])
                ->withInput();
        }
        $truck->subunits()->create([
            'subunit_truck_id' => $validated['subunit_truck_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date']
        ]);

        return redirect()->route('trucks.show', $truck)
            ->with('success', 'Subunit assigned successfully');
    }

    public function destroy(Truck $truck, TruckSubunit $subunit)
    {
        if ($subunit->main_truck_id !== $truck->id) {
            abort(403);
        }

        $subunit->delete();

        return redirect()->route('trucks.show', $truck)
            ->with('success', 'Subunit assignment removed successfully');
    }
}