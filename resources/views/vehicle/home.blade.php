@extends('layouts.bootstrap')
@section('head')
    <style>
        @media(min-width: 992px) {
            .placa {
                bottom: -57px !important;
            }
        }
        
        .placa {
            bottom: -26px;
            z-index: 2;
            left: 50%;
            transform: translateX(-50%);
        }


        .info-placa {
            left: 50%;
            transform: translate(-50%, -40%);
            top: 50%;
        }

        .vehicle {
            border: 1px solid white;
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
                <h1 style="font-size: 20px;">Minhas motos</h1>
            </div>
        </div>
        <div class="row mt-4 g-3 mb-3">
            @if ($myVehicles->count() === 0)
                <div class="col-12 text-center">
                    <span class="text-secondary">Adicione um veículo para vê-lo aqui.</span>
                </div>
            @else
                @foreach ($myVehicles as $vehicle)
                    <div class="col-6 col-lg-2 mb-lg-5 mb-4">
                        <a href="{{ route('vehicle.show', ['vehicle' => $vehicle->id]) }}"
                            class="text-decoration-none text-white">
                            <div class="vehicle rounded-4 text-center pt-2 pb-5 pb-lg-4 position-relative">
                                <small>{{ $vehicle->brand }}</small>
                                <br><small>{{ $vehicle->model }}</small>
                                <br><small>ANO: {{ $vehicle->year }}</small>
                                <br>
                                @if ($vehicle->actualRental)
                                    <small class="text-warning">ALUGADA</small>
                                @else
                                    <small class="text-success">DISPONÍVEL</small>
                                @endif
                                <div class="placa position-absolute w-100">
                                    <div class="position-relative">
                                        <img src="{{ asset('assets/svg/placa.svg') }}" alt="" class="w-100">
                                        <span
                                            class="fs-4 w-100 text-black position-absolute info-placa"><strong>{{ $vehicle->license_plate }}</strong></span>
                                    </div>
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
        <a href="{{ route('vehicle.new') }}" class="btn btn-light fs-1 py-0 rounded-3 text-decoration-none">
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
