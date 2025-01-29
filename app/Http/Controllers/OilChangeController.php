<?php

namespace App\Http\Controllers;

use App\Models\OilChange;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class OilChangeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $vehicle, string $rental = null)
    {
        $oilChanges = OilChange::where('vehicle_id', $vehicle);
        if ($rental) {
            $oilChanges = $oilChanges->where('rental_id', $rental);
        }
        $oilChanges = $oilChanges->orderByDesc('created_at')->paginate(10);

        $oilChanges->getCollection()->transform(function ($item, $index) use ($oilChanges) {
            $item->count = $oilChanges->total() - (($oilChanges->currentPage() - 1) * $oilChanges->perPage() + $index);
            return $item;
        });

        return response()->json($oilChanges, 200);
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
    public function store(Request $request, Vehicle $vehicle)
    {
        $validated = $this->validateStoreData($request);

        $validated['vehicle_id'] = $vehicle->id;

        if ($vehicle->user_id !== Auth()->id()) {
            return response()->json(['error' => 'Selecione um veículo existente.'], 400);
        }

        $actualRental = $vehicle->actualRental()->select('id', 'vehicle_id', 'finished_at')->first();

        if ($actualRental) {
            $validated['rental_id'] = $actualRental->id;
        }

        OilChange::create($validated);
        $vehicle->actual_km = $validated['actual_km'];
        $vehicle->save();
        
        return redirect()->back()->with('success', 'Adicionado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($Maintenance)
    {
        $Maintenance = OilChange::where('id', $Maintenance)->whereHas('vehicle', function ($query) {
            $query->where('user_id', Auth()->id());
        })->delete();
        if ($Maintenance) {
            return redirect()->back()->with('success', 'Troca de óleo deletada.');
        };
        return redirect()->back()->with('error', 'Selecione uma troca de óleo existente.');
    }

    protected function validateStoreData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'cost' => 'required|numeric|min:0|max:999999',
            'actual_km' => 'required|integer',
            'observation' => 'string'
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
