<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bus;

class BusController extends Controller
{
    public function index()
    {
        $buses = Bus::all();
        return view('admin.buses', compact('buses'));
    }

    public function store(Request $request)
    {
        Bus::create($request->all());
        return redirect()->back()->with('success', 'Bus Added!');
    }
}
