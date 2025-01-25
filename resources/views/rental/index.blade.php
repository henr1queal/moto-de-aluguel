@extends('layouts.bootstrap')
@section('head')
    <style>
        .placa {
            bottom: -14%;
        }

        .info-placa {
            bottom: -26%;
            z-index: 2;
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
                <h1 style="font-size: 20px;">Minhas locações</h1>
            </div>
        </div>
        <div class="row mt-4 g-3">
            @if ($myRentals->count() === 0)
                <div class="col-12 text-center">
                    <span class="text-secondary">Adicione uma locação para vê-la aqui.</span>
                </div>
            @else
                @foreach ($myRentals as $rental)
                    <div class="col-6 d-flex mb-5">
                        <a href="{{ route('rental.show', ['rental' => $rental->id]) }}"
                            class="rounded-4 menu-item d-flex flex-column justify-content-between w-100 text-decoration-none text-white">
                            <div class="row justify-content-center g-0">
                                <div class="col-12 text-center">
                                    <div class="bg-white mx-auto rounded-circle position-relative"
                                        style="width: 140px; height: 140px;">
                                        <div class="d-flex justify-content-center h-100">
                                            <img src="{{ asset('assets/svg/silhueta.svg') }}" alt=""
                                                class="img-fluid" style="width: 76px; height: auto;">
                                        </div>
                                        <div class="info-placa position-absolute w-100"
                                            style="left: 50%; transform: translateX(-50%);">
                                            <span class="fs-4 text-break text-black mb-0"><strong>{{ $rental->vehicle->license_plate }}</strong></span>
                                            @if ($rental->finished_at === null)
                                            <br><small class="text-success"><strong>Ativa</strong></small>                                                
                                            @else
                                            <br><small class="text-danger"><strong>Finalizada</strong></small>                                                

                                            @endif
                                        </div>
                                        <img src="{{ asset('assets/svg/placa.svg') }}" alt=""
                                            class="position-absolute placa" style="left: 50%; transform: translateX(-50%);">
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
