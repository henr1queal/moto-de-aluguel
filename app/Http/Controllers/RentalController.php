<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RentalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $myRentals = Auth()->user()->rentals;
        return view('rental.index', compact('myRentals'));
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $myVehicles = Vehicle::where('user_id', Auth()->user()->id)->get();
        if ($myVehicles->count() === 0) {
            return redirect()->back()->with('error', 'Você precisa ter veículos cadastrados.');
        }
        return view('rental.new', compact('myVehicles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateRentalData($request);

        try {
            DB::beginTransaction();
            // $photoPath = $this->uploadPhoto($request->file('photo'));
            $photoPath = '123';
            $rental = $this->createRental($validated, $photoPath);
            $this->updateVehicle($validated['vehicle_id'], $request->only(['revision_period', 'oil_period']));

            DB::commit();

            return redirect()->route('rental.index')->with('success', 'Locação adicionada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            return redirect()->back()->with('error', 'Corrija os dados do formulário e tente novamente.');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Rental $rental)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rental $rental)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rental $rental)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rental $rental)
    {
        //
    }

    protected function validateRentalData(Request $request)
    {
        return $request->validate([
            'landlord_name' => [
                'required',
                'string',
                'max:150',
                function ($attribute, $value, $fail) {
                    if (str_word_count($value) < 2) {
                        $fail('O nome do locador deve conter pelo menos dois nomes.');
                    }
                },
            ],
            'landlord_cpf' => 'required|string|size:11',
            'driver_license_number' => 'required|string|min:7|max:25',
            'driver_license_issue_date' => 'required|date|before:today',
            'birth_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $birthDate = \Carbon\Carbon::parse($value);
                    if ($birthDate->diffInYears(\Carbon\Carbon::now()) < 18) {
                        $fail('A data de nascimento deve indicar que a pessoa tem ao menos 18 anos.');
                    }
                },
            ],
            'phone_1' => 'required|string|size:11',
            'phone_2' => 'nullable|string|size:11',
            'mother_name' => [
                'nullable',
                'string',
                'max:150',
                function ($attribute, $value, $fail) {
                    if ($value && str_word_count($value) < 2) {
                        $fail('O nome da mãe deve conter pelo menos dois nomes.');
                    }
                },
            ],
            'father_name' => [
                'nullable',
                'string',
                'max:150',
                function ($attribute, $value, $fail) {
                    if ($value && str_word_count($value) < 2) {
                        $fail('O nome do pai deve conter pelo menos dois nomes.');
                    }
                },
            ],
            'start_date' => 'required|date|before_or_equal:today',
            'end_date' => 'required|in:1,3,6,12,15,18,24,30,36,48',
            'cost' => 'required|numeric|min:0|max:999999',
            'deposit' => 'required|numeric|min:0|max:999999',
            'zip_code' => 'required|string|size:8',
            'state' => 'required|string|max:2|in:AC,AL,AP,AM,BA,CE,DF,ES,GO,MA,MS,MT,MG,PA,PB,PR,PE,PI,RJ,RN,RS,RO,RR,SC,SP,SE,TO',
            'city' => 'required|string|min:3|max:50',
            'neighborhood' => 'required|string|min:2|max:100',
            'street' => 'required|string|min:2|max:100',
            'house_number' => 'nullable|integer|max:999999',
            'complement' => 'nullable|string|max:150',
            'photo' => 'nullable|image|max:2048',
            'vehicle_id' => [
                'required',
                'exists:vehicles,id',
                function ($attribute, $value, $fail) {
                    $vehicle = \App\Models\Vehicle::find($value);
                    if (!$vehicle || $vehicle->user_id !== auth()->id()) {
                        $fail('Selecione um veículo válido.');
                    }
                },
            ],
            'observation' => 'string|max:1000',
            'oil_period' => 'required|integer|max:999999',
            'revision_period' => 'required|integer|max:999999',
        ]);
    }

    // protected function uploadPhoto($photo)
    // {
    //     return $photo->store('rentals/photos', 'public');
    // }

    protected function createRental(array $data, string $photoPath)
    {
        $data['photo'] = $photoPath;
        $data['end_date'] = Carbon::parse($data['start_date'])->addMonths($data['end_date']);
        return Rental::create($data);
    }

    protected function updateVehicle(string $vehicleId, array $updates)
    {
        $vehicle = Vehicle::find($vehicleId);
        $vehicle->update($updates);
    }
}
