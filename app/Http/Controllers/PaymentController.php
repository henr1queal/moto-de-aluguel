<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $vehicle, string $rental = null)
    {
        $paymentsQuery = Payment::query();

        if ($rental) {
            $paymentsQuery->whereHas('rental', function ($query) use ($vehicle, $rental) {
                $query->where('id', $rental)
                    ->where('vehicle_id', $vehicle);
            });
        } else {
            $paymentsQuery->whereHas('rental', function ($query) use ($vehicle) {
                $query->where('vehicle_id', $vehicle);
            });
        }

        $payments = $paymentsQuery->get();

        $payments->transform(function ($item, $index) {
            $item->count = $index + 1;
            return $item;
        });

        return response()->json($payments, 200);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Payment $payment)
    {
        $validate = $request->validate([
            'paid' => 'nullable|boolean'
        ]);

        if(isset($validate['paid']) && $validate['paid'] == 1){
            $payment->paid = 1;
            $payment->paid_in = now();
            $returnMessage = 'Valor recebido!';
        } else {
            $payment->paid = 0;
            $payment->paid_in = null;
            $returnMessage = 'Valor não recebido.';
        }
        $payment->save();

        return response()->json(['success' => $returnMessage]);
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
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
