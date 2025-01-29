<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $vehicle, string $rental = null)
    {
        $maintenances = Maintenance::where('vehicle_id', $vehicle);
        if ($rental) {
            $maintenances = $maintenances->where('rental_id', $rental);
        }
        $maintenances = $maintenances->orderByDesc('created_at')->paginate(10);

        $maintenances->getCollection()->transform(function ($item, $index) use ($maintenances) {
            $item->count = $maintenances->total() - (($maintenances->currentPage() - 1) * $maintenances->perPage() + $index);
            return $item;
        });

        return response()->json($maintenances, 200);
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

        Maintenance::create($validated);
        $vehicle->actual_km = $validated['actual_km'];
        $vehicle->save();
        
        return redirect()->back()->with('success', 'Adicionado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($Maintenance)
    {
        $Maintenance = Maintenance::where('id', $Maintenance)->whereHas('vehicle', function ($query) {
            $query->where('user_id', Auth()->id());
        })->delete();
        if ($Maintenance) {
            return redirect()->back()->with('success', 'Manutenção deletada.');
        };
        return redirect()->back()->with('error', 'Selecione uma manutenção existente.');
    }

    protected function validateStoreData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'cost' => 'required|numeric|min:0|max:999999',
            'actual_km' => 'required|integer',
            'have_oil_change' => 'required|boolean',
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
