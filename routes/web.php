<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BusController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\BusLocationController;
use App\Http\Controllers\BookingRequestController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\BkashTokenizePaymentController;
use Karim007\LaravelBkashTokenize\Facade\BkashPaymentTokenize;

use App\Http\Controllers\PassengerController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});



// Passenger Dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [PassengerController::class, 'index'])->name('dashboard');
    Route::post('/passenger/tickets/booking', [BookingRequestController::class, 'store'])->name('passenger.tickets.booking');
    Route::get('/passengers/buses/{bus}/seats', [TicketController::class, 'getAvailableSeatsForPassenger']);
    Route::delete('/booking/cancel/{id}', [BookingRequestController::class, 'cancelBooking'])->name('cancelBooking');
    
    Route::get('/get-buses-for-map', [PassengerController::class, 'getBusesForMap'])->name('get.buses.for.map');
    // routes/web.php
    Route::post('/pay-booking/{id}/{amount}', [BookingRequestController::class, 'payBooking'])
    ->name('payBooking');

    Route::get('/bkash/callback/{id}', [BookingRequestController::class, 'bkashCallback'])
    ->name('bkash.callback');

    Route::post('/feedback/{booking?}', [FeedbackController::class, 'store'])
    ->name('feedback.submit');

});

//search payment
Route::get('/bkash/search/{trxID}', [App\Http\Controllers\BkashTokenizePaymentController::class,'searchTnx'])->name('bkash-serach');

Route::get('/debug-bkash-token', function() {
    try {
        $token = BkashPaymentTokenize::getToken();
        return response()->json([
            'token' => substr($token, 0, 20).'...',
            'length' => strlen($token),
            'time' => now()->toDateTimeString()
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});
use App\Http\Controllers\DriverController;

// Driver Dashboard
Route::middleware(['auth', 'ensure.user.is.driver'])->group(function () {
    Route::get('/driver/dashboard', [DriverController::class, 'dashboard'])->name('driver.dashboard');
    Route::get('/driver/profile', [DriverController::class, 'profile'])->name('driver.profile');

    Route::post('/driver/location', [DriverController::class, 'location'])->name('driver.send.location');
    Route::post('/driver/remove-location', [DriverController::class, 'removeLocation'])->name('driver.remove.location');

    // Add more driver-specific routes here
});

// Admin Dashboard
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    // Route::resource('admin/buses', BusController::class);
    Route::post('buses', [BusController::class, 'store'])->name('buses.store');
    Route::get('/admin/buses/{bus}/edit', [BusController::class, 'edit'])->name('admin.buses.edit');
    Route::put('admin/buses/{bus}', [BusController::class, 'update'])->name('admin.buses.update');
    Route::get('admin/buses/data', [BusController::class, 'data'])->name('admin.buses.data');
    Route::delete('admin/buses/{bus}', [BusController::class, 'destroy'])->name('admin.buses.destroy');

    Route::post('routes', [RouteController::class, 'store'])->name('routes.store');
    Route::get('/admin/routes/edit/{route}', [RouteController::class, 'edit'])->name('admin.routes.edit');
    Route::put('admin/routes/{route}', [RouteController::class, 'update'])->name('admin.routes.update');
    Route::put('admin/routes/{route}', [RouteController::class, 'update'])->name('admin.routes.update');
    Route::delete('admin/routes/destroy/{route}', [RouteController::class, 'destroy'])->name('admin.routes.destroy');

    Route::get('/admin/tickets/create/{bus}', [TicketController::class, 'create'])->name('admin.tickets.create');
    Route::post('/admin/tickets', [TicketController::class, 'store'])->name('admin.tickets.store');
    Route::get('/admin/buses/{bus}/seats', [TicketController::class, 'getAvailableSeats']);
    Route::post('/admin/tickets/store', [TicketController::class, 'store'])->name('admin.tickets.store');


    
    // Route to display the form
    Route::get('/admin/assign-bus', [BusController::class, 'assignBusForm'])->name('admin.assign.bus.form');

    // Route to handle form submission
    Route::post('/admin/assign-bus', [BusController::class, 'assignBus'])->name('admin.assign.bus');


});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/search-buses', [PassengerController::class, 'searchBuses'])->name('search.buses');

Route::get('/buses', [BusController::class, 'index'])->name('buses.index');
Route::post('/buses', [BusController::class, 'store'])->name('buses.store');

Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');

Route::post('/update-location', [BusLocationController::class, 'updateLocation'])->name('update.location');
Route::get('/get-location/{bus_id}', [BusLocationController::class, 'getLocation'])->name('get.location');


require __DIR__.'/auth.php';
