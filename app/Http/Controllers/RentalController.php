<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $myRentals = Auth()->user()->rentals;
        return view('rental.index', compact('myRentals'));
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $myVehicles = Vehicle::where('user_id', Auth()->user()->id)->get();
        if($myVehicles->count() === 0) {
            return redirect()->back()->with('error', 'Você precisa ter veículos cadastrados.');
        }
        return view('rental.new', compact('myVehicles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Rental $rental)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rental $rental)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rental $rental)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rental $rental)
    {
        //
    }
}
