<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'passenger_id',
        'bus_id',
        'ticket_ids',
        'total_amount',
        'date',
        'note',
        'payment_status',
        'status',
    ];

    protected $casts = [
        'ticket_ids' => 'array', // Convert JSON ticket_ids to array automatically
    ];

    // Relationship with User (Passenger)
    public function passenger()
    {
        return $this->belongsTo(User::class, 'passenger_id');
    }
}
