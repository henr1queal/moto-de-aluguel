<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authUserId = Auth()->user()->id;
        $myVehicles = Vehicle::myVehicles()->select([
            'id',
            'brand',
            'model',
            'year',
            'license_plate'
        ])->get();
        return view('vehicle.home', compact('myVehicles'));
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
        $validated = $request->validate([
            'brand' => 'required|string|max:20',
            'model' => 'required|string|max:20',
            'license_plate' => 'required|regex:/^[A-Z]{3}-\d{1}[A-Z0-9]{1}\d{2}$/|string|max:8|unique:vehicles,license_plate',
            'renavam' => 'required|string|max:11|unique:vehicles,renavam',
            'actual_km' => 'required|integer|min:0',
            'revision_period' => 'required|integer',
            'year' => 'required|integer|between:2010,2026',
            'oil_period' => 'required|integer',
            'protection_value' => 'required|numeric|min:0',
        ]);

        $validated['user_id'] = auth()->id();

        $vehicle = Vehicle::create($validated);

        return redirect()->route('vehicle.show', ['vehicle' => $vehicle->id])->with('success', 'Veículo criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        $vehicle->load('actualRental');
        return view('vehicle.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        try {
            $validated = $request->validate([
                'license_plate' => 'required|regex:/^[A-Z]{3}-\d{1}[A-Z0-9]{1}\d{2}$/|string|max:8|unique:vehicles,license_plate,' . $vehicle->id,
                'renavam' => 'required|string|max:11|unique:vehicles,renavam,' . $vehicle->id,
                'actual_km' => 'required|integer|min:0',
                'revision_period' => 'required|integer',
                'year' => 'required|integer|between:2010,2026',
                'oil_period' => 'required|integer',
                'protection_value' => 'required|numeric|min:0',
            ]);

            // Exclui os campos brand e model para garantir que não sejam atualizados
            unset($validated['brand'], $validated['model']);

            $vehicle->update($validated);

            return redirect()->route('vehicle.show', $vehicle->id)->with('success', 'O veículo foi atualizado com sucesso.');
        } catch (\Illuminate\Validation\ValidationException $th) {
            // Captura os erros de validação e redireciona de volta com os erros
            return redirect()
                ->route('vehicle.show', $vehicle->id)
                ->withErrors($th->errors()) // Passa os erros de validação
                ->withInput()
                ->with('error', 'Houve um ou mais erros ao atualizar.');
        } catch (\Throwable $th) {
            return redirect()
                ->route('vehicle.show', $vehicle->id)
                ->with('error', 'Houve um ou mais erros ao atualizar.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        if($vehicle->user_id === Auth()->user()->id) {
            $vehicle->delete();
            return redirect()->route('vehicle.index')->with('error', 'O veículo foi deletado.');
        } else {
            return redirect()->back()->with('error', 'Veículo inválido.');
        }
    }
}
