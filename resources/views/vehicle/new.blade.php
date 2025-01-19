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
                <form>
                    <div class="mt-4"><small><strong>Dados sobre o veículo:</strong></small></div>
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <label for="brand" class="form-label fw-light">Marca<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control bg-transparent" id="brand" name="brand">
                        </div>
                        <div class="col mt-0">
                            <label for="model" class="form-label fw-light">Modelo<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control bg-transparent" id="model" name="model">
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <label for="license_plate" class="form-label fw-light">Placa<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control bg-transparent" id="license_plate"
                                name="license_plate">
                        </div>
                        <div class="col mt-0">
                            <label for="renavam" class="form-label fw-light">Renavam<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control bg-transparent" id="renavam" name="renavam">
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <label for="year" class="form-label fw-light">Ano modelo<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control bg-transparent" id="year" name="year">
                        </div>
                        <div class="col mt-0">
                            <label for="actual_km" class="form-label fw-light">KM Atual<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control bg-transparent" id="actual_km" name="oil_period">
                        </div>
                        <div class="col mt-0">
                            <label for="protection_value" class="form-label fw-light">Valor seguro<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control bg-transparent" id="protection_value"
                                name="oil_period">
                        </div>
                    </div>
                    <div class="mt-4"><small><strong>Manutenções e revisões:</strong></small></div>
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <label for="revision_period" class="form-label fw-light">Período de revisão<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control bg-transparent" id="revision_period"
                                name="revision_period">
                            <div id="emailHelp" class="form-text text-secondary">Quilômetros</div>
                        </div>
                        <div class="col mt-0">
                            <label for="oil_period" class="form-label fw-light">Período troca de óleo<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control bg-transparent" id="oil_period" name="oil_period">
                            <div id="emailHelp" class="form-text text-secondary">Quilômetros</div>
                        </div>
                    </div>
                    <div class="mt-4"><small><strong>Valores a serem cobrados:</strong></small></div>
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <label for="cost" class="form-label fw-light">Valor semanal<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control bg-transparent" id="cost" name="cost">
                        </div>
                        <div class="col mt-0">
                            <label for="deposit" class="form-label fw-light">Caução<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control bg-transparent" id="deposit" name="deposit">
                        </div>
                    </div>
                    <div class="row g-3 mt-5 mb-5">
                        <div class="col mt-0 text-center">
                            <input type="submit" value="Adicionar" class="btn btn-success btn-lg">
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
            const zipCodeInput = document.getElementById('zip_code');
            const streetInput = document.getElementById('street');
            const stateSelect = document.getElementById('state');
            const cityInput = document.getElementById('city');
            const neighborhoodInput = document.getElementById('neighborhood');
            const complementInput = document.getElementById('complement');

            zipCodeInput.addEventListener('input', function() {
                const zipCode = zipCodeInput.value.replace(/\D/g, ''); // Remove caracteres não numéricos

                if (zipCode.length === 8) {
                    // Realiza a busca do endereço na API ViaCEP
                    fetch(`https://viacep.com.br/ws/${zipCode}/json/`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Erro ao buscar o CEP');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.erro) {
                                alert('CEP não encontrado');
                                return;
                            }

                            // Preenche os campos com os dados retornados
                            streetInput.value = data.logradouro || '';
                            stateSelect.value = data.uf || '';
                            cityInput.value = data.localidade || '';
                            neighborhoodInput.value = data.bairro || '';
                            complementInput.value = data.complemento || '';
                        })
                        .catch(error => {
                            console.error('Erro:', error);
                            alert('Não foi possível buscar o CEP.');
                        });
                }
            });

            document.querySelectorAll('.vehicle').forEach(vehicle => {
                vehicle.addEventListener('click', () => {
                    const input = vehicle.querySelector('input[name="vehicle_id"]');
                    if (input) {
                        input.checked = true;
                    }
                });
            });

        });
    </script>
@endsection
