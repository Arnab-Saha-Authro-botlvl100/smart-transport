<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB; // Add this import
use App\Models\Feedback;
use App\Models\BookingRequest;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $request, BookingRequest $booking = null)
    {
        DB::beginTransaction();

        try {
            if ($booking->feedback) {
                // Update existing feedback
                $booking->feedback->update([
                    'rating' => $request->input('rating'),
                    'content' => $request->input('feedback')
                ]);
                
                $message = 'Feedback updated successfully!';
            } else {
                // Create new feedback
                $feedback = new Feedback();
                $feedback->booking_id = $booking->id;
                $feedback->user_id = auth()->id();
                $feedback->rating = $request->input('rating');
                $feedback->content = $request->input('feedback');
                $feedback->save();
                
                // Associate with booking
                $booking->feedback_id = $feedback->id;
                $booking->save();
                
                $message = 'Thank you for your feedback!';
            }

            DB::commit();

            return redirect()->back()
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Feedback submission failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'An error occurred. Please try again.');
        }
    }
}