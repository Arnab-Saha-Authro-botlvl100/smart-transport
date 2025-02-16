<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\User;
use App\Models\Route;
use App\Models\Ticket;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Fetch statistics for the dashboard
        $totalBuses = Bus::count();
        $totalRoutes = Route::count();
        $totalTicketsSold = Ticket::count();
        // $totalRevenue = Payment::sum('amount');  // Assuming 'amount' is the column storing payment details
        $totalRevenue = 0;  // Assuming 'amount' is the column storing payment details
        // Fetch active routes (assuming you have an 'is_active' column in your 'routes' table)
        $activeRoutes = Route::count();

        $buses = Bus::all();
        $routes  = Route::all();

       
        // dd($routes);
        // Pass the data to the view
        return view('admin.dashboard', compact('totalBuses', 'totalRoutes', 'totalTicketsSold',
         'totalRevenue', 'activeRoutes', 'buses', 'routes'));
        }
}
