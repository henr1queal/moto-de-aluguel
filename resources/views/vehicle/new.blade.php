@extends('layouts.bootstrap')
@section('head')
    <style>
        .vehicle {
            border: 1px solid white;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row g-0">
            <div class="col text-center">
                <h1 style="font-size: 20px;">Nova moto</h1>
            </div>
        </div>
        <div class="row mt-4 g-3">
            <div class="col mt-0">
                <form action="{{ route('vehicle.store') }}" method="POST" onsubmit="disableSubmit()">
                    @csrf
                    <div class="mt-4"><small><strong>Dados sobre o veículo:</strong></small></div>
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <label for="brand" class="form-label fw-light">Marca<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('brand')
                                <input type="text" class="form-control bg-transparent border-danger" id="brand"
                                    value="{{ old('brand') }}" name="brand" required>
                                <small class="very-small text-danger">Digite uma marca válida.</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="brand"
                                    value="{{ old('brand') }}" name="brand" required>
                            @enderror
                        </div>
                        <div class="col mt-0">
                            <label for="model" class="form-label fw-light">Modelo<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('model')
                                <input type="text" class="form-control bg-transparent border-danger" id="model"
                                    value="{{ old('model') }}" name="model" required>
                                <small class="very-small text-danger">Digite um modelo válido.</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="model"
                                    value="{{ old('model') }}" name="model" required>
                            @enderror
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
                                    value="{{ old('license_plate') }}" name="license_plate" maxlength="8" required>
                            @enderror
                        </div>
                        <div class="col mt-0">
                            <label for="renavam" class="form-label fw-light">Renavam<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('renavam')
                                <input type="text" class="form-control bg-transparent border-danger" id="renavam"
                                    value="{{ old('renavam') }}" name="renavam" required>
                                <small class="very-small text-danger">Apenas números.</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="renavam"
                                    value="{{ old('renavam') }}" name="renavam" required>
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
                                    value="{{ old('year') }}" name="year" maxlength="4" required>
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
                                    value="{{ old('actual_km') }}" name="actual_km" required>
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
                                    value="{{ old('protection_value') }}" name="protection_value" required>
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
                                    value="{{ old('revision_period') }}" name="revision_period" required>
                                <div id="emailHelp" class="form-text text-secondary">Quilômetros</div>
                            @enderror
                        </div>
                        <div class="col mt-0">
                            <label for="oil_period" class="form-label fw-light">Período troca de óleo<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('oil_period')
                                <input type="text" class="form-control bg-transparent border-danger" id="oil_period"
                                    value="{{ old('oil_period') }}" name="oil_period" required>
                                <div id="emailHelp" class="form-text text-secondary">Quilômetros</div>
                                <small class="very-small text-danger">Apenas números.</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="oil_period"
                                    value="{{ old('oil_period') }}" name="oil_period" required>
                                <div id="emailHelp" class="form-text text-secondary">Quilômetros</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3 mt-5 mb-5">
                        <div class="col mt-0 text-center">
                            <button type="submit" class="btn btn-success btn-lg" id="submit"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy me-1" viewBox="0 0 16 16">
                                <path d="M11 2H9v3h2z"/>
                                <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                              </svg> Adicionar</button>
                        </div>
                    </div>
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
