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
            height: calc(100dvh - 60px - 70px);
        }

        li a {
            cursor: pointer;
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
        <form method="POST" action="{{ route('rental.patch', ['rental' => $rental->id]) }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="row g-0">
                <div class="col text-center">
                    <h1 style="font-size: 20px;">Visualizando Locação</h1>
                    @if ($rental->stop_date)
                        <small class="text-danger">Finalizada antes do tempo</small>
                    @elseif($rental->finished_at)
                        <small class="text-danger">Finalizada</small>
                    @else
                        <small class="text-success">Em andamento</small>
                    @endif
                </div>
            </div>
            <div class="row mt-4 g-3">
                <div class="col mt-0 position-relative">
                    @if ($rental->photo)
                        <!-- Imagem com evento de clique para abrir o modal -->
                        <div class="position-absolute end-0 me-2" style="top: -2%">
                            <img src="{{ route('photo.show', ['rental' => $rental->id]) }}" alt="Imagem da Locação"
                                style="max-width: 80px; max-height: 80px; cursor: pointer;" data-bs-toggle="modal"
                                data-bs-target="#imageModal">
                        </div>
                    @endif
                    <div><small><strong>Dados sobre o locador:</strong></small></div>
                    <div class="mt-3">
                        <label for="landlord_name" class="form-label fw-light">Nome completo<span
                                class="text-danger"><strong>*</strong></span></label>
                        @error('landlord_name')
                            <input @disabled($rental->finished_at) type="text" class="form-control bg-transparent border-danger"
                                id="landlord_name" value="{{ old('landlord_name') }}" required name="landlord_name">
                            <small class="very-small text-danger">Digite o nome</small>
                        @else
                            <input @disabled($rental->finished_at) type="text" class="form-control bg-transparent"
                                id="landlord_name" value="{{ $rental->landlord_name ?? null }}" required name="landlord_name">
                        @enderror
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <label for="landlord_cpf" class="form-label fw-light">CPF<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('landlord_cpf')
                                <input @disabled($rental->finished_at) type="text"
                                    class="form-control bg-transparent border-danger" id="landlord_cpf"
                                    value="{{ old('landlord_cpf') }}" required name="landlord_cpf" maxlength="11">
                                <small class="very-small text-danger">CPF com 11 dígitos</small>
                            @else
                                <input @disabled($rental->finished_at) type="text" class="form-control bg-transparent"
                                    id="landlord_cpf" value="{{ $rental->landlord_cpf ?? null }}" required name="landlord_cpf"
                                    maxlength="11">
                            @enderror
                        </div>
                        <div class="col mt-0">
                            <label for="driver_license_number" class="form-label fw-light">Número da CNH<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('driver_license_number')
                                <input @disabled($rental->finished_at) type="text"
                                    class="form-control bg-transparent border-danger" id="driver_license_number"
                                    value="{{ old('driver_license_number') }}" required name="driver_license_number">
                                <small class="very-small text-danger">Apenas números</small>
                            @else
                                <input @disabled($rental->finished_at) type="text" class="form-control bg-transparent"
                                    id="driver_license_number" value="{{ $rental->driver_license_number ?? null }}" required
                                    name="driver_license_number">
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <label for="driver_license_issue_date" class="form-label fw-light">Data da Emissão da CNH<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('driver_license_issue_date')
                                <input @disabled($rental->finished_at) type="date"
                                    class="form-control bg-transparent border-danger" id="driver_license_issue_date"
                                    value="{{ old('driver_license_issue_date') }}" required name="driver_license_issue_date">
                                <small class="very-small text-danger">Data válida</small>
                            @else
                                <input @disabled($rental->finished_at) type="date" class="form-control bg-transparent"
                                    id="driver_license_issue_date" value="{{ $rental->driver_license_issue_date ?? null }}"
                                    required name="driver_license_issue_date">
                            @enderror
                        </div>
                        <div class="col mt-0">
                            <label for="birth_date" class="form-label fw-light">Data de Nascimento<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('birth_date')
                                <input @disabled($rental->finished_at) type="date"
                                    class="form-control bg-transparent border-danger" id="birth_date"
                                    value="{{ old('birth_date') }}" required name="birth_date">
                                <small class="very-small text-danger">Data válida</small>
                            @else
                                <input @disabled($rental->finished_at) type="date" class="form-control bg-transparent"
                                    id="birth_date" value="{{ $rental->birth_date ?? null }}" required name="birth_date">
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-4 mt-0">
                            <label for="zip_code" class="form-label fw-light">CEP<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('zip_code')
                                <input @disabled($rental->finished_at) type="text"
                                    class="form-control bg-transparent border-danger" id="zip_code"
                                    value="{{ old('zip_code') }}" required name="zip_code" maxlength="8">
                                <small class="very-small text-danger">8 dígitos</small>
                            @else
                                <input @disabled($rental->finished_at) type="text" class="form-control bg-transparent"
                                    id="zip_code" value="{{ $rental->zip_code ?? null }}" required name="zip_code"
                                    maxlength="8">
                            @enderror
                        </div>
                        <div class="col-8 mt-0">
                            <label for="street" class="form-label fw-light">Rua<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('street')
                                <input @disabled($rental->finished_at) type="text"
                                    class="form-control bg-transparent border-danger" id="street"
                                    value="{{ old('street') }}" required name="street">
                                <small class="very-small text-danger">Nome da rua</small>
                            @else
                                <input @disabled($rental->finished_at) type="text" class="form-control bg-transparent"
                                    id="street" value="{{ $rental->street ?? null }}" required name="street">
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
                                <input @disabled($rental->finished_at) type="text"
                                    class="form-control bg-transparent border-danger" id="city"
                                    value="{{ old('city') }}" required name="city">
                                <small class="very-small text-danger">Digite a cidade</small>
                            @else
                                <input @disabled($rental->finished_at) type="text" class="form-control bg-transparent"
                                    id="city" value="{{ $rental->city ?? null }}" required name="city">
                            @enderror
                        </div>
                        <div class="col-5 mt-0">
                            <label for="neighborhood" class="form-label fw-light">Bairro<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('neighborhood')
                                <input @disabled($rental->finished_at) type="text"
                                    class="form-control bg-transparent border-danger" id="neighborhood"
                                    value="{{ old('neighborhood') }}" required name="neighborhood">
                                <small class="very-small text-danger">Digite o bairro</small>
                            @else
                                <input @disabled($rental->finished_at) type="text" class="form-control bg-transparent"
                                    id="neighborhood" value="{{ $rental->neighborhood ?? null }}" required
                                    name="neighborhood">
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-3 mt-0">
                            <label for="house_number" class="form-label fw-light">Número</label>
                            @error('house_number')
                                <input @disabled($rental->finished_at) type="text"
                                    class="form-control bg-transparent border-danger" id="house_number"
                                    value="{{ old('house_number') }}" name="house_number">
                                <small class="very-small text-danger">Apenas números</small>
                            @else
                                <input @disabled($rental->finished_at) type="text" class="form-control bg-transparent"
                                    id="house_number" value="{{ $rental->house_number ?? null }}" name="house_number">
                            @enderror
                        </div>
                        <div class="col mt-0">
                            <label for="complement" class="form-label fw-light">Complemento</label>
                            @error('complement')
                                <input @disabled($rental->finished_at) type="text"
                                    class="form-control bg-transparent border-danger" id="complement"
                                    value="{{ old('complement') }}" name="complement">
                                <small class="very-small text-danger">Digite o complemento</small>
                            @else
                                <input @disabled($rental->finished_at) type="text" class="form-control bg-transparent"
                                    id="complement" value="{{ $rental->complement ?? null }}" name="complement">
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col mt-0">
                            <label for="phone_1" class="form-label fw-light">Telefone 1<span
                                    class="text-danger"><strong>*</strong></span></label>
                            @error('phone_1')
                                <input @disabled($rental->finished_at) type="text"
                                    class="form-control bg-transparent border-danger" id="phone_1"
                                    value="{{ old('phone_1') }}" required name="phone_1" maxlength="11">
                                <small class="very-small text-danger">Telefone com 11 dígitos</small>
                            @else
                                <input @disabled($rental->finished_at) type="text" class="form-control bg-transparent"
                                    id="phone_1" value="{{ $rental->phone_1 ?? null }}" required name="phone_1"
                                    maxlength="11">
                            @enderror
                        </div>
                        <div class="col mt-0">
                            <label for="phone_2" class="form-label fw-light">Telefone 2</label>
                            @error('phone_2')
                                <input @disabled($rental->finished_at) type="text"
                                    class="form-control bg-transparent border-danger" id="phone_2"
                                    value="{{ old('phone_2') }}" name="phone_2" maxlength="11">
                                <small class="very-small text-danger">Telefone com 11 dígitos</small>
                            @else
                                <input @disabled($rental->finished_at) type="text" class="form-control bg-transparent"
                                    id="phone_2" value="{{ $rental->phone_2 ?? null }}" name="phone_2" maxlength="11">
                            @enderror
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="mother_name" class="form-label fw-light">Nome da mãe</label>
                        @error('mother_name')
                            <input @disabled($rental->finished_at) type="text"
                                class="form-control bg-transparent border-danger" id="mother_name"
                                value="{{ old('mother_name') }}" name="mother_name" maxlength="150">
                            <small class="very-small text-danger">Escreva o nome da mãe</small>
                        @else
                            <input @disabled($rental->finished_at) type="text" class="form-control bg-transparent"
                                id="mother_name" value="{{ $rental->mother_name ?? null }}" name="mother_name"
                                maxlength="150">
                        @enderror
                    </div>
                    <div class="mt-3">
                        <label for="father_name" class="form-label fw-light">Nome do pai</label>
                        @error('father_name')
                            <input @disabled($rental->finished_at) type="text"
                                class="form-control bg-transparent border-danger" id="father_name"
                                value="{{ old('father_name') }}" name="father_name" maxlength="150">
                            <small class="very-small text-danger">Escreva o nome do pai</small>
                        @else
                            <input @disabled($rental->finished_at) type="text" class="form-control bg-transparent"
                                id="father_name" value="{{ $rental->father_name ?? null }}" name="father_name"
                                maxlength="150">
                        @enderror
                    </div>
                    <div class="mt-3">
                        <label for="photo" class="form-label fw-light">Foto do locador</label>
                        <input @disabled($rental->finished_at)
                            class="form-control bg-transparent @error('photo') border-danger @enderror text-white"
                            type="file" id="photo" accept=".png, .jpg, .jpeg" value="{{ $rental->photo }}"
                            name="photo">
                        @error('photo')
                            <small class="very-small text-danger">Arquivo PNG</small>
                        @enderror
                    </div>
                    <div class="mt-4"><small><strong>Dados sobre o veículo:</strong></small></div>
                    <div class="row g-3 mt-3 align-items-center">
                        <div class="col-auto text-center mt-0">
                            <div class="vehicle rounded-3 py-2 px-3">
                                <small>{{ $rental->vehicle->brand }} {{ $rental->vehicle->model }}</small>
                                <br><small>{{ $rental->vehicle->license_plate }}</small>
                                <br><small>KM: {{ $rental->vehicle->actual_km }}</small>
                                <br><small>ANO: {{ $rental->vehicle->year }}</small>
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <small><strong>Última revisão:</strong></small>
                            @if ($rental->vehicle->latestRevision)
                                <small>{{ date('d/m/Y', strtotime($rental->vehicle->latestRevision->date)) }} |
                                    {{ $rental->vehicle->latestRevision->actual_km }}</small>
                            @else
                                <small>N/A</small>
                            @endif
                            @php
                                $kmRemaining = $rental->vehicle->next_revision - $rental->vehicle->actual_km;
                            @endphp
                            <br><small><strong>Próxima revisão:</strong> <span
                                    class="@if ($kmRemaining < 0) text-danger @else text-success @endif">{{ $kmRemaining }}</span>
                                KM.</small>
                            <br><small><strong>Última troca de óleo:</strong></small>
                            @if ($rental->vehicle->latestOilChange)
                                <small>{{ date('d/m/Y', strtotime($rental->vehicle->latestOilChange->date)) }}.</small>
                            @else
                                <small>N/A</small>
                            @endif
                            <br><small><strong>Próxima troca de óleo:</strong></small>
                            @php
                                $oilKmRemaining = $rental->vehicle->next_oil_change - $rental->vehicle->actual_km;
                                $needChangeOil = $rental->vehicle->next_oil_change <= $rental->vehicle->actual_km;
                            @endphp
                            <small>
                                <span
                                    class="@if ($needChangeOil) text-danger @else text-success @endif">{{ $rental->vehicle->next_oil_change }}
                                    KM (faltam {{ $oilKmRemaining }} KM).</span>
                            </small>
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
                        <input @disabled($rental->finished_at) type="text" class="form-control bg-transparent border-danger"
                            id="cost" value="{{ old('cost') }}" required name="cost" maxlength="6">
                        <small class="very-small text-danger">Apenas números</small>
                    @else
                        <input @disabled($rental->finished_at) type="text" class="form-control bg-transparent"
                            id="cost" value="{{ $rental->cost ?? null }}" required name="cost" maxlength="6">
                    @enderror
                </div>
                <div class="col mt-0">
                    <label for="deposit" class="form-label fw-light">Caução<span
                            class="text-danger"><strong>*</strong></span></label>
                    @error('deposit')
                        <input @disabled($rental->finished_at) type="text" class="form-control bg-transparent border-danger"
                            id="deposit" value="{{ old('deposit') }}" required name="deposit" maxlength="6" disabled
                            readonly>
                        <small class="very-small text-danger">Apenas números</small>
                    @else
                        <input @disabled($rental->finished_at) type="text" class="form-control bg-transparent"
                            id="deposit" value="{{ $rental->deposit ?? null }}" required name="deposit" maxlength="6"
                            disabled readonly>
                    @enderror
                </div>
                <div class="col mt-0">
                    <label for="payment_day" class="form-label fw-light">Dia pagamento<span
                            class="text-danger"><strong>*</strong></span></label>
                    @error('payment_day')
                        <input @disabled($rental->finished_at) type="text" class="form-control bg-transparent border-danger"
                            id="payment_day" value="{{ old('payment_day') }}" required name="payment_day" maxlength="6"
                            disabled readonly>
                        <small class="very-small text-danger">Apenas números</small>
                    @else
                        <input @disabled($rental->finished_at) type="text" class="form-control bg-transparent"
                            id="payment_day" value="{{ $rental->payment_day ?? null }}" required name="payment_day"
                            maxlength="6" disabled readonly>
                    @enderror
                </div>
            </div>
            <div class="row g-3 mt-3">
                <div class="col mt-0">
                    <label for="start_date" class="form-label fw-light">Início do contrato<span
                            class="text-danger"><strong>*</strong></span></label>
                    <input @disabled($rental->finished_at) type="date" class="form-control bg-transparent"
                        id="start_date" value="{{ $rental->start_date }}" required name="start_date" disabled readonly>
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
                    <textarea @disabled($rental->finished_at) rows="6" class="form-control bg-transparent text-white"
                        id="observation" name="observation" maxlength="1000">{{ $rental->observation ?? null }}</textarea>
                </div>
            </div>
            <div class="row g-3 mt-5 mb-5 align-items-center">
                <div class="col mt-0 text-center">
                    <input @disabled($rental->finished_at) type="submit" value="Atualizar" class="btn btn-success btn-lg">
                </div>
            </div>
        </form>
        <div class="row mt-4">
            <div class="col-auto">
                <button data-bs-toggle="modal" data-bs-target="#finishRental" type="submit"
                    class="btn-sm btn btn-danger" @disabled($rental->finished_at)><svg xmlns="http://www.w3.org/2000/svg"
                        width="16" height="16" fill="currentColor" class="bi bi-stop-fill mb-1"
                        viewBox="0 0 16 16">
                        <path
                            d="M5 3.5h6A1.5 1.5 0 0 1 12.5 5v6a1.5 1.5 0 0 1-1.5 1.5H5A1.5 1.5 0 0 1 3.5 11V5A1.5 1.5 0 0 1 5 3.5" />
                    </svg>Finalizar
                </button>
            </div>
            <div class="col text-end">
                <div class="row justify-content-end">
                    <div class="col mt-0">
                        @if ($previousRental)
                            <a href="{{ route('rental.show', ['rental' => $previousRental]) }}"
                                class="btn btn-outline-info btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
                                </svg> Anterior
                            </a>
                        @endif

                        @if ($nextRental)
                            <a href="{{ route('rental.show', ['rental' => $nextRental]) }}"
                                class="ms-3 btn btn-outline-info btn-sm">
                                Próximo
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16"
                                    style="transform: rotate(180deg)">
                                    <path fill-rule="evenodd"
                                        d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
                                </svg>
                            </a>
                        @endif

                    </div>
                </div>
            </div>
        </div>
        <!-- Modal finalizar-->
        <div aria-modal="true" class="modal fade" id="finishRental" tabindex="-1" aria-labelledby="finishRentalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background-color: rgb(28 28 28)">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="finishRentalLabel">Finalizar aluguel</h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form action="{{ route('rentals.finish', ['rental' => $rental->id]) }}" method="POST"
                        id="finishRentalForm">
                        @csrf
                        <div class="modal-body">
                            <div class="row mt-3">
                                <div class="col">
                                    <label class="form-label fw-light">Data finalização<span
                                            class="text-danger"><strong>*</strong></label>
                                    <input @disabled($rental->finished_at) type="date"
                                        class="form-control bg-transparent" name="stop_date"
                                        max="{{ now()->toDateString() }}"
                                        value="{{ old('date', now()->toDateString()) }}" required>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <label class="form-label fw-light">Existe multa por quebra de contrato?<span
                                            class="text-danger"><strong>*</strong></label>
                                    <select class="form-control bg-transparent" name="contract_break_fee"
                                        id="contract_break_fee" required>
                                        <option class="text-black" value="0">Não</option>
                                        <option class="text-black" value="1">Sim</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3" id="contractBreakFeeValueContainer" style="display: none;">
                                <div class="col">
                                    <label class="form-label fw-light">Valor da multa<span
                                            class="text-danger"><strong>*</strong></label>
                                    <input @disabled($rental->finished_at) type="number"
                                        class="form-control bg-transparent" name="contract_break_fee_value"
                                        id="contract_break_fee_value" min="0" step="0.01">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <label class="form-label fw-light">Houve dano?<span
                                            class="text-danger"><strong>*</strong></label>
                                    <select class="form-control bg-transparent" name="damage_fee" id="damage_fee"
                                        required>
                                        <option class="text-black" value="0">Não</option>
                                        <option class="text-black" value="1">Sim</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3" id="damageFeeValueContainer" style="display: none;">
                                <div class="col">
                                    <label class="form-label fw-light">Valor do dano<span
                                            class="text-danger"><strong>*</strong></label>
                                    <input @disabled($rental->finished_at) type="number"
                                        class="form-control bg-transparent" name="damage_fee_value" id="damage_fee_value"
                                        min="0" step="0.01">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <label class="form-label fw-light">Observações</label>
                                    <textarea class="form-control bg-transparent text-white" name="finish_observation"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Finalizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal listagem KM Diária-->
        <div aria-modal="true" class="modal fade" id="kmDiariaModal" tabindex="-1"
            aria-labelledby="kmDiariaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background-color: rgb(28 28 28)">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="kmDiariaModalLabel">KM Diária</h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <small class="text-white"><strong>Histórico de quilometragem diária:</strong></small>
                            </div>
                            <div class="col text-end"><button class="btn btn-primary btn-sm rounded-3 border-white fs-6"
                                    @disabled($rental->finished_at) data-bs-toggle="modal"
                                    data-bs-target="#criacaokmDiariaModal">Novo anexo</button>
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
        <div aria-modal="true" class="modal fade" id="criacaokmDiariaModal" tabindex="-1"
            aria-labelledby="criacaokmDiariaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background-color: rgb(28 28 28)">
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
                                    <label for="date_mileage" class="form-label fw-light">
                                        Data<span class="text-danger"><strong>*</strong></span>
                                    </label>
                                    <input @disabled($rental->finished_at) type="date"
                                        class="form-control bg-transparent" id="date_mileage" required name="date"
                                        max="{{ now()->toDateString() }}"
                                        value="{{ old('date', now()->toDateString()) }}">

                                </div>
                                <div class="col">
                                    <label for="date_mileage" class="form-label fw-light">KM Atual<span
                                            class="text-danger"><strong>*</strong></span></label>
                                    <input @disabled($rental->finished_at) type="number"
                                        class="form-control bg-transparent" id="date_mileage" required name="actual_km"
                                        min="{{ $rental->vehicle->actual_km }}" max="999999">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <label for="observation_mileage" class="form-label fw-light">Observação</label>
                                    <textarea class="form-control bg-transparent text-white" name="observation" id="observation_mileage" rows="3"></textarea>
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
        <!-- Modal listagem Manutenção-->
        <div aria-modal="true" class="modal fade" id="revisaoModal" tabindex="-1" aria-labelledby="revisaoModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background-color: rgb(28 28 28)">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="revisaoModalLabel">Revisão</h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <small class="text-white"><strong>Histórico de revisões:</strong></small>
                            </div>
                            <div class="col text-end"><button class="btn btn-primary btn-sm rounded-3 border-white fs-6"
                                    @disabled($rental->finished_at) data-bs-toggle="modal"
                                    data-bs-target="#criacaoRevisaoModal">Nova
                                    revisão</button>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col" id="revisaoModalBody">...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div aria-modal="true" class="modal fade" id="trocaDeOleoModal" tabindex="-1"
            aria-labelledby="trocaDeOleoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background-color: rgb(28 28 28)">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="trocaDeOleoModalLabel">Nova Troca de óleo</h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <small class="text-white"><strong>Histórico de trocas de óleo:</strong></small>
                            </div>
                            <div class="col text-end">
                                <button class="btn btn-primary btn-sm rounded-3 border-white fs-6"
                                    @disabled($rental->finished_at) data-bs-toggle="modal"
                                    data-bs-target="#criacaoTrocaDeOleoModal">Nova troca de óleo</button>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col" id="trocaDeOleoModalBody">...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal criação Manutenção-->
        <div aria-modal="true" class="modal fade" id="criacaoRevisaoModal" tabindex="-1"
            aria-labelledby="criacaorevisaoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background-color: rgb(28 28 28)">
                    <form action="{{ route('revision.show', ['vehicle' => $rental->vehicle->id]) }}" method="post">
                        @csrf
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="criacaorevisaoModalLabel">Nova revisão</h1>
                            <button type="button" class="btn-close btn-close-white" data-bs-toggle="modal"
                                data-bs-target="#revisaoModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col">
                                    <label for="cost_maintenance" class="form-label fw-light">Valor<span
                                            class="text-danger"><strong>*</strong></span></label>
                                    <input @disabled($rental->finished_at) type="number"
                                        class="form-control bg-transparent" id="cost_maintenance" required name="cost"
                                        max="999999">
                                </div>
                                <div class="col">
                                    <label for="date_maintenance" class="form-label fw-light">Data<span
                                            class="text-danger"><strong>*</strong></span></label>
                                    <input @disabled($rental->finished_at) type="date"
                                        class="form-control bg-transparent" id="date_maintenance" required name="date"
                                        max="999999">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <label for="actual_km_maintenance" class="form-label fw-light">KM Atual<span
                                            class="text-danger"><strong>*</strong></span></label>
                                    <input @disabled($rental->finished_at) type="number"
                                        class="form-control bg-transparent" id="actual_km_maintenance" required
                                        name="actual_km" min="{{ $rental->vehicle->actual_km }}" max="999999">
                                </div>
                                <div class="col">
                                    <label class="form-label fw-light">Houve troca de óleo?<span
                                            class="text-danger"><strong>*</strong></span></label>
                                    <br>
                                    <input @disabled($rental->finished_at) type="radio" class="btn-check"
                                        name="have_oil_change" id="success-outlined-oil" autocomplete="off"
                                        value="1">
                                    <label class="btn btn-sm btn-outline-success" for="success-outlined-oil">Sim</label>

                                    <input @disabled($rental->finished_at) type="radio" class="btn-check"
                                        name="have_oil_change" id="danger-outlined-oil" autocomplete="off"
                                        value="0" checked>
                                    <label class="btn btn-sm btn-outline-danger" for="danger-outlined-oil">Não</label>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <label for="observation_maintenance" class="form-label fw-light">Observação</label>
                                    <textarea class="form-control bg-transparent text-white" name="observation" id="observation_maintenance"
                                        rows="3"></textarea>
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
        <!-- Modal troca de óleo-->
        <div aria-modal="true" class="modal fade" id="criacaoTrocaDeOleoModal" tabindex="-1"
            aria-labelledby="criacaoTrocaDeOleoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background-color: rgb(28 28 28)">
                    <form action="{{ route('oil-change.store', ['vehicle' => $rental->vehicle->id]) }}" method="post">
                        @csrf
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="criacaoTrocaDeOleoModalLabel">Anexar Troca de Óleo</h1>
                            <button type="button" class="btn-close btn-close-white" data-bs-toggle="modal"
                                data-bs-target="#revisaoModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col">
                                    <label for="cost_oil_change" class="form-label fw-light">Valor<span
                                            class="text-danger"><strong>*</strong></span></label>
                                    <input @disabled($rental->finished_at) type="number"
                                        class="form-control bg-transparent" id="cost_oil_change" required name="cost"
                                        max="999999">
                                </div>
                                <div class="col">
                                    <label for="date_oil_change" class="form-label fw-light">Data<span
                                            class="text-danger"><strong>*</strong></span></label>
                                    <input @disabled($rental->finished_at) type="date"
                                        class="form-control bg-transparent" id="date_oil_change" required name="date">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <label for="actual_km_oil_change" class="form-label fw-light">KM Atual<span
                                            class="text-danger"><strong>*</strong></span></label>
                                    <input @disabled($rental->finished_at) type="number"
                                        class="form-control bg-transparent" id="actual_km_oil_change" required
                                        name="actual_km" min="{{ $rental->vehicle->actual_km }}" max="999999">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <label for="observation_oil_change" class="form-label fw-light">Observação</label>
                                    <textarea class="form-control bg-transparent text-white" name="observation" id="observation_oil_change"
                                        rows="3"></textarea>
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
        <!-- Modal listagem Finanças-->
        <div aria-modal="true" class="modal fade" id="financasModal" tabindex="-1"
            aria-labelledby="financasModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background-color: rgb(28 28 28)">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="financasModalLabel">Finanças</h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <small class="text-white"><strong>Histórico de financas:</strong></small>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col" id="financasModalBody">...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal listagem Multa-->
        <div aria-modal="true" class="modal fade" id="multaModal" tabindex="-1" aria-labelledby="multaModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background-color: rgb(28 28 28)">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="multaModalLabel">Multa</h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <small class="text-white"><strong>Histórico de multas:</strong></small>
                            </div>
                            <div class="col text-end"><button class="btn btn-primary btn-sm rounded-3 border-white fs-6"
                                    @disabled($rental->finished_at) data-bs-toggle="modal"
                                    data-bs-target="#criacaoMultaModal">Novo anexo</button>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col" id="multaModalBody">...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal criação Multa-->
        <div aria-modal="true" class="modal fade" id="criacaoMultaModal" tabindex="-1"
            aria-labelledby="criacaoMultaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background-color: rgb(28 28 28)">
                    <form action="{{ route('fine.store', ['vehicle' => $rental->vehicle->id]) }}" method="post">
                        @csrf
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="criacaoMultaModalLabel">Anexar Multa</h1>
                            <button type="button" class="btn-close btn-close-white" data-bs-toggle="modal"
                                data-bs-target="#multa"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col">
                                    <label for="date_fine" class="form-label fw-light">Data ocorrido<span
                                            class="text-danger"><strong>*</strong></span></label>
                                    <input @disabled($rental->finished_at) type="date"
                                        class="form-control bg-transparent" id="date_fine" required name="date">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <label for="cost_fine" class="form-label fw-light">Valor<span
                                            class="text-danger"><strong>*</strong></span></label>
                                    <input @disabled($rental->finished_at) type="number"
                                        class="form-control bg-transparent" id="cost_fine" required name="cost"
                                        max="999999">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <label class="form-label fw-light">Está paga?<span
                                            class="text-danger"><strong>*</strong></span></label>
                                    <br>
                                    <input @disabled($rental->finished_at) type="radio" class="btn-check" name="paid"
                                        id="success-outlined" autocomplete="off" value="1" checked>
                                    <label class="btn btn-sm btn-outline-success" for="success-outlined">Sim</label>

                                    <input @disabled($rental->finished_at) type="radio" class="btn-check" name="paid"
                                        id="danger-outlined" autocomplete="off" value="0">
                                    <label class="btn btn-sm btn-outline-danger" for="danger-outlined">Não</label>
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
        @if ($rental->photo)
            <!-- Modal imagem -->
            <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered bg-transparent">
                    <div class="modal-content bg-transparent">
                        <div class="modal-header border-0">
                            <button type="button" class="btn-close btn-close-white bg-white opacity-100"
                                data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img id="modalImage" src="{{ route('photo.show', ['rental' => $rental->id]) }}"
                                alt="Imagem da Locação" class="img-fluid">
                            <div class="mt-4">
                                <form action="{{ route('photo.delete', ['rental' => $rental->id]) }}"
                                    onsubmit="return confirm('Deseja deletar esta imagem?')" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Excluir foto</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@section('options-button')
    <div class="container text-end pe-4 menu-button">
        <div class="btn-group dropup-center dropup">
            <button type="button" class="btn btn-light fs-1 py-0 rounded-3" data-bs-toggle="dropdown"
                aria-expanded="false" style="height: 44px;">
                <div class="pt-1 rounded-2 bg-black" style="width: 20px;"></div>
                <div class="pt-1 rounded-2 bg-black mt-1" style="width: 20px;"></div>
                <div class="pt-1 rounded-2 bg-black mt-1" style="width: 20px;"></div>
            </button>
            <ul class="dropdown-menu p-2 text-center shadow mb-1" style="width: auto; min-width: 70px;">
                <li>
                    <a data-bs-toggle="modal" data-bs-target="#multaModal" onclick="fetchFineData()"
                        class="d-block text-decoration-none text-black">
                        <img src="{{ asset('assets/svg/multa.svg') }}" alt="Icon 1" class="img-fluid mx-auto"
                            style="width: 30px; height: auto;">
                        <small class="d-block" style="font-size: 12px;">Multas</small>
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a data-bs-toggle="modal" data-bs-target="#revisaoModal"
                        onclick="fetchMaintenanceData()" class="d-block text-decoration-none text-black">
                        <img src="{{ asset('assets/svg/revision.svg') }}" alt="Icon 2" class="img-fluid mx-auto"
                            style="width: 30px; height: auto;">
                        <small class="d-block" style="font-size: 12px;">Revisões</small>
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a data-bs-toggle="modal" data-bs-target="#trocaDeOleoModal"
                        onclick="fetchOilChangeData()" class="d-block text-decoration-none text-black">
                        <img src="{{ asset('assets/svg/oil.svg') }}" alt="Icon 2" class="img-fluid mx-auto"
                            style="width: 30px; height: auto;">
                        <small class="d-block" style="font-size: 12px;">Tr. óleo</small>
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a data-bs-toggle="modal" data-bs-target="#financasModal" onclick="fetchPaymentData()"
                        class="d-block text-decoration-none text-black">
                        <span class="fs-2 text-black"><strong>$</strong></span>
                        <small class="d-block" style="font-size: 12px;">Finanças</small>
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a class="d-block text-decoration-none text-black" data-bs-toggle="modal"
                        data-bs-target="#kmDiariaModal" onclick="fetchMileageData()">
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
        var isRentalFinished = @json($rental->finished_at !== null);
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const zipCodeInput = document.getElementById('zip_code');
            const streetInput = document.getElementById('street');
            const stateSelect = document.getElementById('state');
            const cityInput = document.getElementById('city');
            const neighborhoodInput = document.getElementById('neighborhood');
            const complementInput = document.getElementById('complement');

            const isRentalFinished = {{ !$rental->finished_at ? 'true' : 'false' }};

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

            // Seleciona o formulário de finalização do aluguel
            const form = document.getElementById("finishRentalForm");

            form.addEventListener("submit", function(event) {
                event.preventDefault(); // Previne o envio automático

                // Captura os valores dos campos para mostrar no alerta de confirmação
                let stopDate = document.querySelector("input[name='stop_date']").value;
                let contractBreakFee = document.getElementById("contract_break_fee").value;
                let contractBreakFeeValue = document.getElementById("contract_break_fee_value").value ||
                    "0.00";
                let damageFee = document.getElementById("damage_fee").value;
                let damageFeeValue = document.getElementById("damage_fee_value").value || "0.00";
                let observation = document.querySelector("textarea[name='finish_observation']").value ||
                    "Nenhuma";

                // Converte os valores de "Sim" e "Não"
                contractBreakFee = contractBreakFee == "1" ? "Sim" : "Não";
                damageFee = damageFee == "1" ? "Sim" : "Não";

                // Mensagem de confirmação
                let confirmationMessage = `Deseja realmente finalizar o aluguel? Esta ação é irreversível.`;

                // Exibe a confirmação
                if (confirm(confirmationMessage.replace(/\*\*/g, ""))) {
                    form.submit(); // Envia o formulário se o usuário confirmar
                }
            });

            // Seleciona os elementos uma única vez para evitar re-declaração
            const contractBreakFeeSelect = document.getElementById("contract_break_fee");
            const contractBreakFeeValueContainer = document.getElementById("contractBreakFeeValueContainer");
            const contractBreakFeeValueInput = document.getElementById("contract_break_fee_value");

            const damageFeeSelect = document.getElementById("damage_fee");
            const damageFeeValueContainer = document.getElementById("damageFeeValueContainer");
            const damageFeeValueInput = document.getElementById("damage_fee_value");

            // Exibir campos de valores somente se necessário
            contractBreakFeeSelect.addEventListener("change", function() {
                if (this.value == "1") {
                    contractBreakFeeValueContainer.style.display = "block";
                    contractBreakFeeValueInput.setAttribute("required", "true");
                } else {
                    contractBreakFeeValueContainer.style.display = "none";
                    contractBreakFeeValueInput.removeAttribute("required");
                    contractBreakFeeValueInput.value = "";
                }
            });

            damageFeeSelect.addEventListener("change", function() {
                if (this.value == "1") {
                    damageFeeValueContainer.style.display = "block";
                    damageFeeValueInput.setAttribute("required", "true");
                } else {
                    damageFeeValueContainer.style.display = "none";
                    damageFeeValueInput.removeAttribute("required");
                    damageFeeValueInput.value = "";
                }
            });

        });

        let mileageHistoryViewed = false;

        let currentPage = 1; // Página inicial

        function formattedDate(dateString) {
            const date = new Date(dateString);
            const day = String(date.getUTCDate()).padStart(2, '0');
            const month = String(date.getUTCMonth() + 1).padStart(2, '0'); // Mês começa em 0
            const year = date.getUTCFullYear();
            return `${day}/${month}/${year}`;
        }

        function updateLatestUpdate(createdAt) {
            const date = new Date(createdAt);
            const today = new Date();
            const yesterday = new Date();
            yesterday.setDate(today.getDate() - 1);

            // Zerando horas para comparação precisa
            today.setHours(0, 0, 0, 0);
            yesterday.setHours(0, 0, 0, 0);
            date.setHours(0, 0, 0, 0);
            const textContentLatestUpdate = latestUpdate.textContent;
            if (date.getTime() === today.getTime()) {
                latestUpdate.textContent = textContentLatestUpdate + 'Hoje';
                latestUpdate.classList.add('text-success');
            } else if (date.getTime() === yesterday.getTime()) {
                latestUpdate.textContent = textContentLatestUpdate + 'Ontem';
                latestUpdate.classList.add('text-warning');
            } else {
                latestUpdate.textContent = textContentLatestUpdate + formattedDate(createdAt);
                latestUpdate.classList.add('text-danger');
            }

        }

        function fetchMileageData(page = 1) {
            const apiUrl = `/km-diaria/{{ $rental->vehicle->id }}/{{ $rental->id }}?page=${page}`;
            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    const resultsContainer = document.getElementById('kmDiariaModalBody');
                    if (data && data.data.length > 0) {
                        let html = '';
                        data.data.forEach((item) => {
                            const collapseId = `collapse-${item.count}`;
                            const observationId = `observation-${item.id}`;
                            const editBtnId = `edit-btn-${item.id}`;
                            const saveBtnId = `save-btn-${item.id}`;
                            const cancelBtnId = `cancel-btn-${item.id}`;
                            const textareaId = `textarea-${item.id}`;

                            html += `
                        <div class="row mb-3" style="border-bottom: 1px solid white; padding-bottom: 8px;">
                            <div class="col-12 d-flex justify-content-between align-items-center" 
                                data-bs-toggle="collapse" data-bs-target="#${collapseId}" 
                                aria-expanded="false" aria-controls="${collapseId}">
                                <span>${item.count}. ${formattedDate(item.date)} 
                                    ${item.observation ? '<strong class="text-danger">*</strong>' : ''}
                                </span>
                                <span class="badge rounded-3 fs-6" style="border: 1px solid white;">${item.actual_km}</span>
                            </div>
                        </div>

                        <!-- Collapse correspondente -->
                        <div class="collapse mb-3" id="${collapseId}">
                            <div class="card card-body" style="background-color: #343a40; color: white;">
                                <div class="row">
                                    <div class="col d-flex align-items-center">
                                        <p><strong>Observação:</strong> 
                                            <span id="${observationId}">${item.observation ?? 'Sem observações.'}</span>
                                            <button ${isRentalFinished ? 'disabled' : ''} class="btn btn-sm btn-warning ms-2" id="${editBtnId}" onclick="enableEdit('${item.id}')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
  <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
</svg>
                                            </button>
                                        </p>
                                    </div>
                                </div>
                                <div class="row d-none" id="edit-container-${item.id}">
                                    <div class="col mb-4">
                                        <textarea class="form-control mb-2 bg-transparent border-white border-1 text-white" id="${textareaId}">${item.observation ?? ''}</textarea>
                                        <button class="btn btn-outline-success btn-sm" id="${saveBtnId}" onclick="saveObservation('${item.id}')">Atualizar observação</button>
                                        <button class="btn btn-outline-danger btn-sm" id="${cancelBtnId}" onclick="disableEdit('${item.id}')">Cancelar</button>
                                    </div>
                                </div>
                                <form method="POST" action="/km-diaria/${item.id}" onsubmit="return confirm('Confirma deletar o anexo de ${formattedDate(item.created_at)}?');">
                                    @method('DELETE')
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button ${isRentalFinished ? 'disabled' : ''} type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                </form>
                            </div>
                        </div>
                    `;
                        });

                        // Adiciona controles de paginação
                        html += `
                    <div class="pagination d-flex justify-content-between align-items-center mt-3">
                        <button 
                            class="btn btn-sm btn-secondary" 
                            ${data.current_page > 1 ? '' : 'disabled'} 
                            onclick="fetchMileageData(${data.current_page - 1})">
                            Anterior
                        </button>
                        <span>Página ${data.current_page} de ${data.last_page}</span>
                        <button 
                            class="btn btn-sm btn-secondary" 
                            ${data.current_page < data.last_page ? '' : 'disabled'} 
                            onclick="fetchMileageData(${data.current_page + 1})">
                            Próxima
                        </button>
                    </div>
                `;

                        resultsContainer.innerHTML = html;
                    } else {
                        resultsContainer.innerHTML = 'Nenhum dado encontrado.';
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar dados:', error);
                    resultsContainer.innerHTML = 'Erro ao carregar os dados.';
                });
        }

        // Função para habilitar edição da observação
        function enableEdit(itemId) {
            document.getElementById(`edit-container-${itemId}`).classList.remove('d-none');
        }

        function disableEdit(itemId) {
            document.getElementById(`edit-container-${itemId}`).classList.add('d-none');
        }

        // Função para salvar a observação editada
        function saveObservation(itemId) {
            const textarea = document.getElementById(`textarea-${itemId}`);
            const observation = textarea.value;

            fetch(`/km-diaria/${itemId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        observation: observation
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Observação atualizada!')
                        document.getElementById(`observation-${itemId}`).textContent = observation ||
                            'Sem observações.';
                        document.getElementById(`edit-container-${itemId}`).classList.add('d-none');
                    } else {
                        alert('Erro ao atualizar observação.');
                    }
                })
                .catch(error => {
                    console.error('Erro ao atualizar observação:', error);
                    alert('Erro ao atualizar observação.');
                });
        }


        function fetchMaintenanceData(page = 1) {
            const maintenanceApiUrl = `/revisao/{{ $rental->vehicle->id }}/{{ $rental->id }}?page=${page}`;

            fetch(maintenanceApiUrl)
                .then(response => response.json())
                .then(maintenanceData => {
                    updateModalSection('revisaoModalBody', maintenanceData, 'Manutenção');
                })
                .catch(error => {
                    console.error('Erro ao buscar dados de manutenção:', error);
                    document.getElementById('revisaoModalBody').innerHTML =
                        'Erro ao carregar os dados de manutenção.';
                });
        }

        function fetchOilChangeData(page = 1) {
            const oilChangeApiUrl = `/troca-de-oleo/{{ $rental->vehicle->id }}/{{ $rental->id }}?page=${page}`;

            fetch(oilChangeApiUrl)
                .then(response => response.json())
                .then(oilChangeData => {
                    updateModalSection('trocaDeOleoModalBody', oilChangeData, 'Troca de Óleo');
                })
                .catch(error => {
                    console.error('Erro ao buscar dados de troca de óleo:', error);
                    document.getElementById('trocaDeOleoModalBody').innerHTML =
                        'Erro ao carregar os dados de troca de óleo.';
                });
        }


        function updateModalSection(containerId, data, sectionTitle) {
            const resultsContainer = document.getElementById(containerId);

            if (data && data.data.length > 0) {
                let html = '';

                data.data.forEach((item) => {
                    const collapseId = `collapse-${item.id}`;

                    html +=
                        `
                <div class="row mb-3" style="border-bottom: 1px solid white; padding-bottom: 8px;">
                    <div class="col-12 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="false" aria-controls="${collapseId}">
                        <span>${item.count}. ${formattedDate(item.date)} ${item.observation ? '<strong class="text-danger">*</strong>' : ''}</span>
                        <span class="badge rounded-3 fs-6" style="border: 1px solid white;">R$ ${item.cost}</span>
                    </div>
                </div>

                <!-- Collapse correspondente -->
                <div class="collapse mb-3" id="${collapseId}">
                    <div class="card card-body" style="background-color: #343a40; color: white;">
                        <div class="row">
                            <div class="col">
                                <p><strong>Quilometragem da moto:</strong> ${item.actual_km ?? 'Sem observações.'}</p>`;
                    if (sectionTitle === 'Manutenção') {
                        html +=
                            `<p><strong>Houve troca de óleo:</strong> ${item.have_oil_change === 1 ? 'Sim' : 'Não'}</p>`;
                    }
                    // Exibição da observação e botão de editar
                    if (sectionTitle === 'Manutenção') {
                        html += `
                                <span><strong>Observação:</strong> <span id="observation-maintenance-${item.id}">${item.observation ?? 'Sem observações.'}</span></span>
                                <button class="btn btn-sm btn-warning ms-2" onclick="enableEditMaintenance('${item.id}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                      <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293z"/>
                                    </svg>
                                </button>
                `;
                    } else if (sectionTitle === 'Troca de Óleo') {
                        html += `
                                <span><strong>Observação:</strong> <span id="observation-oilchange-${item.id}">${item.observation ?? 'Sem observações.'}</span></span>
                                <button class="btn btn-sm btn-warning ms-2" onclick="enableEditOilChange('${item.id}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                      <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293z"/>
                                    </svg>
                                </button>
                `;
                    }
                    // Container oculto para edição da observação
                    if (sectionTitle === 'Manutenção') {
                        html += `
                                <div class="row d-none" id="edit-container-maintenance-${item.id}">
                                    <div class="col mb-4">
                                        <textarea class="form-control mb-2 bg-transparent border-white border-1 text-white" id="textarea-maintenance-${item.id}">${item.observation ?? ''}</textarea>
                                        <button class="btn btn-outline-success btn-sm" onclick="saveObservationMaintenance('${item.id}')">Atualizar observação</button>
                                        <button class="btn btn-outline-danger btn-sm" onclick="disableEditMaintenance('${item.id}')">Cancelar</button>
                                    </div>
                                </div>
                `;
                    } else if (sectionTitle === 'Troca de Óleo') {
                        html += `
                                <div class="row d-none" id="edit-container-oilchange-${item.id}">
                                    <div class="col mb-4">
                                        <textarea class="form-control mb-2 bg-transparent border-white border-1 text-white" id="textarea-oilchange-${item.id}">${item.observation ?? ''}</textarea>
                                        <button class="btn btn-outline-success btn-sm" onclick="saveObservationOilChange('${item.id}')">Atualizar observação</button>
                                        <button class="btn btn-outline-danger btn-sm" onclick="disableEditOilChange('${item.id}')">Cancelar</button>
                                    </div>
                                </div>
                `;
                    }

                    html += `
                            </div>
                        </div>
                        <form method="POST" action="/${sectionTitle === 'Manutenção' ? 'manutencao' : 'troca-de-oleo'}/${item.id}" onsubmit="return confirm('Confirma deletar a ${sectionTitle.toLowerCase()} de ${formattedDate(item.date)}?');">
                            @method('DELETE')
                            <input ${isRentalFinished ? 'disabled' : ''} type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button ${isRentalFinished ? 'disabled' : ''} type="submit" class="btn btn-danger btn-sm">Excluir</button>
                        </form>
                    </div>
                </div>
            `;
                });

                // Controles de paginação (os nomes das funções abaixo podem ser ajustados de acordo com sua implementação)
                html += `
            <div class="pagination d-flex justify-content-between align-items-center mt-3">
                <button class="btn btn-sm btn-secondary" ${data.current_page > 1 ? '' : 'disabled'} onclick="${sectionTitle === 'Manutenção' ? 'fetchMaintenanceData' : 'fetchOilChangeData'}(${data.current_page - 1})">Anterior</button>
                <span>Página ${data.current_page} de ${data.last_page}</span>
                <button class="btn btn-sm btn-secondary" ${data.current_page < data.last_page ? '' : 'disabled'} onclick="${sectionTitle === 'Manutenção' ? 'fetchMaintenanceData' : 'fetchOilChangeData'}(${data.current_page + 1})">Próxima</button>
            </div>
        `;
                resultsContainer.innerHTML = html;
            } else {
                resultsContainer.innerHTML = `Nenhum dado encontrado.`;
            }
        }

        // Funções para Manutenção (Revisão)
        function enableEditMaintenance(itemId) {
            document.getElementById(`edit-container-maintenance-${itemId}`).classList.remove('d-none');
        }

        function disableEditMaintenance(itemId) {
            document.getElementById(`edit-container-maintenance-${itemId}`).classList.add('d-none');
        }

        function saveObservationMaintenance(itemId) {
            const textarea = document.getElementById(`textarea-maintenance-${itemId}`);
            const observation = textarea.value;

            fetch(`/manutencao/${itemId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        observation: observation
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Observação atualizada!');
                        document.getElementById(`observation-maintenance-${itemId}`).textContent = observation ||
                            'Sem observações.';
                        disableEditMaintenance(itemId);
                    } else {
                        alert('Erro ao atualizar observação.');
                    }
                })
                .catch(error => {
                    console.error('Erro ao atualizar observação:', error);
                    alert('Erro ao atualizar observação.');
                });
        }

        // Funções para Troca de Óleo
        function enableEditOilChange(itemId) {
            document.getElementById(`edit-container-oilchange-${itemId}`).classList.remove('d-none');
        }

        function disableEditOilChange(itemId) {
            document.getElementById(`edit-container-oilchange-${itemId}`).classList.add('d-none');
        }

        function saveObservationOilChange(itemId) {
            const textarea = document.getElementById(`textarea-oilchange-${itemId}`);
            const observation = textarea.value;

            fetch(`/troca-de-oleo/${itemId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        observation: observation
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Observação atualizada!');
                        document.getElementById(`observation-oilchange-${itemId}`).textContent = observation ||
                            'Sem observações.';
                        disableEditOilChange(itemId);
                    } else {
                        alert('Erro ao atualizar observação.');
                    }
                })
                .catch(error => {
                    console.error('Erro ao atualizar observação:', error);
                    alert('Erro ao atualizar observação.');
                });
        }


        function fetchFineData(page = 1) {
            const apiUrl =
                `/multa/{{ $rental->vehicle->id }}/{{ $rental->id }}?page=${page}`; // Adiciona o número da página

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    const resultsContainer = document.getElementById('multaModalBody');
                    if (data && data.data.length > 0) {
                        let html = '';

                        data.data.forEach((item) => {
                            const collapseId = `collapse-${item.count}`;

                            html += `
                        <div class="row mb-3" style="border-bottom: 1px solid white; padding-bottom: 8px;">
                            <!-- Entrada principal -->
                            <div class="col-12 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="false" aria-controls="${collapseId}">
                                <span>${item.count}. ${formattedDate(item.created_at)} - R$ ${item.cost}</span>
                                <span class="badge rounded-3 fs-6 ${item.paid == 1 ? 'text-bg-success' : 'text-bg-danger'}">${item.paid == 1 ? 'Pago' : 'Não pago'}</span>
                            </div>
                        </div>

                        <!-- Collapse correspondente -->
                        <div class="collapse mb-3" id="${collapseId}">
                            <div class="card card-body" style="background-color: #343a40; color: white;">
                                <div class="row">
                                    <div class="col">
                                        <p><strong>Observação:</strong> ${item.observation ?? 'Sem observações.'}</p>
                                    </div>
                                    <div class="col-auto">
                                        <div class="form-check form-switch">
                                            <input ${isRentalFinished ? 'disabled' : ''} class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked-${item.id}" ${item.paid == 1 ? 'checked' : ''} onchange="togglePaidStatus('${item.id}')">
                                            <label class="form-check-label" for="flexSwitchCheckChecked-${item.id}">Pago</label>
                                        </div>
                                    </div>
                                </div>
                                <form method="POST" action="/multa/${item.id}" onsubmit="return confirm('Confirma deletar a multa de ${formattedDate(item.created_at)}?');">
                                    @method('DELETE')
                                    <input ${isRentalFinished ? 'disabled' : ''} type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button ${isRentalFinished ? 'disabled' : ''} type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                </form>
                            </div>
                        </div>
                    `;
                        });

                        // Adiciona controles de paginação
                        html += `
                    <div class="pagination d-flex justify-content-between align-items-center mt-3">
                        <button 
                            class="btn btn-sm btn-secondary" 
                            ${data.current_page > 1 ? '' : 'disabled'} 
                            onclick="fetchFineData(${data.current_page - 1})">
                            Anterior
                        </button>
                        <span>Página ${data.current_page} de ${data.last_page}</span>
                        <button 
                            class="btn btn-sm btn-secondary" 
                            ${data.current_page < data.last_page ? '' : 'disabled'} 
                            onclick="fetchFineData(${data.current_page + 1})">
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
                    const resultsContainer = document.getElementById('multaModalBody');
                    resultsContainer.innerHTML = 'Erro ao carregar os dados.';
                });
        }

        function fetchPaymentData() {
            const apiUrl = `/financas/{{ $rental->vehicle->id }}/{{ $rental->id }}`;

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    const resultsContainer = document.getElementById('financasModalBody');
                    if (data && data.length > 0) {
                        let html = '';

                        data.forEach((item) => {
                            const collapseId = `collapse-${item.count}`;
                            const paymentDate = new Date(item.payment_date);
                            const today = new Date();
                            today.setHours(0, 0, 0, 0);

                            let badgeClass = null; // Default: Pago (verde)

                            if (item.paid == 0) {
                                if (paymentDate.getTime() === today.getTime()) {
                                    badgeClass = 'text-warning fw-bold'; // Hoje e não pago (laranja)
                                } else if (paymentDate.getTime() < today.getTime()) {
                                    badgeClass = 'text-danger fw-bold'; // Passado e não pago (vermelho)
                                }
                            }

                            html += `
                    <div class="row mb-3" style="border-bottom: 1px solid white; padding-bottom: 8px;">
                        <!-- Entrada principal -->
                        <div class="col-12 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="false" aria-controls="${collapseId}">
                            <span class="${badgeClass}">${item.count}. ${formattedDate(item.payment_date)} - R$ ${item.cost}</span>
                                <span class="badge rounded-3 fs-6 ${item.paid == 1 ? 'text-bg-success' : 'text-bg-danger'}">${item.paid == 1 ? 'Pago' : (item.paid === 2 ? 'Não pago' : 'Encerrado')}</span>
                            </div>
                        </div>

                        <!-- Collapse correspondente -->
                        <div class="collapse mb-3" id="${collapseId}">
                            <div class="card card-body" style="background-color: #343a40; color: white;">
                                <div class="row gap-3">
                                    <div class="col-auto">
                                        <div class="form-check form-switch">
                                            <input ${isRentalFinished ? 'disabled' : ''} class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked-${item.id}" ${item.paid == 1 ? 'checked' : ''} onchange="togglePaymentPaidStatus('${item.id}')">
                                            <label class="form-check-label" for="flexSwitchCheckChecked-${item.id}">Pago</label>
                                        </div>
                                    </div>`;
                            if (item.paid_in) {
                                html += `<div class="col-auto">Pago em ${formattedDate(item.paid_in)}</div>`;
                            }
                            html += `</div>
                            </div>  
                        </div>
                    `;
                        });
                        resultsContainer.innerHTML = html;
                    } else {
                        resultsContainer.innerHTML = 'Nenhum dado encontrado.';
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar dados:', error);
                    const resultsContainer = document.getElementById('financasModalBody');
                    resultsContainer.innerHTML = 'Erro ao carregar os dados.';
                });
        }

        async function togglePaidStatus(itemId) {
            const checkbox = document.getElementById(`flexSwitchCheckChecked-${itemId}`);
            const previousState = checkbox.checked;

            if (confirm('Deseja mudar o status da multa?')) {
                try {
                    const response = await fetch(`/multa/${itemId}`, {
                        method: 'put',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Adicionar token CSRF para segurança
                        },
                        body: JSON.stringify({
                            paid: checkbox.checked,
                        })
                    });

                    if (!response.ok) {
                        throw new Error('Erro ao atualizar o status');
                    } else {
                        if (previousState) {
                            alert('Multa paga!')
                            window.location.reload()
                        } else {
                            alert('Multa não paga!')
                            window.location.reload()

                        }
                    }
                    // Se a requisição for bem-sucedida, não precisamos fazer nada, o estado já está atualizado
                } catch (error) {
                    console.error('Erro:', error);
                    // Reverte o estado do checkbox em caso de erro
                    checkbox.checked = !previousState;
                }
            } else {
                checkbox.checked = !previousState;
            }
        }

        async function togglePaymentPaidStatus(itemId) {
            const checkbox = document.getElementById(`flexSwitchCheckChecked-${itemId}`);
            const previousState = checkbox.checked;

            if (confirm('Deseja mudar o status do pagamento?')) {
                try {
                    const response = await fetch(`/financas/${itemId}`, {
                        method: 'put',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Adicionar token CSRF para segurança
                        },
                        body: JSON.stringify({
                            paid: checkbox.checked,
                        })
                    });

                    if (!response.ok) {
                        throw new Error('Erro ao atualizar o status');
                    } else {
                        if (previousState) {
                            alert('Cobrança paga!')
                            window.location.reload()
                        } else {
                            alert('Cobrança não paga!')
                            window.location.reload()

                        }
                    }
                    // Se a requisição for bem-sucedida, não precisamos fazer nada, o estado já está atualizado
                } catch (error) {
                    console.error('Erro:', error);
                    // Reverte o estado do checkbox em caso de erro
                    checkbox.checked = !previousState;
                }
            } else {
                checkbox.checked = !previousState;
            }
        }
    </script>
@endsection
