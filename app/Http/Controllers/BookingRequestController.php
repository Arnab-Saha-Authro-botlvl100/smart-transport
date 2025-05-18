<?php

namespace App\Http\Controllers;

use App\Models\BookingRequest;
use App\Models\Ticket;
// use App\Services\BkashService;
use Karim007\LaravelBkashTokenize\Facade\BkashPaymentTokenize;

use Illuminate\Http\Request;

class BookingRequestController extends Controller
{
    // Show all booking requests for admin
    public function index()
    {
        $bookingRequests = BookingRequest::with('passenger')->latest()->get();
        return view('admin.booking_requests.index', compact('bookingRequests'));
    }

    // Store new booking request (Passenger Side)
    public function store(Request $request)
    {
        $request->validate([
            'tickets' => 'required|array',
            'price' => 'required|numeric',
            'date' => 'required|date',
            'note' => 'nullable|string',
            'bus_id' => 'required|numeric',
        ]);

        BookingRequest::create([
            'passenger_id' => auth()->id(), // Assuming authenticated user
            'ticket_ids' => json_encode($request->tickets),
            'total_amount' => $request->price,
            'date' => $request->date,
            'note' => $request->note,
            'bus_id' => $request->bus_id,
        ]);

        foreach ($request->tickets as $ticketId) {
            // dd($ticketId);
            $ticket = Ticket::where('ticket_number', $ticketId['ticket_number'])
                    ->where('seat_number', $ticketId['seat_number'])->first();
            $ticket->status = 'booked';
            $ticket->save();
        }

        return back()->with('success', 'Booking request submitted successfully!');
    }

    // Approve or Reject booking request (Admin)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $bookingRequest = BookingRequest::findOrFail($id);
        $bookingRequest->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Booking request updated successfully!');
    }
    

    public function payBooking(Request $request, $id, $amount)
    {
        $bookingRequest = BookingRequest::findOrFail($id);
        
        try {
            $invoiceId = 'BOOKING-'.$id.'-'.time();
            
            // Prepare payment data as an array
            $paymentData = [
                'amount' => (string)$amount, // Must be string
                'merchantInvoiceNumber' => $invoiceId,
                'callbackURL' => route('bkash.callback', ['id' => $id]),
                'intent' => 'sale',
                'currency' => 'BDT',
                'mode' => '0011', // Tokenized payment mode
                'payerReference' => $invoiceId
            ];
    
            \Log::debug('Payment Request Data', $paymentData);
    
            // JSON encode the data before passing to cPayment
            $jsonData = json_encode($paymentData);
            
            // Pass the JSON encoded data
            $response = BkashPaymentTokenize::cPayment($jsonData);
    
            \Log::debug('bKash API Response', (array)$response);
    
            if (!isset($response['paymentID'])) {
                $error = $response['statusMessage'] ?? json_encode($response);
                throw new \Exception("Payment failed: $error");
            }
    
            // Store session data
            session([
                'bkash_payment_id' => $response['paymentID'],
                'booking_id' => $id,
                'amount' => $amount,
                'invoice_id' => $invoiceId,
            ]);
    
            return redirect($response['bkashURL']);
    
        } catch (\Exception $e) {
            \Log::error('Payment Error', [
                'booking_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Payment failed. Please try again later.');
        }
    }
    
    public function bkashCallback(Request $request, $id)
    {
        try {
            $paymentID = session('bkash_payment_id');
            
            // Use the package's executePayment method
            $response = BkashPaymentTokenize::executePayment($paymentID);
            
            if ($response['statusCode'] === '0000') {
                // Payment successful
                BookingRequest::find($id)->update([
                    'payment_status' => 'paid',
                    'transaction_id' => $paymentID,
                    'status' => 'approved'
                ]);
                
                return redirect()->route('dashboard', $id)
                    ->with('success', 'Payment completed successfully!');
            }
            
            throw new \Exception($response['statusMessage'] ?? 'Payment failed');
            
        } catch (\Exception $e) {
            return redirect()->route('dashboard', $id)
                ->with('error', 'Payment failed: '.$e->getMessage());
        }
    }

    public function cancelBooking($id)
    {
        $bookingRequest = BookingRequest::findOrFail($id);

        // Change the status of the booking request to 'cancelled'
        $bookingRequest->status = 'rejected';
        $bookingRequest->save();

        // Loop through the tickets in the booking request and update their status
        $ticketIds = json_decode($bookingRequest->ticket_ids, true);

        foreach ($ticketIds as $ticketId) {
            // Find each ticket by its ticket_number and seat_number
            $ticket = Ticket::where('ticket_number', $ticketId['ticket_number'])
                            ->where('seat_number', $ticketId['seat_number'])
                            ->first();

            // If the ticket exists, update its status back to 'available' or 'fit'
            if ($ticket) {
                $ticket->status = 'fit';  // Or 'fit', depending on your system
                $ticket->save();
            }
        }

        return back()->with('success', 'Booking has been cancelled!');
    }

}

