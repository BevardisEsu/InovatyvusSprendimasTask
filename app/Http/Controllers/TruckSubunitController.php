<?php

namespace App\Http\Controllers;
use App\Models\Truck;
use App\Models\TruckSubunit;
use Illuminate\Http\Request;

class TruckSubunitController extends Controller
{
    public function create(Truck $truck)
    {
        $availableTrucks = Truck::where('id', '!=', $truck->id)->get();
        return view('truck-subunits.create', compact('truck', 'availableTrucks'));
    }

    public function store(Request $request, Truck $truck)
    {
        $validated = $request->validate([
            'subunit_truck_id' => ['required', 'exists:trucks,id', 'different:truck.id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
        ]);

        // Check for date overlaps for the main truck
        $existingOverlap = TruckSubunit::where('main_truck_id', $truck->id)
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_date', '<=', $validated['start_date'])
                            ->where('end_date', '>=', $validated['end_date']);
                    });
            })
            ->exists();

        if ($existingOverlap) {
            return back()
                ->withErrors(['dates' => 'The selected dates overlap with an existing subunit assignment'])
                ->withInput();
        }

        // Check if the subunit truck is already assigned on the period
        $subunitOverlap = TruckSubunit::where('subunit_truck_id', $validated['subunit_truck_id'])
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
                ->withErrors(['subunit' => 'The selected truck is already assigned as a subunit during this period'])
                ->withInput();
        }

        // Check if the subunit truck has its own subunits during this period
        $hasOwnSubunits = TruckSubunit::where('main_truck_id', $validated['subunit_truck_id'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_date', '<=', $validated['start_date'])
                            ->where('end_date', '>=', $validated['end_date']);
                    });
            })
            ->exists();

        if ($hasOwnSubunits) {
            return back()
                ->withErrors(['subunit' => 'The selected truck has its own subunits during this period'])
                ->withInput();
        }

        // Validations
        $truck->subunits()->create([
            'subunit_truck_id' => $validated['subunit_truck_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date']
        ]);

        return redirect()->route('trucks.show', $truck)
            ->with('success', 'Subunit assigned successfully.');
    }

    public function destroy(Truck $truck, TruckSubunit $subunit)
    {
        // Quick security check
        if ($subunit->main_truck_id !== $truck->id) {
            abort(403);
        }

        $subunit->delete();

        return redirect()->route('trucks.show', $truck)
            ->with('success', 'Subunit assignment removed successfully.');
    }
}
