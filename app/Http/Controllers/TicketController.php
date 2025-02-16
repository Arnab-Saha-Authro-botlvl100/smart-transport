<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Bus;
use App\Models\Route;
use Carbon\Carbon;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = auth()->user()->tickets;
        return view('passenger.tickets', compact('tickets'));
    }
    public function create(Bus $bus)
    {
        return view('admin.tickets.create', compact('bus'));
    }

    public function getAvailableSeats($bus_id)
    {
        $bus = Bus::findOrFail($bus_id);
        $routes = Route::all(); // Fetch all available routes
        $routeId = $bus->route_id; // Get the specific route ID for this bus
    
        $seats = [];
        for ($i = 1; $i <= $bus->capacity; $i++) {
            $seatNumber = substr($bus->bus_number, 0, 2) . '-' . $i;
            $ticketNumber = substr($bus->bus_number, 0, 2) . '-' . strtoupper(dechex(random_int(100000, 999999)));
            $seat_status = Ticket::where('bus_id', $bus->id)->where('seat_number', $seatNumber)->value('status') ?? '';
    
            $seats[] = [
                'seat_number' => $seatNumber,
                'ticket_number' => $ticketNumber,
                'seat_status' => $seat_status,
            ];
        }
    
        return response()->json([
            'routes' => $routes, // Return routes separately
            'selected_route' => $routeId, // Send the current route ID of the bus
            'seats' => $seats, // Send seats separately
        ]);
    }
    
    public function getAvailableSeatsForPassenger($bus_id)
    {
        $bus = Bus::findOrFail($bus_id);
        $tickets = Ticket::where('bus_id', $bus_id)->where('status', 'fit')->get();
    
        return response()->json([
            'bus' => $bus, // Return routes separately
            'seats' => $tickets, // Send seats separately
        ]);
    }
    

    public function store(Request $request)
    {
        // dd($request->all()); // Check the request data

        if (!$request->has('tickets') || empty($request->tickets)) {
            return response()->json(['error' => 'No tickets received'], 400);
        }

        foreach ($request->tickets as $ticket) {
            // dd( (int)$request->route_id);
            Ticket::create([
                'bus_id' => $request->bus_id,
                'route_id' => (int) $request->route_id, // Ensure this is passed
                'price' => $request->price ?? 0,  // Handle missing price
                'user_id' => auth()->id(),
                'seat_number' => $ticket['seat_number'],
                'ticket_number' => $ticket['ticket_number'],
                'date' => Carbon::parse($request->date)->format('Y-m-d'),
                'status' => 'fit',
            ]);
        }
        

        return response()->json(['success' => 'Tickets booked successfully!']);
    }


}

