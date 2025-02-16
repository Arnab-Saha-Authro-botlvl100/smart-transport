<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bus;
use App\Models\User;
use App\Models\Route;  // Import Route model for route data
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BusController extends Controller
{
    // Display a listing of buses
    public function index()
    {
        $buses = Bus::all();
        return view('admin.buses.index', compact('buses'));
    }

    // Show the form to create a new bus
    public function create()
    {
        $routes = Route::all();  // Get all available routes to show in the form
        return view('admin.buses.create', compact('routes'));
    }

    // Store the newly created bus
    public function store(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'busName' => 'required|string|max:255',
            'busNumber' => 'required|string|max:255',
            'route' => 'required|exists:routes,id',  // Ensure route exists
            'capacity' => 'required|integer|min:1',
            'date' => 'date',
            'time' => 'string'
        ]);

        // Create the bus record
        $bus = new Bus();
        $bus->name = $validated['busName'];
        $bus->bus_number = $validated['busNumber'];
        $bus->date = $validated['date'];
        $bus->time = $validated['time'];
        $bus->route_id = $validated['route'];
        $bus->capacity = $validated['capacity'];

        $bus->user = Auth::id(); // Save the authenticated user's ID
        $bus->save();

        return response()->json([
            'success' => true,
            'message' => 'Bus added successfully.',
            'data' => $bus // Optionally return the created route data
        ], 201); // 201 is the HTTP status code for created resource
    }

    // Show the form to edit an existing bus
    public function edit(Bus $bus)
    {
        $routes  = Route::all();
        return view('admin.buses.edit', compact('bus', 'routes'));
    }


    // Update an existing bus record
    public function update(Request $request, Bus $bus)
    {
        $request->validate([
            'busName' => 'required|string|max:255',
            'busNumber' => 'required|string|max:255',
            'route' => 'required|exists:routes,id',
            'capacity' => 'required|integer|min:1',
        ]);

        // Update the bus record
        $bus->update([
            'name' => $request->busName,
            'bus_number' => $request->busNumber,
            'route_id' => $request->route,
            'capacity' => $request->capacity,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Bus updated successfully.');
    }


    // Delete a bus record
    public function destroy(Bus $bus)
    {
        // Delete the bus
        $bus->delete();

        // Redirect with a success message
        return redirect()->route('admin.dashboard')->with('success', 'Bus deleted successfully.');
    }
    
    public function data()
    {
        if (request()->ajax()) {
            $buses = Bus::with('route')->get(); // Load buses with their routes

            return datatables()->of($buses)
                ->addColumn('actions', function($bus) {
                    return '
                        <a href="' . route('admin.buses.edit', $bus->id) . '" class="btn btn-warning">Edit</a>
                        <form action="' . route('admin.buses.destroy', $bus->id) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    ';
                })
                ->make(true); // Return the JSON response to DataTables
        }
    }

    public function assignBusForm()
    {
        $drivers = User::where('role', 'driver')->get();

        $buses = Bus::all();

        $assigned_buses = Bus::where('driver_id', '!=', null)
        ->join('users', 'users.id', '=', 'buses.driver_id')
        ->join('routes', 'routes.id', '=', 'buses.route_id')
        ->select('buses.*', 'users.name as driver_name', 'routes.*', 'users.phone as driver_phone')->get();

        return view('admin.buses.assign_bus', compact('drivers', 'buses', 'assigned_buses'));
    }

    public function assignBus(Request $request)
    {
        // Validate the form input
        $request->validate([
            'driver_id' => 'required|exists:users,id',
            'bus_id' => 'required|exists:buses,id',
        ]);

        // Find the selected bus
        $bus = Bus::find($request->bus_id);

        // Assign the driver to the bus
        $bus->driver_id = $request->driver_id;
        $bus->save();

        // Redirect back with a success message
        return redirect()->route('admin.assign.bus.form')->with('success', 'Bus assigned to driver successfully!');
    }

   

}
