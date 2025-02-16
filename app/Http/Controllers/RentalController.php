<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Rental;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RentalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'todos'); // Pega o filtro ou usa "todos" como padrão

        $query = Auth()->user()->rentals();

        switch ($filter) {
            case 'ativos':
                $query->whereNull('finished_at'); // Apenas ativos (não finalizados)
                break;

            case 'cancelados':
                $query->whereNotNull('stop_date'); // Apenas cancelados (parados manualmente)
                break;

            case 'finalizados':
                $query->whereNotNull('finished_at')->whereNull('stop_date'); // Finalizados corretamente
                break;
        }

        $myRentals = $query
            ->orderByRaw('finished_at IS NULL DESC')
            ->orderBy('finished_at', 'desc')
            ->paginate(10);

        foreach ($myRentals as $rental) {
            if ($rental->finished_at === null) {
                $rental->has_overdue_payments = $rental->hasOverduePayments();
            }
        }

        return view('rental.index', compact('myRentals', 'filter'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $myVehicles = Vehicle::whereDoesntHave('actualRental')->get();
        if ($myVehicles->count() === 0) {
            return redirect()->back()->with('error', 'Você precisa ter veículos cadastrados e disponíveis.');
        }
        return view('rental.new', compact('myVehicles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $this->validateRentalData($request);
            DB::beginTransaction();

            $photoPath = isset($validated['photo']) ? $this->uploadPhoto($request->file('photo')) : null;

            $rental = $this->createRental($validated, $photoPath);
            $this->updateVehicle($validated['vehicle_id'], $request->only(['revision_period', 'oil_period']));
            $this->createPayments($rental->id, $validated['start_date'], $validated['end_date'], $validated['cost'], $validated['payment_day']);

            DB::commit();

            return redirect()->route('rental.index')->with('success', 'Locação adicionada com sucesso!');
        } catch (ValidationException $e) {
            DB::rollBack();

            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (QueryException $e) {
            DB::rollBack();

            if (isset($photoPath)) {
                Storage::disk('private')->delete($photoPath);
            }

            return redirect()->back()->with('error', 'Erro no banco de dados: ' . $e->getMessage())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($photoPath)) {
                Storage::disk('private')->delete($photoPath);
            }

            return redirect()->back()->with('error', 'Erro inesperado: ' . $e->getMessage())->withInput();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Rental $rental)
    {
        $days = [
            'Sunday' => 'Domingo',
            'Monday' => 'Segunda-feira',
            'Tuesday' => 'Terça-feira',
            'Wednesday' => 'Quarta-feira',
            'Thursday' => 'Quinta-feira',
            'Friday' => 'Sexta-feira',
            'Saturday' => 'Sábado'
        ];
        $rental->load(['vehicle.latestMaintenance', 'vehicle.latestOilChange']);
        $rental->payment_day = $days[$rental->payment_day];
        return view('rental.show', compact('rental'));
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
        try {
            $validated = $this->validateRentalUpdateData($request);
            DB::beginTransaction();

            if (isset($validated['photo'])) {
                $photoPath = $this->uploadPhoto($request->file('photo'));
                $validated['photo'] = $photoPath;
            }

            $actualCost = $rental->cost;
            if ($actualCost !== $validated['cost']) {
                Payment::where('rental_id', $rental->id)->where('paid', 0)->update(['cost' => $validated['cost']]);
            }

            $rental->fill($validated);

            if ($rental->isDirty()) {
                $rental->save();
            }

            DB::commit();

            return redirect()->back()->with('success', 'Locação atualizada com sucesso!');
        } catch (ValidationException $e) {
            // Adiciona os erros de validação na sessão
            $errorMessages = $e->validator->errors()->all(); // Retorna todos os erros como um array
            return redirect()->back()
                ->with('error', implode(' ', $errorMessages)) // Une os erros em uma única mensagem
                ->withInput($e->validator->validated());
        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($photoPath)) {
                Storage::disk('private')->delete($photoPath);
            }
            return redirect()->back()->with('error', 'Corrija os dados do formulário e tente novamente.');
        }
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
            'driver_license_number' => 'required|string|min:5|max:25',
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
            'photo' => 'nullable|mimes:jpeg,jpg,png|max:10000',
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
            'observation' => 'string|nullable|max:1000',
            'oil_period' => 'required|integer|max:999999',
            'revision_period' => 'required|integer|max:999999',
            'payment_day' => 'required|string|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
        ]);
    }

    protected function validateRentalUpdateData(Request $request)
    {
        $validator = Validator::make($request->all(), [
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
            'driver_license_number' => 'required|string|min:5|max:25',
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
            'cost' => 'required|numeric|min:0|max:999999',
            'zip_code' => 'required|string|size:8',
            'state' => 'required|string|max:2|in:AC,AL,AP,AM,BA,CE,DF,ES,GO,MA,MS,MT,MG,PA,PB,PR,PE,PI,RJ,RN,RS,RO,RR,SC,SP,SE,TO',
            'city' => 'required|string|min:3|max:50',
            'neighborhood' => 'required|string|min:2|max:100',
            'street' => 'required|string|min:2|max:100',
            'house_number' => 'nullable|integer|max:999999',
            'complement' => 'nullable|string|max:150',
            'photo' => 'nullable|mimes:jpeg,jpg,png|max:10000',
            'observation' => 'string|nullable|max:1000',
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

    protected function uploadPhoto($photo)
    {
        $fileName = time() . '_' . $photo->getClientOriginalName(); // Evitar nomes duplicados

        // Salva a imagem na pasta 'storage/app/private'
        $photo->storeAs('private', $fileName);

        return $fileName;
    }

    protected function createRental(array $data, string $photoPath = null)
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

    protected function createPayments(string $rentalId, string $startDate, int $endDate, float $cost, string $paymentDay)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = $startDate->copy()->addMonths($endDate);
        $weeksDifference = $startDate->diffInWeeks($endDate);

        $payments = [];
        for ($i = 0; $i < $weeksDifference; $i++) {
            $paymentDate = $startDate->copy()->addWeeks($i)->next($paymentDay);
            $payments[] = [
                'rental_id' => $rentalId,
                'cost' => $cost,
                'paid' => 0,
                'payment_date' => $paymentDate->toDateString(),
                'created_at' => now()
            ];
        }
        return DB::table('payments')->insert($payments);
    }

    public function photo(Rental $rental)
    {
        $path = storage_path("app/private/{$rental->photo}");

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }

    public function deletePhoto(Rental $rental)
    {
        $photo = $rental->photo;
        $path = storage_path("app/private/{$photo}");

        if (!file_exists($path)) {
            abort(404);
        }

        $rental->update(['photo' => null]);

        Storage::disk('private')->delete($photo);

        return redirect()->back()->with('success', 'Imagem deletada!');
    }

    public function finish(Request $request, Rental $rental)
    {
        $validated = $request->validate([
            'contract_break_fee' => 'required|boolean',
            'contract_break_fee_value' => 'nullable|numeric|min:0',
            'damage_fee' => 'required|boolean',
            'damage_fee_value' => 'nullable|numeric|min:0',
            'stop_date' => 'required|date',
            'finish_observation' => 'nullable|string',
        ]);
        
        $validated['finished_at'] = $validated['stop_date'];
        if ($rental->payments()->where('paid', 0)->count() === 0) {
            $validated['stop_date'] = null;
        };

        // Atualiza os dados do aluguel
        $rental->update($validated);

        return redirect()->back()->with('success', 'Aluguel finalizado com sucesso!');
    }
}
