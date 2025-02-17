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
            height: 85dvh;
            margin-bottom: 10px;
        }

        .vehicle {
            border: 1px solid white;
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
                    <label for="filter" class="fw-light me-1">Filtrar:</label>
                    <select name="filter" id="filter" class="form-select d-inline w-auto bg-transparent text-white"
                        onchange="this.form.submit()">
                        <option class="text-black" value="todos" {{ $filter === 'todos' ? 'selected' : '' }}>Todos</option>
                        <option class="text-black" value="ativos" {{ $filter === 'ativos' ? 'selected' : '' }}>Ativos
                        </option>
                        <option class="text-black" value="cancelados" {{ $filter === 'cancelados' ? 'selected' : '' }}>
                            Cancelados</option>
                        <option class="text-black" value="finalizados" {{ $filter === 'finalizados' ? 'selected' : '' }}>
                            Finalizados</option>
                    </select>
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
                            class="text-decoration-none text-white position-relative">
                            <div class="vehicle rounded-2 text-center pt-1 position-relative">
                                <div class="px-1">
                                    <div>
                                        <img src="{{ route('photo.show', ['rental' => $rental->id]) }}" style="max-width: 100px" class="img-fluid rounded-5">
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
    <div class="container text-end pe-4">
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
