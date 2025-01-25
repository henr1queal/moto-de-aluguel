<?php

namespace App\Http\Controllers;

use App\Models\MileageHistory;
use Illuminate\Http\Request;

class MileageHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $vehicle, string $rental = null)
    {
        $mileageHistory = MileageHistory::where('vehicle_id', $vehicle);
        if ($rental) {
            $mileageHistory = $mileageHistory->where('rental_id', $rental);
        }
        $mileageHistory = $mileageHistory->paginate(10);
        return response()->json($mileageHistory, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(MileageHistory $mileageHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MileageHistory $mileageHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MileageHistory $mileageHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MileageHistory $mileageHistory)
    {
        //
    }
}
