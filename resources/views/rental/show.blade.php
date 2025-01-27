@extends('layouts.bootstrap')
@section('head')
    <style>
        .vehicle {
            border: 1px solid white;
        }

        .very-small {
            font-size: 12px;
        }

        .content {
            height: 85dvh;
            margin-bottom: 10px;
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
                            {{ session('success') }}
                        </div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
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
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                    </div>
                </div>
            @endif
        </div>
    @endif
    <div class="container">
        <form method="POST" action="{{ route('rental.patch', ['rental' => $rental->id]) }}">
            @csrf
            @method('PATCH')
            <div class="row g-0">
                <div class="col text-center">
                    <h1 style="font-size: 20px;">Visualizando Locação</h1>
                </div>
            </div>
            <div class="row mt-4 g-3">
                <div class="col mt-0">
                    <div><small><strong>Dados sobre o locador:</strong></small></div>
                    <div class="mt-3">
                        <label for="landlord_name" class="form-label fw-light">Nome completo<span
                                class="text-danger"><strong>*</strong></span></label>
                        @error('landlord_name')
                            <input type="text" class="form-control bg-transparent border-danger" id="landlord_name"
                                value="{{ old('landlord_name') }}" required name="landlord_name">
                            <small class="very-small text-danger">Digite o nome</small>
                        @else
                            <input type="text" class="form-control bg-transparent" id="landlord_name"
                                value="{{ $rental->landlord_name ?? null }}" required name="landlord_name">
                        @enderror
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <label for="landlord_cpf" class="form-label fw-light">CPF<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('landlord_cpf')
                                <input type="text" class="form-control bg-transparent border-danger" id="landlord_cpf"
                                    value="{{ old('landlord_cpf') }}" required name="landlord_cpf" maxlength="11">
                                <small class="very-small text-danger">CPF com 11 dígitos</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="landlord_cpf"
                                    value="{{ $rental->landlord_cpf ?? null }}" required name="landlord_cpf" maxlength="11">
                            @enderror
                        </div>
                        <div class="col mt-0">
                            <label for="driver_license_number" class="form-label fw-light">Número da CNH<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('driver_license_number')
                                <input type="text" class="form-control bg-transparent border-danger"
                                    id="driver_license_number" value="{{ old('driver_license_number') }}" required
                                    name="driver_license_number">
                                <small class="very-small text-danger">Apenas números</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="driver_license_number"
                                    value="{{ $rental->driver_license_number ?? null }}" required name="driver_license_number">
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <label for="driver_license_issue_date" class="form-label fw-light">Data da Emissão da CNH<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('driver_license_issue_date')
                                <input type="date" class="form-control bg-transparent border-danger"
                                    id="driver_license_issue_date" value="{{ old('driver_license_issue_date') }}" required
                                    name="driver_license_issue_date">
                                <small class="very-small text-danger">Data válida</small>
                            @else
                                <input type="date" class="form-control bg-transparent" id="driver_license_issue_date"
                                    value="{{ $rental->driver_license_issue_date ?? null }}" required
                                    name="driver_license_issue_date">
                            @enderror
                        </div>
                        <div class="col mt-0">
                            <label for="birth_date" class="form-label fw-light">Data de Nascimento<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('birth_date')
                                <input type="date" class="form-control bg-transparent border-danger" id="birth_date"
                                    value="{{ old('birth_date') }}" required name="birth_date">
                                <small class="very-small text-danger">Data válida</small>
                            @else
                                <input type="date" class="form-control bg-transparent" id="birth_date"
                                    value="{{ $rental->birth_date ?? null }}" required name="birth_date">
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-4 mt-0">
                            <label for="zip_code" class="form-label fw-light">CEP<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('zip_code')
                                <input type="text" class="form-control bg-transparent border-danger" id="zip_code"
                                    value="{{ old('zip_code') }}" required name="zip_code" maxlength="8">
                                <small class="very-small text-danger">8 dígitos</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="zip_code"
                                    value="{{ $rental->zip_code ?? null }}" required name="zip_code" maxlength="8">
                            @enderror
                        </div>
                        <div class="col-8 mt-0">
                            <label for="street" class="form-label fw-light">Rua<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('street')
                                <input type="text" class="form-control bg-transparent border-danger" id="street"
                                    value="{{ old('street') }}" required name="street">
                                <small class="very-small text-danger">Nome da rua</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="street"
                                    value="{{ $rental->street ?? null }}" required name="street">
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-2 mt-0">
                            <label for="state" class="form-label fw-light">Estado<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <select required name="state" id="state"
                                class="form-control bg-transparent @error('state') border-danger @enderror">
                                <option @selected((old('state') && old('state') === 'AC') || $rental->state === 'AC') value="AC" class="text-black">AC</option>
                                <option @selected((old('state') && old('state') === 'AL') || $rental->state === 'AL') value="AL" class="text-black">AL</option>
                                <option @selected((old('state') && old('state') === 'AP') || $rental->state === 'AP') value="AP" class="text-black">AP</option>
                                <option @selected((old('state') && old('state') === 'AM') || $rental->state === 'AM') value="AM" class="text-black">AM</option>
                                <option @selected((old('state') && old('state') === 'BA') || $rental->state === 'BA') value="BA" class="text-black">BA</option>
                                <option @selected((old('state') && old('state') === 'CE') || $rental->state === 'CE') value="CE" class="text-black">CE</option>
                                <option @selected((old('state') && old('state') === 'DF') || $rental->state === 'DF') value="DF" class="text-black">DF</option>
                                <option @selected((old('state') && old('state') === 'ES') || $rental->state === 'ES') value="ES" class="text-black">ES</option>
                                <option @selected((old('state') && old('state') === 'GO') || $rental->state === 'GO') value="GO" class="text-black">GO</option>
                                <option @selected((old('state') && old('state') === 'MA') || $rental->state === 'MA') value="MA" class="text-black">MA</option>
                                <option @selected((old('state') && old('state') === 'MS') || $rental->state === 'MS') value="MS" class="text-black">MS</option>
                                <option @selected((old('state') && old('state') === 'MT') || $rental->state === 'MT') value="MT" class="text-black">MT</option>
                                <option @selected((old('state') && old('state') === 'MG') || $rental->state === 'MG') value="MG" class="text-black">MG</option>
                                <option @selected((old('state') && old('state') === 'PA') || $rental->state === 'PA') value="PA" class="text-black">PA</option>
                                <option @selected((old('state') && old('state') === 'PB') || $rental->state === 'PB') value="PB" class="text-black">PB</option>
                                <option @selected((old('state') && old('state') === 'PR') || $rental->state === 'PR') value="PR" class="text-black">PR</option>
                                <option @selected((old('state') && old('state') === 'PE') || $rental->state === 'PE') value="PE" class="text-black">PE</option>
                                <option @selected((old('state') && old('state') === 'PI') || $rental->state === 'PI') value="PI" class="text-black">PI</option>
                                <option @selected((old('state') && old('state') === 'RJ') || $rental->state === 'RJ') value="RJ" class="text-black">RJ</option>
                                <option @selected((old('state') && old('state') === 'RN') || $rental->state === 'RN') value="RN" class="text-black">RN</option>
                                <option @selected((old('state') && old('state') === 'RS') || $rental->state === 'RS') value="RS" class="text-black">RS</option>
                                <option @selected((old('state') && old('state') === 'RO') || $rental->state === 'RO') value="RO" class="text-black">RO</option>
                                <option @selected((old('state') && old('state') === 'RR') || $rental->state === 'RR') value="RR" class="text-black">RR</option>
                                <option @selected((old('state') && old('state') === 'SC') || $rental->state === 'SC') value="SC" class="text-black">SC</option>
                                <option @selected((old('state') && old('state') === 'SP') || $rental->state === 'SP') value="SP" class="text-black">SP</option>
                                <option @selected((old('state') && old('state') === 'SE') || $rental->state === 'SE') value="SE" class="text-black">SE</option>
                                <option @selected((old('state') && old('state') === 'TO') || $rental->state === 'TO') value="TO" class="text-black">TO</option>
                            </select>
                        </div>
                        <div class="col-5 mt-0">
                            <label for="city" class="form-label fw-light">Cidade<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('city')
                                <input type="text" class="form-control bg-transparent border-danger" id="city"
                                    value="{{ old('city') }}" required name="city">
                                <small class="very-small text-danger">Digite a cidade</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="city"
                                    value="{{ $rental->city ?? null }}" required name="city">
                            @enderror
                        </div>
                        <div class="col-5 mt-0">
                            <label for="neighborhood" class="form-label fw-light">Bairro<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('neighborhood')
                                <input type="text" class="form-control bg-transparent border-danger" id="neighborhood"
                                    value="{{ old('neighborhood') }}" required name="neighborhood">
                                <small class="very-small text-danger">Digite o bairro</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="neighborhood"
                                    value="{{ $rental->neighborhood ?? null }}" required name="neighborhood">
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-3 mt-0">
                            <label for="house_number" class="form-label fw-light">Número</label>
                            @error('house_number')
                                <input type="text" class="form-control bg-transparent border-danger" id="house_number"
                                    value="{{ old('house_number') }}" name="house_number">
                                <small class="very-small text-danger">Apenas números</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="house_number"
                                    value="{{ $rental->house_number ?? null }}" name="house_number">
                            @enderror
                        </div>
                        <div class="col mt-0">
                            <label for="complement" class="form-label fw-light">Complemento</label>
                            @error('complement')
                                <input type="text" class="form-control bg-transparent border-danger" id="complement"
                                    value="{{ old('complement') }}" name="complement">
                                <small class="very-small text-danger">Digite o complemento</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="complement"
                                    value="{{ $rental->complement ?? null }}" name="complement">
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <label for="phone_1" class="form-label fw-light">Telefone 1<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('phone_1')
                                <input type="text" class="form-control bg-transparent border-danger" id="phone_1"
                                    value="{{ old('phone_1') }}" required name="phone_1" maxlength="11">
                                <small class="very-small text-danger">Telefone com 11 dígitos</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="phone_1"
                                    value="{{ $rental->phone_1 ?? null }}" required name="phone_1" maxlength="11">
                            @enderror
                        </div>
                        <div class="col mt-0">
                            <label for="phone_2" class="form-label fw-light">Telefone 2</label>
                            @error('phone_2')
                                <input type="text" class="form-control bg-transparent border-danger" id="phone_2"
                                    value="{{ old('phone_2') }}" name="phone_2" maxlength="11">
                                <small class="very-small text-danger">Telefone com 11 dígitos</small>
                            @else
                                <input type="text" class="form-control bg-transparent" id="phone_2"
                                    value="{{ $rental->phone_2 ?? null }}" name="phone_2" maxlength="11">
                            @enderror
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="mother_name" class="form-label fw-light">Nome da mãe</label>
                        @error('mother_name')
                            <input type="text" class="form-control bg-transparent border-danger" id="mother_name"
                                value="{{ old('mother_name') }}" name="mother_name" maxlength="150">
                            <small class="very-small text-danger">Escreva o nome da mãe</small>
                        @else
                            <input type="text" class="form-control bg-transparent" id="mother_name"
                                value="{{ $rental->mother_name ?? null }}" name="mother_name" maxlength="150">
                        @enderror
                    </div>
                    <div class="mt-3">
                        <label for="father_name" class="form-label fw-light">Nome do pai</label>
                        @error('father_name')
                            <input type="text" class="form-control bg-transparent border-danger" id="father_name"
                                value="{{ old('father_name') }}" name="father_name" maxlength="150">
                            <small class="very-small text-danger">Escreva o nome do pai</small>
                        @else
                            <input type="text" class="form-control bg-transparent" id="father_name"
                                value="{{ $rental->father_name ?? null }}" name="father_name" maxlength="150">
                        @enderror
                    </div>
                    <div class="mt-3">
                        <label for="photo" class="form-label fw-light">Foto do locador</label>
                        <input class="form-control bg-transparent @error('photo') border-danger @enderror text-white"
                            type="file" id="photo" accept=".png, .jpg, .jpeg" value="{{ $rental->photo }}"
                            name="photo">
                        @error('photo')
                            <small class="very-small text-danger">Arquivo PNG</small>
                        @enderror
                    </div>
                    <div class="mt-4"><small><strong>Dados sobre o veículo:</strong></small></div>
                    <div class="row g-3 mt-3">
                        <div class="col-auto text-center mt-0">
                            <div class="vehicle rounded-3 py-2 px-3">
                                <small>{{ $rental->vehicle->brand }} {{ $rental->vehicle->model }}</small>
                                <br><small>{{ $rental->vehicle->license_plate }}</small>
                                <br><small>KM: {{ $rental->vehicle->actual_km }}</small>
                                <br><small>ANO: {{ $rental->vehicle->year }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4"><small><strong>Dados sobre o contrato:</strong></small></div>
            <div class="row g-3 mt-3">
                <div class="col mt-0">
                    <label for="cost" class="form-label fw-light">Valor semanal<span
                            class="text-danger"><strong>*</strong></span></label>
                    @error('cost')
                        <input type="text" class="form-control bg-transparent border-danger" id="cost"
                            value="{{ old('cost') }}" required name="cost" maxlength="6">
                        <small class="very-small text-danger">Apenas números</small>
                    @else
                        <input type="text" class="form-control bg-transparent" id="cost"
                            value="{{ $rental->cost ?? null }}" required name="cost" maxlength="6">
                    @enderror
                </div>
                <div class="col mt-0">
                    <label for="deposit" class="form-label fw-light">Caução<span
                            class="text-danger"><strong>*</strong></span></label>
                    @error('deposit')
                        <input type="text" class="form-control bg-transparent border-danger" id="deposit"
                            value="{{ old('deposit') }}" required name="deposit" maxlength="6">
                        <small class="very-small text-danger">Apenas números</small>
                    @else
                        <input type="text" class="form-control bg-transparent" id="deposit"
                            value="{{ $rental->deposit ?? null }}" required name="deposit" maxlength="6">
                    @enderror
                </div>
            </div>
            <div class="row g-3 mt-3">
                <div class="col mt-0">
                    <label for="start_date" class="form-label fw-light">Início do contrato<span
                            class="text-danger"><strong>*</strong></span></label>
                    <input type="date" class="form-control bg-transparent" id="start_date"
                        value="{{ $rental->start_date }}" required name="start_date" disabled readonly>
                </div>
                <div class="col mt-0">
                    <label for="end_date" class="form-label fw-light">Fim do contrato<span
                            class="text-danger"><strong>*</strong></span></label>
                    <select required name="end_date" class="form-control bg-transparent" id="end_date" disabled
                        readonly>
                        <option class="text-black" selected value="{{ $rental->end_date }}" disabled readonly>
                            {{ date('d/m/Y', strtotime($rental->end_date)) }}</option>
                    </select>
                </div>
            </div>
            <div class="row g-3 mt-3">
                <div class="col mt-0">
                    <label for="observation" class="form-label fw-light">Observação</label>
                    <textarea rows="3" class="form-control bg-transparent text-white" id="observation" name="observation"
                        maxlength="1000">{{ $rental->observation ?? null }}</textarea>
                </div>
            </div>
            <div class="row g-3 mt-5 mb-5">
                <div class="col mt-0 text-center">
                    <input type="submit" value="Atualizar" class="btn btn-success btn-lg">
                </div>
            </div>
        </form>
        <!-- Modal listagem KM Diária-->
        <div class="modal fade" id="kmDiariaModal" tabindex="-1" aria-labelledby="kmDiariaModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background-color: #242424;">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="kmDiariaModalLabel">KM Diária</h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <small class="text-white">Histório de quilometragem diária</small>
                            </div>
                            <div class="col text-end"><button class="btn btn-primary btn-sm rounded-3 border-white fs-6"
                                    data-bs-toggle="modal" data-bs-target="#criacaokmDiariaModal">Novo anexo</button>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col" id="kmDiariaModalBody">...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal criação KM Diária-->
        <div class="modal fade" id="criacaokmDiariaModal" tabindex="-1" aria-labelledby="criacaokmDiariaModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background-color: #242424;">
                    <form action="{{ route('milleage.store', ['vehicle' => $rental->vehicle->id]) }}" method="post">
                        @csrf
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="criacaokmDiariaModalLabel">Anexar KM Diária</h1>
                            <button type="button" class="btn-close btn-close-white" data-bs-toggle="modal"
                                data-bs-target="#kmDiariaModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col">
                                    <label for="actual_km" class="form-label fw-light">KM Atual<span
                                            class="text-danger"><strong>*</strong></span></label>
                                    <input type="number" class="form-control bg-transparent" id="actual_km" required
                                        name="actual_km" max="999999">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <label for="observation" class="form-label fw-light">Observação</label>
                                    <textarea class="form-control bg-transparent text-white" name="observation" id="observation" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-top-0">
                            <button type="submit" class="btn btn-success w-100 btn-lg">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('options-button')
    <div class="container text-end pe-4">
        <div class="btn-group dropup-center dropup">
            <button type="button" class="btn btn-light fs-1 py-0 rounded-3" data-bs-toggle="dropdown"
                aria-expanded="false" style="height: 44px;">
                <div class="pt-1 rounded-2 bg-black" style="width: 20px;"></div>
                <div class="pt-1 rounded-2 bg-black mt-1" style="width: 20px;"></div>
                <div class="pt-1 rounded-2 bg-black mt-1" style="width: 20px;"></div>
            </button>
            <ul class="dropdown-menu p-2 text-center shadow mb-1" style="width: auto; min-width: 70px;">
                <li>
                    <a href="#" class="d-block text-decoration-none text-black">
                        <img src="{{ asset('assets/svg/multa.svg') }}" alt="Icon 1" class="img-fluid mx-auto"
                            style="width: 30px; height: auto;">
                        <small class="d-block" style="font-size: 12px;">Multas</small>
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a href="#" class="d-block text-decoration-none text-black">
                        <img src="{{ asset('assets/svg/chave-inglesa.svg') }}" alt="Icon 2" class="img-fluid mx-auto"
                            style="width: 30px; height: auto;">
                        <small class="d-block" style="font-size: 12px;">Manut.</small>
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a href="#" class="d-block text-decoration-none text-black">
                        <span class="fs-2 text-black"><strong>$</strong></span>
                        <small class="d-block" style="font-size: 12px;">Finanças</small>
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a href="#" class="d-block text-decoration-none text-black" data-bs-toggle="modal"
                        data-bs-target="#kmDiariaModal" onclick="fetchApiData()">
                        <span class="fs-2 text-black"><strong>KM</strong></span>
                        <small class="d-block" style="font-size: 12px;">KM Diária</small>
                    </a>
                </li>
            </ul>
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

        let mileageHistoryViewed = false;

        let currentPage = 1; // Página inicial

        function fetchApiData(page = 1) {
            const apiUrl =
                `/km-diaria/{{ $rental->vehicle->id }}/{{ $rental->id }}?page=${page}`; // Adiciona o número da página

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    const resultsContainer = document.getElementById('kmDiariaModalBody');
                    if (data && data.data.length > 0) {
                        let html = '<ul style="list-style: none; padding: 0;">';
                        data.data.forEach((item) => {
                            const formattedDate = new Date(item.created_at).toLocaleDateString('pt-BR');
                            const collapseId = `collapse-${item.count}`;
                            html += `
                        <li class="pb-2" style="margin-bottom: 8px; border-bottom: 1px solid white;" data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="false" aria-controls="${collapseId}">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>${item.count}. ${formattedDate}</span>
                                <span class="badge rounded-3 fs-6" style="border: 1px solid white;">${item.actual_km}</span>
                            </div>
                            <div class="collapse mt-2" id="${collapseId}">
                                <div class="card card-body" style="background-color: #343a40; color: white;">
                                    <p><strong>Observação:</strong> ${item.observation ?? 'Sem observações.'}</p>
                                    <form method="POST" action="/km-diaria/${item.id}" onsubmit="return confirm('Confirma deletar o anexo de ${formattedDate}?');">
                                        @method('DELETE')
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    `;

                        });
                        html += '</ul>';

                        // Adiciona controles de paginação corrigidos
                        html += `
                    <div class="pagination d-flex justify-content-between align-items-center mt-3">
                        <button 
                            class="btn btn-sm btn-secondary" 
                            ${data.current_page > 1 ? '' : 'disabled'} 
                            onclick="fetchApiData(${data.current_page - 1})">
                            Anterior
                        </button>
                        <span>Página ${data.current_page} de ${data.last_page}</span>
                        <button 
                            class="btn btn-sm btn-secondary" 
                            ${data.current_page < data.last_page ? '' : 'disabled'} 
                            onclick="fetchApiData(${data.current_page + 1})">
                            Próxima
                        </button>
                    </div>
                `;

                        resultsContainer.innerHTML = html;
                        currentPage = page; // Atualiza a página atual
                    } else {
                        resultsContainer.innerHTML = 'Nenhum dado encontrado.';
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar dados:', error);
                    const resultsContainer = document.getElementById('kmDiariaModalBody');
                    resultsContainer.innerHTML = 'Erro ao carregar os dados.';
                });
        }
    </script>
@endsection
