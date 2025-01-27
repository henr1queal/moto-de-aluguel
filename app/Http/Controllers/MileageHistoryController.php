<?php

namespace App\Http\Controllers;

use App\Models\MileageHistory;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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
        $mileageHistory = $mileageHistory->orderByDesc('created_at')->paginate(10);

        $mileageHistory->getCollection()->transform(function ($item, $index) use ($mileageHistory) {
            $item->count = $mileageHistory->total() - (($mileageHistory->currentPage() - 1) * $mileageHistory->perPage() + $index);
            return $item;
        });

        return response()->json($mileageHistory, 200);
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

        try {
            MileageHistory::create($validated);
            return redirect()->back()->with('success', 'Adicionado com sucesso!');
        } catch (\Throwable $th) {
            Log::info('Erro ao adicionar KM Diária', ['erro' => $th->getMessage()]);
        }
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($mileageHistory)
    {
        $mileageHistory = MileageHistory::where('id', $mileageHistory)->whereHas('vehicle', function($query){
            $query->where('user_id', Auth()->id());
        })->delete();
        if($mileageHistory) {
            return redirect()->back()->with('success', 'Quilometragem do dia deletada.');
        };
        return redirect()->back()->with('error', 'Selecione uma entrada existente.');
    }

    protected function validateStoreData(Request $request)
    {
        $validator = Validator::make($request->all(), [
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
