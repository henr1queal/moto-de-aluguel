<?php

namespace App\Http\Controllers;

use App\Models\Revision;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RevisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $vehicle, string $rental = null)
    {
        $revisions = Revision::where('vehicle_id', $vehicle);
        if ($rental) {
            $revisions = $revisions->where('rental_id', $rental);
        }
        $revisions = $revisions->orderByDesc('created_at')->paginate(10);

        $revisions->getCollection()->transform(function ($item, $index) use ($revisions) {
            $item->count = $revisions->total() - (($revisions->currentPage() - 1) * $revisions->perPage() + $index);
            return $item;
        });

        return response()->json($revisions, 200);
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
            Revision::create($validated);

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

        $revision = Revision::findOrFail($id);
        $revision->observation = $request->observation;
        $revision->save();

        return response()->json(['success' => true, 'message' => 'Observação atualizada com sucesso!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($revision)
    {
        $revision = Revision::where('id', $revision)->whereHas('vehicle', function ($query) {
            $query;
        })->delete();
        if ($revision) {
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
