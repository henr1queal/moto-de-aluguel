<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class FineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $vehicle, string $rental = null)
    {
        $fines = Fine::where('vehicle_id', $vehicle);
        if ($rental) {
            $fines = $fines->where('rental_id', $rental);
        }
        $fines = $fines->orderByDesc('created_at')->paginate(10);

        $fines->getCollection()->transform(function ($item, $index) use ($fines) {
            $item->count = $fines->total() - (($fines->currentPage() - 1) * $fines->perPage() + $index);
            return $item;
        });

        return response()->json($fines, 200);
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
    public function store(Request $request, string $vehicle)
    {
        $validated = $this->validateStoreData($request);
        $vehicle = Vehicle::where('id', $vehicle)->with('actualRental:id,vehicle_id')->first();

        if (!$vehicle) {
            return redirect()->back()->with('error', 'Selecione um veículo existente.');
        }

        if (!$vehicle->actualRental) {
            return redirect()->back()->with('error', 'Multa só pode ser adicionada a um veículo em locação.');
        };

        $validated['vehicle_id'] = $vehicle->id;
        $validated['rental_id'] = $vehicle->actualRental->id;

        Fine::create($validated);

        return redirect()->back()->with('success', 'Multa adicionada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Fine $fine)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fine $fine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fine $fine)
    {
        $validate = $request->validate([
            'paid' => 'nullable|boolean'
        ]);

        if(isset($validate['paid']) && $validate['paid'] == 1){
            $fine->paid = 1;
            $returnMessage = 'Multa paga!';
        } else {
            $fine->paid = 0;
            $returnMessage = 'Multa não paga.';
        }
        $fine->save();

        return response()->json(['success' => $returnMessage]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($fine)
    {
        $fine = Fine::where('id', $fine)->whereHas('vehicle', function ($query) {
            $query;
        })->delete();
        if (!$fine) {
            return redirect()->back()->with('error', 'Selecione uma multa existente.');
        };
        return redirect()->back()->with('success', 'Multa deletada.');
    }

    protected function validateStoreData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date|before_or_equal:today',
            'cost' => 'numeric|min:0|max:999999',
            'paid' => 'nullable|in:0,1',
            'observation' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            // Retorna os dados validados parcialmente e os erros
            throw ValidationException::withMessages([
                'errors' => $validator->errors(),
                'validated' => $validator->validated(),
            ]);
        }

        return $validator->validated();
    }
}
