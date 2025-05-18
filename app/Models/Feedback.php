<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    protected $table = 'feedbacks'; // Explicitly set table name
    protected $fillable = [
        'user_id',
        'booking_id',
        'rating',
        'content'
    ];

    protected $casts = [
        'rating' => 'integer',
    ];
    public function booking()
{
    return $this->belongsTo(BookingRequest::class, 'booking_id');
}

}