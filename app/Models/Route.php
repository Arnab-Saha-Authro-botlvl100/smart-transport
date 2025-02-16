<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = ['origin', 'destination', 'stops'];

    // Cast stops to an array automatically
    protected $casts = [
        'stops' => 'array',
    ];

    public function drivers()
    {
        return $this->belongsToMany(User::class, 'driver_route', 'route_id', 'driver_id');
    }
}

