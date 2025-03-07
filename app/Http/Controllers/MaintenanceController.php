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
        try {
            $validated = $this->validateStoreData($request, $vehicle);

            $validated['vehicle_id'] = $vehicle->id;

            if ($vehicle->user_id !== auth()->id()) {
                return redirect()->back()->with('error', 'Selecione um veículo existente.');
            }

            $actualRental = $vehicle->actualRental()->select('id', 'vehicle_id', 'finished_at')->first();
            if ($actualRental) {
                $validated['rental_id'] = $actualRental->id;
            }

            // Criar a manutenção
            Maintenance::create($validated);

            // Atualizar dados do veículo
            if ($validated['actual_km'] >= $vehicle->actual_km) {
                $vehicle->actual_km = $validated['actual_km'];
            }
            $vehicle->next_revision = $validated['actual_km'] + $vehicle->revision_period;

            if ($validated['have_oil_change']) {
                $vehicle->next_oil_change = $validated['actual_km'] + $vehicle->oil_period;
            }

            $vehicle->save();

            return redirect()->back()->with('success', 'Sucesso! O veículo foi atualizado.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors()) // Retorna os erros para a sessão
                ->withInput() // Mantém os inputs preenchidos
                ->with('error', 'Erro adicionar manutenção.');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('error', 'Erro interno no servidor. Tente novamente mais tarde.');
        }
    }

    public function updateObservation(Request $request, $id)
    {
        $request->validate([
            'observation' => 'nullable|string|max:500'
        ]);

        $maintenance = Maintenance::findOrFail($id);
        $maintenance->observation = $request->observation;
        $maintenance->save();

        return response()->json(['success' => true, 'message' => 'Observação atualizada com sucesso!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($Maintenance)
    {
        $Maintenance = Maintenance::where('id', $Maintenance)->whereHas('vehicle', function ($query) {
            $query;
        })->delete();
        if ($Maintenance) {
            return redirect()->back()->with('success', 'Manutenção deletada.');
        };
        return redirect()->back()->with('error', 'Selecione uma manutenção existente.');
    }

    protected function validateStoreData(Request $request, Vehicle $vehicle)
    {
        return $request->validate([
            'date' => 'required|date',
            'cost' => 'required|numeric|min:0|max:999999',
            'actual_km' => ['required', 'integer', function ($attribute, $value, $fail) use ($vehicle) {
                if ($value < $vehicle->actual_km) {
                    $fail("O valor de 'actual_km' deve ser maior ou igual a {$vehicle->actual_km}.");
                }
            }],
            'have_oil_change' => 'required|boolean',
            'observation' => 'nullable|string'
        ]);
    }
}
