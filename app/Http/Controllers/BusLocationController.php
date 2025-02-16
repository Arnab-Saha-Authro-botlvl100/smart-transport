<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusLocation;

class BusLocationController extends Controller
{
    public function updateLocation(Request $request)
    {
        BusLocation::updateOrCreate(
            ['bus_id' => $request->bus_id],
            ['latitude' => $request->latitude, 'longitude' => $request->longitude]
        );

        return response()->json(['message' => 'Location Updated']);
    }

    public function getLocation($bus_id)
    {
        return response()->json(BusLocation::where('bus_id', $bus_id)->first());
    }
}
