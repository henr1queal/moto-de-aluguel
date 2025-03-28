<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Part;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MaintenanceController extends Controller
{
    public function index()
    {
        $authUserId = Auth()->user()->id;
        $key = "myVehicles";
        $myVehicles = Cache::remember($key, 60 * 24 * 7, function () {
            return Vehicle::select([
                'id',
                'brand',
                'model',
                'year',
                'license_plate',
                'actual_km'
            ])->with('maintenances')->get();
        });

        return view('maintenance.home', compact('myVehicles'));
        return response()->json($manutencoes);
    }

    public function show($id)
    {
        $maintenance = Maintenance::with(['vehicle', 'parts'])->findOrFail($id);
        return response()->json($maintenance);
    }

    public function getAllParts()
    {
        $parts = Part::select('id', 'name')->get();
        return response()->json($parts);
    }

    public function getPartsChanged(Request $request, $vehicleId)
    {
        $search = $request->input('search', '');

        $maintenances = DB::table('maintenance_part')
            ->join('maintenances', 'maintenance_part.maintenance_id', '=', 'maintenances.id')
            ->join('vehicles', 'maintenances.vehicle_id', '=', 'vehicles.id')
            ->join('parts', 'maintenance_part.part_id', '=', 'parts.id')
            ->select(
                'vehicles.actual_km as vehicle_actual_km',
                'maintenances.date as maintenance_date',
                'maintenances.id as maintenance_id',
                'parts.id as part_id',
                'parts.name as part_name',
                'maintenance_part.initial_km',
                'maintenance_part.final_km',
                'maintenance_part.changed_in',
                'maintenance_part.observation',
                'maintenance_part.type',
                'maintenance_part.quantity',
                'maintenance_part.cost',
            )
            ->where('vehicles.id', '=', $vehicleId)
            ->when($search, function ($query, $search) {
                $query->where('parts.name', 'like', '%' . $search . '%');
            })
            ->orderBy('maintenances.created_at', 'desc')
            ->paginate(10);

        return response()->json($maintenances);
    }

    public function store(Request $request)
    {

        try {
            $request->validate([
                'date' => 'required|date',
                'vehicle_id' => 'required|exists:vehicles,id',
                'items'      => 'required|array',
                'items.*.type' => 'required|string|in:UN.,ML.,LT.',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.cost' => 'required|numeric|min:0',
                'items.*.observation' => 'nullable|string',
            ]);

            DB::beginTransaction();

            $maintenance = Maintenance::create([
                'vehicle_id' => $request->vehicle_id,
                'date' => $request->date,
            ]);

            $vehicleActualKm = Vehicle::select('id', 'actual_km')->find($request->vehicle_id);
            $vehicleActualKm = $vehicleActualKm->actual_km;

            $changedParts = [];

            foreach ($request->items as $key => $item) {
                $item['initial_km'] = $vehicleActualKm;
                // Se a chave for numérica, é uma peça existente
                if (is_numeric($key)) {
                    $partId = (int) $key;
                } else {
                    // Criar nova peça
                    $newPart = Part::firstOrCreate([
                        'name' => $key,
                    ], [
                        'name' => $key
                    ]);
                    $partId = $newPart->id;
                }

                $changedParts[] = $partId;

                // Relacionar no pivot com os dados extras
                $maintenance->parts()->attach($partId, [
                    'type' => $item['type'],
                    'initial_km' => $item['initial_km'],
                    'quantity' => $item['quantity'],
                    'cost' => $item['cost'],
                    'observation' => $item['observation'] ?? null,
                    'created_at' => now(),
                ]);
            }

            $maintenances = Maintenance::where('vehicle_id', $request->vehicle_id)
                ->whereHas('parts', function ($query) use ($changedParts) {
                    $query->whereIn('parts.id', $changedParts);
                })->whereNot('id', $maintenance->id)
                ->pluck('id');

            if ($maintenances->isNotEmpty()) {
                DB::table('maintenance_part')
                    ->whereIn('maintenance_id', $maintenances)
                    ->whereIn('part_id', $changedParts)
                    ->whereNull('changed_in')
                    ->update([
                        'changed_in' => now(),
                        'final_km' => $vehicleActualKm,
                    ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Manutenção salva com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erro ao salvar manutenção', 'details' => $e->getMessage()], 500);
        }
    }

    public function updatePart(Request $request, Maintenance $maintenance, Part $part)
    {
        $data = $request->validate([
            'cost' => 'required|numeric|min:0',
            'observation' => 'nullable|string',
        ]);

        $maintenance->parts()->updateExistingPivot($part->id, $data);

        return response()->json(['message' => 'Peça atualizada com sucesso.']);
    }

    public function detachPart(Maintenance $maintenance, Part $part)
    {
        $maintenance->parts()->detach($part->id);

        if ($maintenance->parts()->count() === 0) {
            $maintenance->delete();
        }

        return response()->json(['message' => 'Item de manutenção deletado.']);
    }


    public function update(Request $request, $id)
    {
        $maintenance = Maintenance::findOrFail($id);

        $data = $request->validate([
            'vehicle_id' => 'sometimes|exists:vehicles,id',
            'date'       => 'sometimes|date',
            'parts'      => 'array',
            'parts.*.id' => 'required_with:parts|exists:parts,id',
            'parts.*.action' => 'required_with:parts|in:add,update,delete',
            'parts.*.observation' => 'nullable|string',
        ]);

        $maintenance->update($data);

        if (!empty($data['parts'])) {
            foreach ($data['parts'] as $part) {
                switch ($part['action']) {
                    case 'add':
                        $maintenance->parts()->attach($part['id'], [
                            'observation' => $part['observation'] ?? null
                        ]);
                        break;

                    case 'update':
                        $maintenance->parts()->updateExistingPivot($part['id'], [
                            'observation' => $part['observation'] ?? null
                        ]);
                        break;

                    case 'delete':
                        $maintenance->parts()->detach($part['id']);
                        break;
                }
            }
        }

        return response()->json($maintenance->load(['vehicle', 'parts']));
    }

    public function destroy($id)
    {
        $maintenance = Maintenance::findOrFail($id);
        $maintenance->delete();

        return response()->json(['message' => 'Manutenção deletada com sucesso!']);
    }
}
