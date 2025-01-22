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
                <h1 style="font-size: 20px;">Nova locação</h1>
            </div>
        </div>
        <div class="row mt-4 g-3">
            <div class="col mt-0">
                <form>
                    <div><small><strong>Dados sobre o locador:</strong></small></div>
                    <div class="mt-3">
                        <label for="landlord_name" class="form-label fw-light">Nome completo<span
                                class="text-danger"><strong>*</strong></span></label>
                        <input type="text" class="form-control bg-transparent" id="landlord_name" name="landlord_name">
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <label for="landlord_cpf" class="form-label fw-light">CPF<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control bg-transparent" id="landlord_cpf" name="landlord_cpf"
                                maxlength="11">
                        </div>
                        <div class="col mt-0">
                            <label for="driver_license_number" class="form-label fw-light">Número da CNH<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control bg-transparent" id="driver_license_number"
                                name="driver_license_number">
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <label for="driver_license_issue_date" class="form-label fw-light">Data da Emissão da CNH<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="date" class="form-control bg-transparent" id="driver_license_issue_date"
                                name="driver_license_issue_date">
                        </div>
                        <div class="col mt-0">
                            <label for="birth_date" class="form-label fw-light">Data de Nascimento<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="date" class="form-control bg-transparent" id="birth_date" name="birth_date">
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <label for="zip_code" class="form-label fw-light">CEP<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control bg-transparent" id="zip_code" name="zip_code"
                                maxlength="8">
                        </div>
                        <div class="col mt-0">
                            <label for="street" class="form-label fw-light">Rua<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control bg-transparent" id="street" name="street">
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-2 mt-0">
                            <label for="state" class="form-label fw-light">Estado<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <select name="state" id="state" class="form-control bg-transparent">
                                <option value="" class="text-black" selected disabled>—</option>
                                <option value="AC" class="text-black">AC</option>
                                <option value="AL" class="text-black">AL</option>
                                <option value="AP" class="text-black">AP</option>
                                <option value="AM" class="text-black">AM</option>
                                <option value="BA" class="text-black">BA</option>
                                <option value="CE" class="text-black">CE</option>
                                <option value="DF" class="text-black">DF</option>
                                <option value="ES" class="text-black">ES</option>
                                <option value="GO" class="text-black">GO</option>
                                <option value="MA" class="text-black">MA</option>
                                <option value="MS" class="text-black">MS</option>
                                <option value="MT" class="text-black">MT</option>
                                <option value="MG" class="text-black">MG</option>
                                <option value="PA" class="text-black">PA</option>
                                <option value="PB" class="text-black">PB</option>
                                <option value="PR" class="text-black">PR</option>
                                <option value="PE" class="text-black">PE</option>
                                <option value="PI" class="text-black">PI</option>
                                <option value="RJ" class="text-black">RJ</option>
                                <option value="RN" class="text-black">RN</option>
                                <option value="RS" class="text-black">RS</option>
                                <option value="RO" class="text-black">RO</option>
                                <option value="RR" class="text-black">RR</option>
                                <option value="SC" class="text-black">SC</option>
                                <option value="SP" class="text-black">SP</option>
                                <option value="SE" class="text-black">SE</option>
                                <option value="TO" class="text-black">TO</option>
                            </select>
                        </div>
                        <div class="col-5 mt-0">
                            <label for="city" class="form-label fw-light">Cidade<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control bg-transparent" id="city" name="city">
                        </div>
                        <div class="col-5 mt-0">
                            <label for="neighborhood" class="form-label fw-light">Bairro<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control bg-transparent" id="neighborhood"
                                name="neighborhood">
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-3 mt-0">
                            <label for="house_number" class="form-label fw-light">Número</label>
                            <input type="text" class="form-control bg-transparent" id="house_number"
                                name="house_number">
                        </div>
                        <div class="col mt-0">
                            <label for="complement" class="form-label fw-light">Complemento<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control bg-transparent" id="complement" name="complement">
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <label for="phone_1" class="form-label fw-light">Telefone 1<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control bg-transparent" id="phone_1" name="phone_1">
                        </div>
                        <div class="col mt-0">
                            <label for="phone_1" class="form-label fw-light">Telefone 2</label>
                            <input type="text" class="form-control bg-transparent" id="phone_1" name="phone_1">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="mother_name" class="form-label fw-light">Nome da mãe</label>
                        <input type="text" class="form-control bg-transparent" id="mother_name" name="mother_name">
                    </div>
                    <div class="mt-3">
                        <label for="father_name" class="form-label fw-light">Nome do pai</label>
                        <input type="text" class="form-control bg-transparent" id="father_name" name="father_name">
                    </div>
                    <div class="mt-3">
                        <label for="photo" class="form-label fw-light">Foto do locador</label>
                        <input class="form-control bg-transparent text-white" type="file" id="photo"
                            name="photo">
                    </div>
                    <div class="mt-4"><small><strong>Dados sobre o veículo:</strong></small></div>
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <div id="carouselExample" class="carousel slide">
                                <div class="carousel-inner">
                                    <div id="carouselExample" class="carousel slide">
                                        <div class="carousel-inner">
                                            @foreach ($myVehicles as $vehicle)
                                                {{-- Início de um novo grupo --}}
                                                @if ($loop->first || ($loop->iteration - 1) % 3 === 0)
                                                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                                        <div class="row g-2">
                                                @endif
                                    
                                                {{-- Veículo individual --}}
                                                <div class="col-4 text-center">
                                                    <div class="vehicle rounded-3 py-2">
                                                        <input type="radio" name="vehicle_id" value="{{ $vehicle->id }}">
                                                        <br><small>{{ $vehicle->brand }} {{ $vehicle->model }}</small>
                                                        <br><small>{{ $vehicle->license_plate }}</small>
                                                        <br><small>KM: {{ $vehicle->actual_km }}</small>
                                                        <br><small>ANO: {{ $vehicle->year }}</small>
                                                    </div>
                                                </div>
                                    
                                                {{-- Fim do grupo ou último item --}}
                                                @if ($loop->iteration % 3 === 0 || $loop->last)
                                                        </div> {{-- Fecha .row --}}
                                                    </div> {{-- Fecha .carousel-item --}}
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-2">
                                <button class="carousel-control-prev position-relative" type="button"
                                    data-bs-target="#carouselExample" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next position-relative" type="button"
                                    data-bs-target="#carouselExample" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <label for="revision_period" class="form-label fw-light">Período de revisão<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control bg-transparent" id="revision_period"
                                name="revision_period">
                        </div>
                        <div class="col mt-0">
                            <label for="oil_period" class="form-label fw-light">Período troca de óleo<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control bg-transparent" id="oil_period" name="oil_period">
                        </div>
                    </div>
                    <div class="mt-4"><small><strong>Dados sobre o contrato:</strong></small></div>
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
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <label for="start_date" class="form-label fw-light">Início do contrato<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="date" class="form-control bg-transparent" id="start_date" name="start_date">
                        </div>
                        <div class="col mt-0">
                            <label for="end_date" class="form-label fw-light">Duração do contrato<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <select name="end_date" class="form-control bg-transparent" id="end_date">
                                <option class="text-black" value="1">1 mês</option>
                                <option class="text-black" value="3">3 meses</option>
                                <option class="text-black" value="6">6 meses</option>
                                <option class="text-black" value="12">12 meses</option>
                                <option class="text-black" value="15">15 meses</option>
                                <option class="text-black" value="18">18 meses</option>
                                <option class="text-black" value="24">24 meses</option>
                                <option class="text-black" value="30">30 meses</option>
                                <option class="text-black" value="36">36 meses</option>
                                <option class="text-black" value="48">48 meses</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <label for="observation" class="form-label fw-light">Observação</label>
                            <textarea rows="3" class="form-control bg-transparent" id="observation" name="observation"></textarea>
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
