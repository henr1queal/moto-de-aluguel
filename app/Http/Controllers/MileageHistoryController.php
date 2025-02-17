<?php

namespace App\Http\Controllers;

use App\Models\MileageHistory;
use App\Models\Vehicle;
use Illuminate\Http\Request;
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
        $mileageHistory = $mileageHistory->orderByDesc('date')->orderByDesc('created_at')->paginate(10);

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
        try {
            $validated = $this->validateStoreData($request, $vehicle);

            $validated['vehicle_id'] = $vehicle->id;

            if ($vehicle->user_id !== Auth()->id()) {
                return response()->json(['error' => 'Selecione um veículo existente.'], 400);
            }

            $actualRental = $vehicle->actualRental()->select('id', 'vehicle_id', 'finished_at')->first();

            if ($actualRental) {
                $validated['rental_id'] = $actualRental->id;
            }

            MileageHistory::create($validated);
            $vehicle->actual_km = $validated['actual_km'];
            $vehicle->save();

            return redirect()->back()->with('success', 'Adicionado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors()) // Retorna os erros para a sessão
                ->withInput() // Mantém os inputs preenchidos
                ->with('error', 'Erro ao validar os dados. Verifique os campos e tente novamente.');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('error', 'Erro interno no servidor. Tente novamente mais tarde.');
        }
    }

    public function updateObservation(Request $request, $id)
    {
        try {
            // Validação da requisição
            $request->validate([
                'observation' => 'nullable|string|max:500'
            ]);

            // Busca o registro no banco de dados
            $mileage = MileageHistory::findOrFail($id);

            // Atualiza a observação
            $mileage->observation = $request->observation;
            $mileage->save();

            // Retorna resposta de sucesso
            return response()->json(['success' => true, 'message' => 'Observação atualizada com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao atualizar observação.'], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($mileageHistory)
    {
        $mileageHistory = MileageHistory::where('id', $mileageHistory)
            ->whereHas('vehicle') // Verifica se tem um veículo associado
            ->first(); // Obtém um único registro ou null

        if (!$mileageHistory) {
            return redirect()->back()->with('error', 'Selecione uma entrada existente.');
        }

        // Pegamos o veículo ANTES de deletar o histórico
        $vehicle = $mileageHistory->vehicle;

        // Agora podemos deletar com segurança
        $mileageHistory->delete();

        // Buscar a última quilometragem do veículo após a deleção
        $latestMileage = MileageHistory::where('vehicle_id', $vehicle->id)->latest()->first();

        // Atualiza o `actual_km` baseado no último histórico ou define como 0 se não houver mais registros
        if ($latestMileage) {
            $vehicle->actual_km = $latestMileage->actual_km;
        } else {
            $vehicle->actual_km = $vehicle->first_declared_km > 0 ? $vehicle->first_declared_km : 0;
        }

        // Atualiza as próximas revisões e troca de óleo com base no `actual_km`
        // $vehicle->next_revision = $vehicle->actual_km + $vehicle->revision_period;
        // $vehicle->next_oil_change = $vehicle->actual_km + $vehicle->oil_period;

        $vehicle->save();

        return redirect()->back()->with('success', 'Quilometragem do dia deletada.');
    }


    protected function validateStoreData(Request $request, $vehicle)
    {
        return $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'actual_km' => ['required', 'integer', function ($attribute, $value, $fail) use ($vehicle) {
                if ($value < $vehicle->actual_km) {
                    $fail("O valor de 'actual_km' deve ser maior ou igual a {$vehicle->actual_km}.");
                }
            }],
            'observation' => 'nullable|string'
        ]);
    }
}
