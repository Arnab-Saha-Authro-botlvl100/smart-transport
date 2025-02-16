<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bus;
use App\Models\Route;
use App\Models\BookingRequest;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class PassengerController extends Controller
{
    /**
     * Show Passenger Dashboard
     */
    public function index(Request $request)
    {
        $search = $request->input('search');  // Capture the search input

        $buses = Bus::with('route')->get();
        $tickets = Ticket::where('user_id', auth()->id())->get();

        // Fetch booking requests and apply search if provided
        $booking_requests = BookingRequest::where('passenger_id', auth()->id())
            ->where('status', '!=', 'rejected')
            ->join('buses', 'booking_requests.bus_id', '=', 'buses.id')
            ->select('booking_requests.*', 'buses.bus_number')
            ->when($search, function($query) use ($search) {
                return $query->where('buses.bus_number', 'like', "%$search%");
            })
            ->paginate(4);  // 4 buses per page

        $startfrom = Route::select('origin')->distinct()->pluck('origin');
        $destination = Route::select('destination')->distinct()->pluck('destination');

        $buses_for_map = Bus::leftJoin('bus_tracking', function ($join) {
            $join->on('bus_tracking.bus_id', '=', 'buses.id')
                ->whereRaw('bus_tracking.tracked_at = (SELECT MAX(tracked_at) FROM bus_tracking WHERE bus_tracking.bus_id = buses.id)');
        })
        ->select(
            'buses.id as bus_id',
            'buses.driver_id',
            'bus_tracking.latitude',
            'bus_tracking.longitude',
            'bus_tracking.tracked_at'
        )
        ->get();

        return view('dashboard', compact('buses', 'tickets', 'booking_requests', 'startfrom', 'destination', 'buses_for_map', 'search'));
    }

    

    /**
     * Search Buses by Route
     */
    public function searchBuses(Request $request)
    {
        $request->validate([
            'startfrom' => 'required|string',
            'destination' => 'required|string',
            'date' => 'nullable|date',
            'time' => 'nullable',
        ]);

        $buses = Bus::whereHas('route', function ($query) use ($request) {
            $query->where('origin', $request->startfrom)
                ->where('destination', $request->destination);
        })
        ->when($request->date, function ($query) use ($request) {
            return $query->whereDate('date', $request->date);
        })
        ->when($request->time, function ($query) use ($request) {
            return $query->whereTime('time', '>=', $request->time);
        })
        ->with('route') // Eager load route data
        ->get();

        return response()->json([
            'success' => true,
            'buses' => $buses,
        ]);
    }


    
    public function bookTicket($bus_id)
    {
        $bus = Bus::findOrFail($bus_id);
        return view('passenger.book_ticket', compact('bus'));
    }

    /**
     * Confirm Ticket Booking
     */
    public function confirmTicket(Request $request)
    {
        Ticket::create([
            'user_id' => auth()->id(),
            'bus_id' => $request->bus_id,
            'seat_number' => $request->seat_number,
            'status' => 'booked'
        ]);

        return redirect('/passenger/dashboard')->with('success', 'Ticket Booked Successfully!');
    }

    /**
     * Show Live Bus Tracking
     */
    public function trackBus($bus_id)
    {
        $bus = Bus::findOrFail($bus_id);
        return view('passenger.track_bus', compact('bus'));
    }

    public function getBusesForMap()
    {
        // dd("huh");
        $buses = DB::table('bus_tracking')
            ->join('buses', 'buses.id', '=', 'bus_tracking.bus_id')
            ->select('bus_tracking.*', 'buses.bus_number')
            ->get();

        return response()->json($buses);
    }

    // public function getBusesForMap(Request $request)
    // {
    //     // Validate the request to ensure bus_ids are provided
    //     // $request->validate([
    //     //     'bus_ids' => 'required|array', // Expect an array of bus IDs
    //     // ]);

    //     // Fetch bus tracking data for the given bus IDs
    //     $buses = DB::table('bus_tracking')
    //         ->join('buses', 'buses.id', '=', 'bus_tracking.bus_id')
    //         ->whereIn('bus_tracking.bus_id', $request->bus_ids) // Filter by bus IDs
    //         ->select('bus_tracking.*', 'buses.bus_number')
    //         ->get();

    //     return response()->json($buses);
    // }

}
