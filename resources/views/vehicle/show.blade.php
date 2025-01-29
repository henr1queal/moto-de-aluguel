@extends('layouts.bootstrap')
@section('head')
    <style>
        .vehicle {
            border: 1px solid white;
        }

        .very-small {
            font-size: 12px;
        }
    </style>
@endsection
@section('content')
    @if (session('success') || session('error'))
        <div class="toast-container position-fixed end-0 p-3" style="z-index: 1055; bottom: 10%;">
            @if (session('success'))
                <div id="success" class="toast align-items-center text-bg-success border-0" role="alert"
                    aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="2500">
                    <div class="d-flex">
                        <div class="toast-body">
                            Sucesso! O veículo foi atualizado.
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div id="error" class="toast align-items-center text-bg-danger border-0" role="alert"
                    aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="2500">
                    <div class="d-flex">
                        <div class="toast-body">
                            {{ session('error') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <div class="container">
        <div class="row g-0">
            <div class="col text-center">
                <h1 style="font-size: 20px;">Visualizando moto</h1>
                @if (!$vehicle->actualRental)
                    <small class="text-success">DISPONÍVEL</small>
                @else
                    <small class="text-warning">ALUGADA</small>
                @endif
            </div>
        </div>
        <div class="row mt-4 g-3">
            <div class="col mt-0">
                <form action="{{ route('vehicle.update', $vehicle->id) }}" method="POST" onsubmit="disableSubmit()">
                    @csrf
                    @method('PATCH')
                    <div class="mt-4"><small><strong>Dados sobre o veículo:</strong></small></div>
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <label for="brand" class="form-label fw-light">Marca<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control bg-transparent" id="brand"
                                value="{{ $vehicle->brand }}" name="brand" disabled readonly required>
                        </div>
                        <div class="col mt-0">
                            <label for="model" class="form-label fw-light">Modelo<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control bg-transparent" id="model"
                                value="{{ $vehicle->model }}" name="model" disabled readonly required>
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <label for="license_plate" class="form-label fw-light">Placa<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('license_plate')
                                <input type="text" class="form-control bg-transparent border-danger" id="license_plate"
                                    value="{{ old('license_plate') }}" name="license_plate" maxlength="8" required>
                                <small class="very-small text-danger">Exemplo: AAA-2H99 ou AAA-1234</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="license_plate"
                                    value="{{ $vehicle->license_plate }}" name="license_plate" maxlength="8" required>
                            @enderror
                        </div>
                        <div class="col mt-0">
                            <label for="renavam" class="form-label fw-light">Renavam<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('renavam')
                                <input type="text" class="form-control bg-transparent border-danger" id="renavam"
                                    value="{{ old('renavam') }}" name="renavam" required>
                                <small class="very-small text-danger">Apenas 11 números.</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="renavam"
                                    value="{{ $vehicle->renavam }}" name="renavam" required>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <label for="year" class="form-label fw-light">Ano modelo<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('year')
                                <input type="text" class="form-control bg-transparent border-danger" id="year"
                                    value="{{ old('year') }}" name="year" maxlength="4" required>
                                <small class="very-small text-danger">Apenas números.</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="year"
                                    value="{{ $vehicle->year }}" name="year" maxlength="4" required>
                            @enderror
                        </div>
                        <div class="col mt-0">
                            <label for="actual_km" class="form-label fw-light">KM Atual<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('actual_km')
                                <input type="text" class="form-control bg-transparent border-danger" id="actual_km"
                                    value="{{ old('actual_km') }}" name="actual_km" required>
                                <small class="very-small text-danger">Apenas números.</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="actual_km"
                                    value="{{ $vehicle->actual_km }}" name="actual_km" required>
                            @enderror
                        </div>
                        <div class="col mt-0">
                            <label for="protection_value" class="form-label fw-light">Valor seguro<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('protection_value')
                                <input type="text" class="form-control bg-transparent border-danger" id="protection_value"
                                    value="{{ old('protection_value') }}" name="protection_value" required>
                                <div id="emailHelp" class="form-text text-secondary">R$</div>
                                <small class="very-small text-danger">Apenas números.</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="protection_value"
                                    value="{{ $vehicle->protection_value }}" name="protection_value" required>
                                <div id="emailHelp" class="form-text text-secondary">R$</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-4"><small><strong>Manutenções e revisões:</strong></small></div>
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <label for="revision_period" class="form-label fw-light">Período de revisão<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('revision_period')
                                <input type="text" class="form-control bg-transparent border-danger" id="revision_period"
                                    value="{{ old('revision_period') }}" name="revision_period" required>
                                <div id="emailHelp" class="form-text text-secondary">Quilômetros</div>
                                <small class="very-small text-danger">Apenas números.</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="revision_period"
                                    value="{{ $vehicle->revision_period }}" name="revision_period" required>
                                <div id="emailHelp" class="form-text text-secondary">Quilômetros</div>
                            @enderror
                        </div>
                        <div class="col mt-0">
                            <label for="oil_period" class="form-label fw-light">Período troca de óleo<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('oil_period')
                                <input type="text" class="form-control bg-transparent border-danger" id="oil_period"
                                    value="{{ old('oil_period') }}" name="oil_period">
                                <div id="emailHelp" class="form-text text-secondary">Quilômetros</div>
                                <small class="very-small text-danger">Apenas números.</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="oil_period"
                                    value="{{ $vehicle->oil_period }}" name="oil_period">
                                <div id="emailHelp" class="form-text text-secondary">Quilômetros</div>
                            @enderror
                        </div>
                        <div class="col-12 mt-2">
                            <small><strong>Última revisão:</strong></small>
                            @if ($vehicle->latestMaintenance)
                                <small>{{ date('d/m/Y', strtotime($vehicle->latestMaintenance->date)) }}.</small>
                            @else
                                <small>N/A</small>
                            @endif
                            @php
                                $latestMaintenanceKm = $vehicle->latestMaintenance->actual_km ?? 0;
                                $revisionPeriod = $vehicle->revision_period; // Período de revisão em km
                                $actualKm = $vehicle->actual_km; // Quilometragem atual do veículo
                                $nextMaintenanceKm = $latestMaintenanceKm + $revisionPeriod; // Quilometragem para a próxima revisão
                                $kmRemaining = $nextMaintenanceKm - $actualKm;
                            @endphp
                            <br><small><strong>Próxima revisão:</strong> <span
                                    class="@if ($kmRemaining < 0) text-danger @else text-success @endif">{{ $kmRemaining }}</span>
                                KM.</small>
                            <br><small><strong>Última troca de óleo:</strong></small>
                            @if ($vehicle->latestOilChange)
                                <small>{{ date('d/m/Y', strtotime($vehicle->latestOilChange->date)) }}.</small>
                            @else
                                <small>N/A</small>
                            @endif
                            <br><small><strong>Próxima troca de óleo:</strong></small>
                            @php
                                // Cálculo para a próxima troca de óleo
                                $oilPeriod = $vehicle->oil_period; // Período de troca de óleo em km
                                $latestOilChangeKm = 0; // Inicializa a quilometragem da última troca de óleo

                                if ($vehicle->latestMaintenance) {
                                    if ($vehicle->latestMaintenance->have_oil_change == 1) {
                                        // Se a última manutenção incluiu troca de óleo
                                        $latestOilChangeKm = $vehicle->latestMaintenance->actual_km;
                                    } else {
                                        // Se a última manutenção não incluiu troca de óleo, verifica o latestOilChange
                                        $latestOilChangeKm = $vehicle->latestOilChange->actual_km ?? 0;
                                    }
                                }
                                $nextOilChangeKm = $latestOilChangeKm + $oilPeriod; // Quilometragem para a próxima troca de óleo
                                $oilKmRemaining = $nextOilChangeKm - $actualKm; // Quilômetros restantes para a troca de óleo
                            @endphp
                            <small>
                                <span
                                    class="@if ($oilKmRemaining < 0) text-danger @else text-success @endif">{{ $oilKmRemaining }}</span>
                                KM.</small>
                        </div>
                    </div>
                    <div class="row g-3 mt-5 mb-5">
                        <div class="col mt-0 text-center">
                            <button type="submit" class="btn btn-success btn-lg" id="submit"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-arrow-repeat me-1" viewBox="0 0 16 16">
                                    <path
                                        d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41m-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9">
                                    </path>
                                    <path fill-rule="evenodd"
                                        d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5 5 0 0 0 8 3M3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9z">
                                    </path>
                                </svg> Atualizar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col">
                <form action="{{ route('vehicle.delete', ['vehicle' => $vehicle->id]) }}" method="post"
                    onsubmit="return confirm('Tem certeza que deseja deletar este veículo?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-sm btn btn-danger" @disabled($vehicle->actualRental)><svg xmlns="http://www.w3.org/2000/svg"
                            width="16" height="16" fill="currentColor" class="bi bi-trash me-1"
                            viewBox="0 0 16 16">
                            <path
                                d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                            <path
                                d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                        </svg>Deletar</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toastElements = document.querySelectorAll('.toast');
            toastElements.forEach(toastElement => {
                const toast = new bootstrap.Toast(toastElement);
                toast.show();
            });

            disableSubmit = () => {
                const submitButton = document.getElementById('submit');
                submitButton.disabled = true;
            }
        });
    </script>
@endsection
