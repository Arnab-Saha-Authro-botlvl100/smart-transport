<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Route;
use App\Models\Bus;

class DriverController extends Controller
{
    /**
     * Display the driver dashboard.
     */
    public function dashboard(): View
    {
        // Get the authenticated driver
        $driver = auth()->user();

        // Get the bus assigned to the driver (if applicable)
        $bus = Bus::where('driver_id', $driver->id)->latest()->first();

        $route_id = $bus->route_id ?? null;

        if ($route_id){
            $route = Route::find($route_id);
        }
        else{
            $route = null;
        }
        
        // dd($routes);
        // dd($driver);
        return view('driver.dashboard', compact('driver', 'route', 'bus'));
    }


    /**
     * Display the driver profile.
     */
    public function profile(): View
    {
        return view('driver.profile');
    }

    
        
    public function location(Request $request)
    {
        // Validate the request
        $request->validate([
            'bus_id' => 'required|integer|exists:buses,id',
            'driver_id' => [
                'required',
                'integer',
                'exists:users,id', // Check if the user exists
                function ($attribute, $value, $fail) {
                    // Check if the user has the role of 'driver'
                    $user = DB::table('users')->where('id', $value)->first();
                    if (!$user || $user->role !== 'driver') {
                        $fail('The selected user is not a driver.');
                    }
                },
            ],
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Update or insert location data into the bus_tracking table
        DB::table('bus_tracking')->updateOrInsert(
            [
                'bus_id' => $request->bus_id,
                'driver_id' => $request->driver_id, // Include driver_id in the condition
            ],
            [
                'latitude'  => $request->latitude,
                'longitude' => $request->longitude,
                'tracked_at' => Carbon::now(), // Current timestamp
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Location tracked successfully',
        ]);
    }


    public function removeLocation(Request $request)
    {
        // Validate the request
        $request->validate([
            'bus_id' => 'required|integer|exists:buses,id',
        ]);

        // Find the bus by ID
        $bus = Bus::find($request->bus_id);

        // If the bus is found, delete all its location tracking data
        if ($bus) {
            DB::table('bus_tracking')
                ->where('bus_id', $bus->id)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'All locations for the bus have been removed successfully.',
            ], 200);
        }

        // If the bus is not found, return an error response
        return response()->json([
            'success' => false,
            'message' => 'Bus not found.',
        ], 404);
    }


    // Add more methods for driver-specific actions here
}
