@extends('layouts.bootstrap')
@section('head')
    <style>
        .placa {
            bottom: -14%;
        }

        .info-placa {
            bottom: -8%;
            z-index: 2;
        }

        .content {
            height: 85dvh;
            margin-bottom: 0;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row g-0">
            <div class="col text-center">
                <h1 style="font-size: 20px;">Minhas locações</h1>
            </div>
        </div>
        <div class="row mt-4 g-3">
            <div class="col-6 d-flex pb-4">
                <a href="{{ route('profile.edit') }}"
                    class="rounded-4 menu-item d-flex flex-column justify-content-between w-100 text-decoration-none text-white">
                    <div class="row justify-content-center g-0">
                        <div class="col-12 text-center">
                            <div class="bg-white mx-auto rounded-circle position-relative"
                                style="width: 140px; height: 140px;">
                                <div class="d-flex justify-content-center h-100">
                                    <img src="{{ asset('assets/svg/silhueta.svg') }}" alt="" class="img-fluid"
                                        style="width: 76px; height: auto;">
                                </div>
                                <div class="info-placa position-absolute w-100"
                                    style="left: 50%; transform: translateX(-50%);">
                                    <span class="fs-4 text-break text-black"><strong>SAE-2H75</strong></span>
                                </div>
                                <img src="{{ asset('assets/svg/placa.svg') }}" alt=""
                                    class="position-absolute placa" style="left: 50%; transform: translateX(-50%);">
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 d-flex pb-4">
                <a href="{{ route('profile.edit') }}"
                    class="rounded-4 menu-item d-flex flex-column justify-content-between w-100 text-decoration-none text-white">
                    <div class="row justify-content-center g-0">
                        <div class="col-12 text-center">
                            <div class="bg-white mx-auto rounded-circle position-relative"
                                style="width: 140px; height: 140px;">
                                <div class="d-flex justify-content-center h-100">
                                    <img src="{{ asset('assets/svg/silhueta.svg') }}" alt="" class="img-fluid"
                                        style="width: 76px; height: auto;">
                                </div>
                                <div class="info-placa position-absolute w-100"
                                    style="left: 50%; transform: translateX(-50%);">
                                    <span class="fs-4 text-break text-black"><strong>SAE-2H75</strong></span>
                                </div>
                                <img src="{{ asset('assets/svg/placa.svg') }}" alt=""
                                    class="position-absolute placa" style="left: 50%; transform: translateX(-50%);">
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 d-flex pb-4">
                <a href="{{ route('profile.edit') }}"
                    class="rounded-4 menu-item d-flex flex-column justify-content-between w-100 text-decoration-none text-white">
                    <div class="row justify-content-center g-0">
                        <div class="col-12 text-center">
                            <div class="bg-white mx-auto rounded-circle position-relative"
                                style="width: 140px; height: 140px;">
                                <div class="d-flex justify-content-center h-100">
                                    <img src="{{ asset('assets/svg/silhueta.svg') }}" alt="" class="img-fluid"
                                        style="width: 76px; height: auto;">
                                </div>
                                <div class="info-placa position-absolute w-100"
                                    style="left: 50%; transform: translateX(-50%);">
                                    <span class="fs-4 text-break text-black"><strong>SAE-2H75</strong></span>
                                </div>
                                <img src="{{ asset('assets/svg/placa.svg') }}" alt=""
                                    class="position-absolute placa" style="left: 50%; transform: translateX(-50%);">
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 d-flex pb-4">
                <a href="{{ route('profile.edit') }}"
                    class="rounded-4 menu-item d-flex flex-column justify-content-between w-100 text-decoration-none text-white">
                    <div class="row justify-content-center g-0">
                        <div class="col-12 text-center">
                            <div class="bg-white mx-auto rounded-circle position-relative"
                                style="width: 140px; height: 140px;">
                                <div class="d-flex justify-content-center h-100">
                                    <img src="{{ asset('assets/svg/silhueta.svg') }}" alt="" class="img-fluid"
                                        style="width: 76px; height: auto;">
                                </div>
                                <div class="info-placa position-absolute w-100"
                                    style="left: 50%; transform: translateX(-50%);">
                                    <span class="fs-4 text-break text-black"><strong>SAE-2H75</strong></span>
                                </div>
                                <img src="{{ asset('assets/svg/placa.svg') }}" alt=""
                                    class="position-absolute placa" style="left: 50%; transform: translateX(-50%);">
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 d-flex pb-4">
                <a href="{{ route('profile.edit') }}"
                    class="rounded-4 menu-item d-flex flex-column justify-content-between w-100 text-decoration-none text-white">
                    <div class="row justify-content-center g-0">
                        <div class="col-12 text-center">
                            <div class="bg-white mx-auto rounded-circle position-relative"
                                style="width: 140px; height: 140px;">
                                <div class="d-flex justify-content-center h-100">
                                    <img src="{{ asset('assets/svg/silhueta.svg') }}" alt="" class="img-fluid"
                                        style="width: 76px; height: auto;">
                                </div>
                                <div class="info-placa position-absolute w-100"
                                    style="left: 50%; transform: translateX(-50%);">
                                    <span class="fs-4 text-break text-black"><strong>SAE-2H75</strong></span>
                                </div>
                                <img src="{{ asset('assets/svg/placa.svg') }}" alt=""
                                    class="position-absolute placa" style="left: 50%; transform: translateX(-50%);">
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 d-flex pb-4">
                <a href="{{ route('profile.edit') }}"
                    class="rounded-4 menu-item d-flex flex-column justify-content-between w-100 text-decoration-none text-white">
                    <div class="row justify-content-center g-0">
                        <div class="col-12 text-center">
                            <div class="bg-white mx-auto rounded-circle position-relative"
                                style="width: 140px; height: 140px;">
                                <div class="d-flex justify-content-center h-100">
                                    <img src="{{ asset('assets/svg/silhueta.svg') }}" alt="" class="img-fluid"
                                        style="width: 76px; height: auto;">
                                </div>
                                <div class="info-placa position-absolute w-100"
                                    style="left: 50%; transform: translateX(-50%);">
                                    <span class="fs-4 text-break text-black"><strong>SAE-2H75</strong></span>
                                </div>
                                <img src="{{ asset('assets/svg/placa.svg') }}" alt=""
                                    class="position-absolute placa" style="left: 50%; transform: translateX(-50%);">
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 d-flex pb-4">
                <a href="{{ route('profile.edit') }}"
                    class="rounded-4 menu-item d-flex flex-column justify-content-between w-100 text-decoration-none text-white">
                    <div class="row justify-content-center g-0">
                        <div class="col-12 text-center">
                            <div class="bg-white mx-auto rounded-circle position-relative"
                                style="width: 140px; height: 140px;">
                                <div class="d-flex justify-content-center h-100">
                                    <img src="{{ asset('assets/svg/silhueta.svg') }}" alt="" class="img-fluid"
                                        style="width: 76px; height: auto;">
                                </div>
                                <div class="info-placa position-absolute w-100"
                                    style="left: 50%; transform: translateX(-50%);">
                                    <span class="fs-4 text-break text-black"><strong>SAE-2H75</strong></span>
                                </div>
                                <img src="{{ asset('assets/svg/placa.svg') }}" alt=""
                                    class="position-absolute placa" style="left: 50%; transform: translateX(-50%);">
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 d-flex pb-4">
                <a href="{{ route('profile.edit') }}"
                    class="rounded-4 menu-item d-flex flex-column justify-content-between w-100 text-decoration-none text-white">
                    <div class="row justify-content-center g-0">
                        <div class="col-12 text-center">
                            <div class="bg-white mx-auto rounded-circle position-relative"
                                style="width: 140px; height: 140px;">
                                <div class="d-flex justify-content-center h-100">
                                    <img src="{{ asset('assets/svg/silhueta.svg') }}" alt="" class="img-fluid"
                                        style="width: 76px; height: auto;">
                                </div>
                                <div class="info-placa position-absolute w-100"
                                    style="left: 50%; transform: translateX(-50%);">
                                    <span class="fs-4 text-break text-black"><strong>SAE-2H75</strong></span>
                                </div>
                                <img src="{{ asset('assets/svg/placa.svg') }}" alt=""
                                    class="position-absolute placa" style="left: 50%; transform: translateX(-50%);">
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 d-flex pb-4">
                <a href="{{ route('profile.edit') }}"
                    class="rounded-4 menu-item d-flex flex-column justify-content-between w-100 text-decoration-none text-white">
                    <div class="row justify-content-center g-0">
                        <div class="col-12 text-center">
                            <div class="bg-white mx-auto rounded-circle position-relative"
                                style="width: 140px; height: 140px;">
                                <div class="d-flex justify-content-center h-100">
                                    <img src="{{ asset('assets/svg/silhueta.svg') }}" alt="" class="img-fluid"
                                        style="width: 76px; height: auto;">
                                </div>
                                <div class="info-placa position-absolute w-100"
                                    style="left: 50%; transform: translateX(-50%);">
                                    <span class="fs-4 text-break text-black"><strong>SAE-2H75</strong></span>
                                </div>
                                <img src="{{ asset('assets/svg/placa.svg') }}" alt=""
                                    class="position-absolute placa" style="left: 50%; transform: translateX(-50%);">
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 d-flex pb-4">
                <a href="{{ route('profile.edit') }}"
                    class="rounded-4 menu-item d-flex flex-column justify-content-between w-100 text-decoration-none text-white">
                    <div class="row justify-content-center g-0">
                        <div class="col-12 text-center">
                            <div class="bg-white mx-auto rounded-circle position-relative"
                                style="width: 140px; height: 140px;">
                                <div class="d-flex justify-content-center h-100">
                                    <img src="{{ asset('assets/svg/silhueta.svg') }}" alt="" class="img-fluid"
                                        style="width: 76px; height: auto;">
                                </div>
                                <div class="info-placa position-absolute w-100"
                                    style="left: 50%; transform: translateX(-50%);">
                                    <span class="fs-4 text-break text-black"><strong>SAE-2H75</strong></span>
                                </div>
                                <img src="{{ asset('assets/svg/placa.svg') }}" alt=""
                                    class="position-absolute placa" style="left: 50%; transform: translateX(-50%);">
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 d-flex pb-4">
                <a href="{{ route('profile.edit') }}"
                    class="rounded-4 menu-item d-flex flex-column justify-content-between w-100 text-decoration-none text-white">
                    <div class="row justify-content-center g-0">
                        <div class="col-12 text-center">
                            <div class="bg-white mx-auto rounded-circle position-relative"
                                style="width: 140px; height: 140px;">
                                <div class="d-flex justify-content-center h-100">
                                    <img src="{{ asset('assets/svg/silhueta.svg') }}" alt="" class="img-fluid"
                                        style="width: 76px; height: auto;">
                                </div>
                                <div class="info-placa position-absolute w-100"
                                    style="left: 50%; transform: translateX(-50%);">
                                    <span class="fs-4 text-break text-black"><strong>SAE-2H75</strong></span>
                                </div>
                                <img src="{{ asset('assets/svg/placa.svg') }}" alt=""
                                    class="position-absolute placa" style="left: 50%; transform: translateX(-50%);">
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 d-flex pb-4">
                <a href="{{ route('profile.edit') }}"
                    class="rounded-4 menu-item d-flex flex-column justify-content-between w-100 text-decoration-none text-white">
                    <div class="row justify-content-center g-0">
                        <div class="col-12 text-center">
                            <div class="bg-white mx-auto rounded-circle position-relative"
                                style="width: 140px; height: 140px;">
                                <div class="d-flex justify-content-center h-100">
                                    <img src="{{ asset('assets/svg/silhueta.svg') }}" alt="" class="img-fluid"
                                        style="width: 76px; height: auto;">
                                </div>
                                <div class="info-placa position-absolute w-100"
                                    style="left: 50%; transform: translateX(-50%);">
                                    <span class="fs-4 text-break text-black"><strong>SAE-2H75</strong></span>
                                </div>
                                <img src="{{ asset('assets/svg/placa.svg') }}" alt=""
                                    class="position-absolute placa" style="left: 50%; transform: translateX(-50%);">
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
@section('options-button')
    <div class="container text-end pe-4">
        <a href="{{ route('rental-new') }}" class="btn btn-light fs-1 py-0 rounded-3 text-decoration-none">
            <strong>+</strong>
        </a>
    </div>
@endsection
