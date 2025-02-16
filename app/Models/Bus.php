<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = ['bus_number', 'capacity', 'route_id', 'driver_id', 'date', 'time'];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function location()
    {
        return $this->hasOne(BusLocation::class);
    }
}
