@extends('layouts.bootstrap')
@section('head')
    <style>
        .placa {
            bottom: -26px;
        }

        @media(min-width: 992px) {
            .placa {
                bottom: -57px !important;
            }
        }

        .info-placa {
            top: 33%;
            z-index: 2;
            left: 50%;
            transform: translateX(-50%);
        }

        .content {
            height: calc(100dvh - 60px - 70px);
        }

        .vehicle {
            border: 1px solid white;
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.514) !important;
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
        <div class="row g-0 mb-3">
            <div class="col text-center">
                <h1 style="font-size: 20px;">Minhas locações</h1>
            </div>
        </div>

        <div class="row">
            <div class="col text-end">
                <form action="{{ route('rental.index') }}" method="GET">
                    <div class="row justify-content-center">
                        <div class="col-12 text-center">
                            <small class="text-white">Filtrar por:</small>
                            <br>
                            <select name="filter" id="filter"
                                class="form-select d-inline w-auto bg-transparent text-white" onchange="this.form.submit()">
                                <option class="text-black" value="todos" {{ $filter === 'todos' ? 'selected' : '' }}>Todos
                                </option>
                                <option class="text-black" value="ativos" {{ $filter === 'ativos' ? 'selected' : '' }}>
                                    Ativos</option>
                                <option class="text-black" value="cancelados"
                                    {{ $filter === 'cancelados' ? 'selected' : '' }}>Cancelados</option>
                                <option class="text-black" value="finalizados"
                                    {{ $filter === 'finalizados' ? 'selected' : '' }}>Finalizados</option>
                            </select>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <small class="text-white">Pesquisar por:</small>
                            </div>
                            <div class="col-lg-4 mx-auto">
                                <div class="d-flex gap-2">
                                    <input type="text" name="search" id="search"
                                        class="form-control d-inline bg-transparent text-white"
                                        placeholder="Nome do Locador ou Placa" value="{{ $search }}">
                                    <button type="submit" class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg"
                                            width="16" height="16" fill="currentColor" class="bi bi-search"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                        </svg></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($search)
                        <div class="row mt-2">
                            <div class="col text-center">
                                <a href="{{ route('rental.index', ['filter' => $filter]) }}">
                                    ❌ Limpar pesquisa
                                </a>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <div class="row mt-4 g-3">
            @if ($myRentals->count() === 0)
                <div class="col-12 text-center">
                    <span class="text-secondary">Nenhuma locação encontrada para esse filtro.</span>
                </div>
            @else
                @foreach ($myRentals as $rental)
                    <div class="col-6 col-lg-2 mb-4 mb-lg-5">
                        <a href="{{ route('rental.show', ['rental' => $rental->id]) }}"
                            class="h-100 text-decoration-none text-white position-relative">
                            <div
                                class="h-100 vehicle rounded-2 text-center pt-1 position-relative d-flex flex-column justify-content-between">
                                <div class="px-1">
                                    <div>
                                        <img src="{{ route('photo.show', ['rental' => $rental->id]) }}"
                                            style="max-width: 100px" class="img-fluid rounded-5">
                                    </div>
                                    <small><strong>{{ $rental->landlord_name }}</strong></small>
                                    <br><small>{{ $rental->vehicle->brand }}</small>
                                    <br><small>{{ $rental->vehicle->model }} {{ $rental->vehicle->year }}</small>
                                    @if ($rental->finished_at === null)
                                        <br><small class="text-success"><strong>Ativa /
                                                @if ($rental->has_overdue_payments)
                                                    <span class="text-warning">Com pendências</span>
                                                @else
                                                    Em dia
                                                @endif
                                            </strong></small>
                                    @elseif($rental->stop_date !== null)
                                        <br><small class="text-danger"><strong>Cancelada</strong></small>
                                    @else
                                        <br><small class="text-danger"><strong>Finalizada</strong></small>
                                    @endif
                                </div>
                                <div class="position-relative">
                                    <img src="{{ asset('assets/svg/placa.svg') }}" alt="" class="w-100">
                                    <span class="fs-4 w-100 text-black position-absolute info-placa">
                                        <strong>{{ $rental->vehicle->license_plate }}</strong>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
@section('options-button')
    <div class="container text-end pe-4 menu-button">
        <a href="{{ route('rental.new') }}" class="btn btn-light fs-1 py-0 rounded-3 text-decoration-none">
            <strong>+</strong>
        </a>
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
