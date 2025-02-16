<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route;  // Correct import
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function store(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'routeName' => 'required|string|max:255',
            'startLocation' => 'required|string|max:255',
            'endLocation' => 'required|string|max:255',
            'stops' => 'required|array', // Ensure stops is an array
            'stops.*' => 'string', // Validate each stop as a string (if necessary)
        ]);

        // Create the new route
        $route = new Route();
        $route->origin = $validated['startLocation'];
        $route->destination = $validated['endLocation'];

        // Store the stops as a JSON array
        $route->stops = json_encode($validated['stops']); // Ensure it's a valid JSON array

        $route->save();

        return response()->json([
            'success' => true,
            'message' => 'Route added successfully.',
            'data' => $route // Optionally return the created route data
        ], 201); // 201 is the HTTP status code for created resource
    }

    
    // Show the form to edit an existing bus
    public function edit(Route $route)
    {
        return view('admin.routes.edit', compact('route'));
    }


    // Update an existing bus record
    public function update(Request $request, Route $route)
    {
        $validated = $request->validate([
            'routeName' => 'required|string|max:255',
            'startLocation' => 'required|string|max:255',
            'endLocation' => 'required|string|max:255',
            'stops' => 'required|array', // Ensure stops is an array
            'stops.*' => 'string', // Validate each stop as a string (if necessary)
        ]);

        // Update the bus record
        $route->update([
            'origin' => $request->startLocation,
            'destination' => $request->endLocation,
            'stops' => json_encode($request->stops),
            
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Route updated successfully.');
    }


    // Delete a bus record
    public function destroy(Route $route)
    {
        // dd($route);
        // Delete the bus
        $route->delete();

        // Redirect with a success message
        return redirect()->route('admin.dashboard')->with('success', 'Route deleted successfully.');
    }

    public function assignRouteForm()
    {
        $drivers = User::where('role', 'driver')->get();
        $routes = Route::all();
        return view('admin.assign_route', compact('drivers', 'routes'));
    }

    public function assignRoute(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:users,id',
            'route_id' => 'required|exists:routes,id',
        ]);

        // Assign the driver to the route
        $driver = User::find($request->driver_id);
        $driver->routes()->attach($request->route_id);

        return redirect()->back()->with('success', 'Route assigned successfully!');
    }

}
