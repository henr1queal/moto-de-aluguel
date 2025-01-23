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
    <div class="container">
        <div class="row g-0">
            <div class="col text-center">
                <h1 style="font-size: 20px;">Nova locação</h1>
            </div>
        </div>
        <div class="row mt-4 g-3">
            <div class="col mt-0">
                <form method="POST" action="{{ route('rental.store') }}">
                    @csrf
                    <div><small><strong>Dados sobre o locador:</strong></small></div>
                    <div class="mt-3">
                        <label for="landlord_name" class="form-label fw-light">Nome completo<span
                                class="text-danger"><strong>*</strong></span></label>
                        @error('landlord_name')
                            <input type="text" class="form-control bg-transparent border-danger" id="landlord_name"
                                value="{{ old('landlord_name') ?? null }}" required name="landlord_name">
                            <small class="very-small text-danger">Digite o nome</small>
                        @else
                            <input type="text" class="form-control bg-transparent" id="landlord_name"
                                value="{{ old('landlord_name') ?? null }}" required name="landlord_name">
                        @enderror

                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <label for="landlord_cpf" class="form-label fw-light">CPF<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('landlord_cpf')
                                <input type="text" class="form-control bg-transparent border-danger" id="landlord_cpf"
                                    value="{{ old('landlord_cpf') ?? null }}" required name="landlord_cpf" maxlength="11">
                                <small class="very-small text-danger">CPF com 11 dígitos</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="landlord_cpf"
                                    value="{{ old('landlord_cpf') ?? null }}" required name="landlord_cpf" maxlength="11">
                            @enderror
                        </div>
                        <div class="col mt-0">
                            <label for="driver_license_number" class="form-label fw-light">Número da CNH<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('driver_license_number')
                                <input type="text" class="form-control bg-transparent border-danger"
                                    id="driver_license_number" value="{{ old('driver_license_number') ?? null }}"
                                    required name="driver_license_number">
                                <small class="very-small text-danger">Apenas números</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="driver_license_number"
                                    value="{{ old('driver_license_number') ?? null }}" required name="driver_license_number">
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <label for="driver_license_issue_date" class="form-label fw-light">Data da Emissão da CNH<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('driver_license_issue_date')
                                <input type="date" class="form-control bg-transparent border-danger"
                                    id="driver_license_issue_date" value="{{ old('driver_license_issue_date') ?? null }}"
                                    required name="driver_license_issue_date">
                                <small class="very-small text-danger">Data válida</small>
                            @else
                                <input type="date" class="form-control bg-transparent" id="driver_license_issue_date"
                                    value="{{ old('driver_license_issue_date') ?? null }}" required name="driver_license_issue_date">
                            @enderror
                        </div>
                        <div class="col mt-0">
                            <label for="birth_date" class="form-label fw-light">Data de Nascimento<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('birth_date')
                                <input type="date" class="form-control bg-transparent border-danger" id="birth_date"
                                    value="{{ old('birth_date') ?? null }}" required name="birth_date">
                                <small class="very-small text-danger">Data válida</small>
                            @else
                                <input type="date" class="form-control bg-transparent" id="birth_date"
                                    value="{{ old('birth_date') ?? null }}" required name="birth_date">
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <label for="zip_code" class="form-label fw-light">CEP<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('zip_code')
                                <input type="text" class="form-control bg-transparent border-danger" id="zip_code"
                                    value="{{ old('zip_code') ?? null }}" required name="zip_code" maxlength="8">
                                <small class="very-small text-danger">8 dígitos</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="zip_code"
                                    value="{{ old('zip_code') ?? null }}" required name="zip_code" maxlength="8">
                            @enderror
                        </div>
                        <div class="col mt-0">
                            <label for="street" class="form-label fw-light">Rua<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('street')
                                <input type="text" class="form-control bg-transparent border-danger" id="street"
                                    value="{{ old('street') ?? null }}" required name="street">
                                <small class="very-small text-danger">Nome da rua</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="street"
                                    value="{{ old('street') ?? null }}" required name="street">
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-2 mt-0">
                            <label for="state" class="form-label fw-light">Estado<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <select required name="state" id="state"
                                class="form-control bg-transparent @error('state') border-danger @enderror">
                                <option value="" class="text-black" @selected(!old('state')) disabled>—</option>
                                <option @selected(old('state') && old('state') === 'AC') value="AC" class="text-black">AC</option>
                                <option @selected(old('state') && old('state') === 'AL') value="AL" class="text-black">AL</option>
                                <option @selected(old('state') && old('state') === 'AP') value="AP" class="text-black">AP</option>
                                <option @selected(old('state') && old('state') === 'AM') value="AM" class="text-black">AM</option>
                                <option @selected(old('state') && old('state') === 'BA') value="BA" class="text-black">BA</option>
                                <option @selected(old('state') && old('state') === 'CE') value="CE" class="text-black">CE</option>
                                <option @selected(old('state') && old('state') === 'DF') value="DF" class="text-black">DF</option>
                                <option @selected(old('state') && old('state') === 'ES') value="ES" class="text-black">ES</option>
                                <option @selected(old('state') && old('state') === 'GO') value="GO" class="text-black">GO</option>
                                <option @selected(old('state') && old('state') === 'MA') value="MA" class="text-black">MA</option>
                                <option @selected(old('state') && old('state') === 'MS') value="MS" class="text-black">MS</option>
                                <option @selected(old('state') && old('state') === 'MT') value="MT" class="text-black">MT</option>
                                <option @selected(old('state') && old('state') === 'MG') value="MG" class="text-black">MG</option>
                                <option @selected(old('state') && old('state') === 'PA') value="PA" class="text-black">PA</option>
                                <option @selected(old('state') && old('state') === 'PB') value="PB" class="text-black">PB</option>
                                <option @selected(old('state') && old('state') === 'PR') value="PR" class="text-black">PR</option>
                                <option @selected(old('state') && old('state') === 'PE') value="PE" class="text-black">PE</option>
                                <option @selected(old('state') && old('state') === 'PI') value="PI" class="text-black">PI</option>
                                <option @selected(old('state') && old('state') === 'RJ') value="RJ" class="text-black">RJ</option>
                                <option @selected(old('state') && old('state') === 'RN') value="RN" class="text-black">RN</option>
                                <option @selected(old('state') && old('state') === 'RS') value="RS" class="text-black">RS</option>
                                <option @selected(old('state') && old('state') === 'RO') value="RO" class="text-black">RO</option>
                                <option @selected(old('state') && old('state') === 'RR') value="RR" class="text-black">RR</option>
                                <option @selected(old('state') && old('state') === 'SC') value="SC" class="text-black">SC</option>
                                <option @selected(old('state') && old('state') === 'SP') value="SP" class="text-black">SP</option>
                                <option @selected(old('state') && old('state') === 'SE') value="SE" class="text-black">SE</option>
                                <option @selected(old('state') && old('state') === 'TO') value="TO" class="text-black">TO</option>
                            </select>
                        </div>
                        <div class="col-5 mt-0">
                            <label for="city" class="form-label fw-light">Cidade<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('city')
                                <input type="text" class="form-control bg-transparent border-danger" id="city"
                                    value="{{ old('city') ?? null }}" required name="city">
                                <small class="very-small text-danger">Digite a cidade</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="city"
                                    value="{{ old('city') ?? null }}" required name="city">
                            @enderror
                        </div>
                        <div class="col-5 mt-0">
                            <label for="neighborhood" class="form-label fw-light">Bairro<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('neighborhood')
                                <input type="text" class="form-control bg-transparent border-danger" id="neighborhood"
                                    value="{{ old('neighborhood') ?? null }}" required name="neighborhood">
                                <small class="very-small text-danger">Digite o bairro</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="neighborhood"
                                    value="{{ old('neighborhood') ?? null }}" required name="neighborhood">
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-3 mt-0">
                            <label for="house_number" class="form-label fw-light">Número</label>
                            @error('house_number')
                                <input type="text" class="form-control bg-transparent border-danger" id="house_number"
                                    value="{{ old('house_number') ?? null }}" name="house_number">
                                <small class="very-small text-danger">Apenas números</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="house_number"
                                    value="{{ old('house_number') ?? null }}" name="house_number">
                            @enderror
                        </div>
                        <div class="col mt-0">
                            <label for="complement" class="form-label fw-light">Complemento</label>
                            @error('complement')
                                <input type="text" class="form-control bg-transparent border-danger" id="complement"
                                    value="{{ old('complement') ?? null }}" name="complement">
                                <small class="very-small text-danger">Digite o complemento</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="complement"
                                    value="{{ old('complement') ?? null }}" name="complement">
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <label for="phone_1" class="form-label fw-light">Telefone 1<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('phone_1')
                                <input type="text" class="form-control bg-transparent border-danger" id="phone_1"
                                    value="{{ old('phone_1') ?? null }}" required name="phone_1" maxlength="11">
                                <small class="very-small text-danger">Telefone com 11 dígitos</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="phone_1"
                                    value="{{ old('phone_1') ?? null }}" required name="phone_1" maxlength="11">
                            @enderror
                        </div>
                        <div class="col mt-0">
                            <label for="phone_2" class="form-label fw-light">Telefone 2</label>
                            @error('phone_2')
                                <input type="text" class="form-control bg-transparent border-danger" id="phone_2"
                                    value="{{ old('phone_2') ?? null }}" name="phone_2" maxlength="11">
                                <small class="very-small text-danger">Telefone com 11 dígitos</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="phone_2"
                                    value="{{ old('phone_2') ?? null }}" name="phone_2" maxlength="11">
                            @enderror
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="mother_name" class="form-label fw-light">Nome da mãe</label>
                        @error('mother_name')
                            <input type="text" class="form-control bg-transparent border-danger" id="mother_name"
                                value="{{ old('mother_name') ?? null }}" name="mother_name" maxlength="150">
                            <small class="very-small text-danger">Escreva o nome da mãe</small>
                        @else
                            <input type="text" class="form-control bg-transparent" id="mother_name"
                                value="{{ old('mother_name') ?? null }}" name="mother_name" maxlength="150">
                        @enderror
                    </div>
                    <div class="mt-3">
                        <label for="father_name" class="form-label fw-light">Nome do pai</label>
                        @error('father_name')
                            <input type="text" class="form-control bg-transparent border-danger" id="father_name"
                                value="{{ old('father_name') ?? null }}" name="father_name" maxlength="150">
                            <small class="very-small text-danger">Escreva o nome do pai</small>
                        @else
                            <input type="text" class="form-control bg-transparent" id="father_name"
                                value="{{ old('father_name') ?? null }}" name="father_name" maxlength="150">
                        @enderror
                    </div>
                    <div class="mt-3">
                        <label for="photo" class="form-label fw-light">Foto do locador</label>
                        @error('photo')
                            <input class="form-control bg-transparent border-danger text-white" type="file" id="photo"
                                accept=".png, .jpg, .jpeg" value="{{ old('photo') ?? null }}" name="photo">
                            <small class="very-small text-danger">Arquivo PNG</small>
                        @else
                            <input class="form-control bg-transparent text-white" type="file" id="photo"
                                accept=".png, .jpg, .jpeg" value="{{ old('photo') ?? null }}" name="photo">
                        @enderror
                    </div>
                    <div class="mt-4"><small><strong>Dados sobre o veículo:</strong></small></div>
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <div id="carouselExample" class="carousel slide">
                                <div class="carousel-inner">
                                    <div id="carouselExample" class="carousel slide">
                                        <div class="carousel-inner">
                                            @error('vehicle_id')
                                                @php
                                                    $borderDanger = 'border-danger';
                                                @endphp
                                            @else
                                                @php
                                                    $borderDanger = null;
                                                @endphp
                                            @enderror
                                            @foreach ($myVehicles as $vehicle)
                                                {{-- Início de um novo grupo --}}
                                                @if ($loop->first || ($loop->iteration - 1) % 3 === 0)
                                                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                                        <div class="row g-2">
                                                @endif

                                                {{-- Veículo individual --}}
                                                <div class="col-4 text-center">
                                                    <div class="vehicle rounded-3 py-2 {{ $borderDanger }}">
                                                        <input type="radio" @checked(old('vehicle_id') && old('vehicle_id') === $vehicle->id)
                                                            required name="vehicle_id" value="{{ $vehicle->id }}" @required(!old('vehicle_id'))>
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
                @error('revision_period')
                    <input type="text" class="form-control bg-transparent border-danger" id="revision_period"
                        value="{{ old('revision_period') ?? null }}" required name="revision_period" maxlength="6">
                    <small class="very-small text-danger">Apenas números</small>
                @else
                    <input type="text" class="form-control bg-transparent" id="revision_period"
                        value="{{ old('revision_period') ?? null }}" required name="revision_period" maxlength="6">
                @enderror
            </div>
            <div class="col mt-0">
                <label for="oil_period" class="form-label fw-light">Período troca de óleo<span
                        class="text-danger"><strong>*</strong></span></label>
                @error('oil_period')
                    <input type="text" class="form-control bg-transparent border-danger" id="oil_period"
                        value="{{ old('oil_period') ?? null }}" required name="oil_period" maxlength="6">
                    <small class="very-small text-danger">Apenas números</small>
                @else
                    <input type="text" class="form-control bg-transparent" id="oil_period"
                        value="{{ old('oil_period') ?? null }}" required name="oil_period" maxlength="6">
                @enderror
            </div>
        </div>
        <div class="mt-4"><small><strong>Dados sobre o contrato:</strong></small></div>
        <div class="row g-3 mt-3">
            <div class="col mt-0">
                <label for="cost" class="form-label fw-light">Valor semanal<span
                        class="text-danger"><strong>*</strong></span></label>
                @error('cost')
                    <input type="text" class="form-control bg-transparent border-danger" id="cost"
                        value="{{ old('cost') ?? null }}" required name="cost" maxlength="6">
                    <small class="very-small text-danger">Apenas números</small>
                @else
                    <input type="text" class="form-control bg-transparent" id="cost"
                        value="{{ old('cost') ?? null }}" required name="cost" maxlength="6">
                @enderror
            </div>
            <div class="col mt-0">
                <label for="deposit" class="form-label fw-light">Caução<span
                        class="text-danger"><strong>*</strong></span></label>
                @error('deposit')
                    <input type="text" class="form-control bg-transparent border-danger" id="deposit"
                        value="{{ old('deposit') ?? null }}" required name="deposit" maxlength="6">
                    <small class="very-small text-danger">Apenas números</small>
                @else
                    <input type="text" class="form-control bg-transparent" id="deposit"
                        value="{{ old('deposit') ?? null }}" required name="deposit" maxlength="6">
                @enderror
            </div>
        </div>
        <div class="row g-3 mt-3">
            <div class="col mt-0">
                <label for="start_date" class="form-label fw-light">Início do contrato<span
                        class="text-danger"><strong>*</strong></span></label>
                @error('start_date')
                    <input type="date" class="form-control bg-transparent border-danger" id="start_date"
                        value="{{ old('start_date') ?? null }}" required name="start_date" max="{{ now() }}">
                    <small class="very-small text-danger">Data válida</small>
                @else
                    <input type="date" class="form-control bg-transparent" id="start_date"
                        value="{{ old('start_date') ?? null }}" required name="start_date" max="{{ now() }}">
                @enderror
            </div>
            <div class="col mt-0">
                <label for="end_date" class="form-label fw-light">Duração do contrato<span
                        class="text-danger"><strong>*</strong></span></label>
                <select required name="end_date" class="form-control bg-transparent" id="end_date">
                    <option class="text-black" @selected(old('end_date') && old('end_date') == 1) value="1">1 mês</option>
                    <option class="text-black" @selected(old('end_date') && old('end_date') == 3) value="3">3 meses</option>
                    <option class="text-black" @selected(old('end_date') && old('end_date') == 6) value="6">6 meses</option>
                    <option class="text-black" @selected(old('end_date') && old('end_date') == 12) value="12">12 meses</option>
                    <option class="text-black" @selected(old('end_date') && old('end_date') == 15) value="15">15 meses</option>
                    <option class="text-black" @selected(old('end_date') && old('end_date') == 18) value="18">18 meses</option>
                    <option class="text-black" @selected(old('end_date') && old('end_date') == 24) value="24">24 meses</option>
                    <option class="text-black" @selected(old('end_date') && old('end_date') == 30) value="30">30 meses</option>
                    <option class="text-black" @selected(old('end_date') && old('end_date') == 36) value="36">36 meses</option>
                    <option class="text-black" @selected(old('end_date') && old('end_date') == 48) value="48">48 meses</option>
                </select>
            </div>
        </div>
        <div class="row g-3 mt-3">
            <div class="col mt-0">
                <label for="observation" class="form-label fw-light">Observação</label>
                <textarea rows="3" class="form-control bg-transparent text-white" id="observation" name="observation"
                    maxlength="1000">{{ old('observation') ?? null }}</textarea>
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
                                streetInput.value = null
                                stateSelect.value = null
                                cityInput.value = null
                                neighborhoodInput.value = null
                                complementInput.value = null
                                return;
                            }

                            // Preenche os campos com os dados retornados
                            streetInput.value = data.logradouro || null
                            if (complementInput.value !== '') {
                                streetInput.dispatchEvent(new Event('input'));
                            }

                            stateSelect.value = data.uf || null
                            if (complementInput.value !== '') {
                                stateSelect.dispatchEvent(new Event('input'));
                            }

                            cityInput.value = data.localidade || null
                            if (complementInput.value !== '') {
                                console.log(1)
                                cityInput.dispatchEvent(new Event('input'));
                            }

                            neighborhoodInput.value = data.bairro || null
                            if (complementInput.value !== '') {
                                neighborhoodInput.dispatchEvent(new Event('input'));
                            }

                            complementInput.value = data.complemento || null
                            if (complementInput.value !== '') {
                                complementInput.dispatchEvent(new Event('input'));
                            }
                        })
                        .catch(error => {
                            console.error('Erro:', error);
                            alert('Não foi possível buscar o CEP.');
                        });
                }
            });

            document.querySelectorAll('.vehicle').forEach(vehicle => {
                vehicle.addEventListener('click', () => {
                    document.querySelectorAll('.vehicle.border-danger').forEach(el => {
                        el.classList.remove('border-danger')
                    })

                    const input = vehicle.querySelector(
                        '[name="vehicle_id"]');
                    if (input) {
                        input.checked = true;
                    }
                });
            });

            document.querySelectorAll('input, textarea').forEach(field => {
                field.addEventListener('input', () => {
                    const parentWithBorderDanger = field.closest('.border-danger');

                    if (parentWithBorderDanger) {
                        parentWithBorderDanger.classList.remove('border-danger');
                    }

                    const smallTextDanger = field.closest('.col')?.querySelector(
                        'small.text-danger');
                    if (smallTextDanger) {
                        smallTextDanger.remove();
                    }
                });
            });

        });
    </script>
@endsection
