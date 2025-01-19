@extends('layouts.bootstrap')
@section('head')
    <style>
        .placa {
            bottom: -14%;
        }

        .placa {
            bottom: -16%;
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
                    <div class="col-6 mb-4">
                        <a href="{{ route('vehicle-show', ['vehicle' => $vehicle->id]) }}" class="text-decoration-none text-white">
                            <div class="vehicle rounded-4 text-center pt-2 pb-5 position-relative">
                                <small>{{ $vehicle->brand }}</small>
                                <br><small>{{ $vehicle->model }}</small>
                                <br><small>ANO: {{ $vehicle->year }}</small>
                                <br><small class="text-success">DISPONÍVEL</small>
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
        <a href="{{ route('vehicle-new') }}" class="btn btn-light fs-1 py-0 rounded-3 text-decoration-none">
            <strong>+</strong>
        </a>
    </div>
@endsection
