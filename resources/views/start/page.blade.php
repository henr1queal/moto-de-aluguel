@extends('layouts.bootstrap')
@section('head')
    <style>
        .menu-item {
            background-color: #4D4C4C;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row g-0">
            <div class="col text-center">
                <h1 style="font-size: 20px;">Home</h1>
            </div>
        </div>
        <div class="row g-0 mt-4 g-3">
            <div class="col-6 col-lg-3 col-xxl-2 d-flex">
                <a href="{{ route('vehicle.index') }}"
                    class="rounded-4 menu-item d-flex flex-column justify-content-between w-100 text-decoration-none text-white">
                    <div class="row g-0">
                        <div class="pt-3 pb-2 px-4 text-center">
                            <div class="row g-0 gap-2">
                                <img src="{{ asset('assets/svg/bike.svg') }}" alt=""
                                    style="width: 40px; height: auto;" class="mx-auto">
                                <p class="mb-0 fw-light" style="font-size: 16px;">
                                    Gerencie suas motocicletas com facilidade
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-black py-2 text-center w-100 rounded-bottom-4">
                        <small>Minhas Motos</small>
                    </div>
                </a>
            </div>
            <div class="col-6 col-lg-3 col-xxl-2 d-flex">
                <a href="{{ route('rental.index') }}"
                    class="rounded-4 menu-item d-flex flex-column justify-content-between w-100 text-decoration-none text-white">
                    <div class="row g-0">
                        <div class="pt-3 pb-2 px-4 text-center">
                            <div class="row g-0 gap-2">
                                <img src="{{ asset('assets/svg/book.svg') }}" alt=""
                                    style="width: 30px; height: auto;" class="mx-auto">
                                <p class="mb-0 fw-light" style="font-size: 16px;">
                                    Gerencie aluguéis ativos ou declare um novo
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-black py-2 text-center w-100 rounded-bottom-4">
                        <small>Minhas Locações</small>
                    </div>
                </a>
            </div>
            <div class="col-6 col-lg-3 col-xxl-2 d-flex">
                <a href="{{ route('payment.index') }}"
                    class="rounded-4 menu-item d-flex flex-column justify-content-between w-100 text-decoration-none text-white">
                    <div class="row g-0">
                        <div class="pt-3 pb-2 px-4 text-center">
                            <div class="row g-0 gap-2">
                                <img src="{{ asset('assets/svg/money.svg') }}" alt=""
                                    style="width: 30px; height: auto;" class="mx-auto">
                                <p class="mb-0 fw-light" style="font-size: 16px;">
                                    Visualize seus lucros e estimativas
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-black py-2 text-center w-100 rounded-bottom-4">
                        <small>Finanças</small>
                    </div>
                </a>
            </div>
            <div class="col-6 col-lg-3 col-xxl-2 d-flex">
                <a href="{{ route('notifications') }}"
                    class="rounded-4 menu-item d-flex flex-column justify-content-between w-100 text-decoration-none text-white">
                    <div class="row g-0">
                        <div class="pt-3 pb-2 px-4 text-center">
                            <div class="row g-0 gap-2">
                                <img src="{{ asset('assets/svg/notification.svg') }}" alt=""
                                    style="width: 30px; height: auto;" class="mx-auto">
                                <p class="mb-0 fw-light" style="font-size: 16px;">
                                    Fique atualizado sobre o que está acontecendo
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-black py-2 text-center w-100 rounded-bottom-4">
                        <small>Lembretes e pendências</small>
                    </div>
                </a>
            </div>
            <div class="col-6 col-lg-3 col-xxl-2 d-flex">
                <a href="{{ route('maintenance.index') }}"
                    class="rounded-4 menu-item d-flex flex-column justify-content-between w-100 text-decoration-none text-white">
                    <div class="row g-0">
                        <div class="pt-3 pb-2 px-4 text-center">
                            <div class="row g-0 gap-2">
                                <img src="{{ asset('assets/svg/wrench.svg') }}" alt=""
                                    style="width: 30px; height: auto;" class="mx-auto">
                                <p class="mb-0 fw-light" style="font-size: 16px;">
                                    Verifique o histórico de peças da moto
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-black py-2 text-center w-100 rounded-bottom-4">
                        <small>Manutenções</small>
                    </div>
                </a>
            </div>
            <div class="col-6 col-lg-3 col-xxl-2 d-flex">
                <a href="{{ route('user.index') }}"
                    class="rounded-4 menu-item d-flex flex-column justify-content-between w-100 text-decoration-none text-white">
                    <div class="row g-0">
                        <div class="pt-3 pb-2 px-4 text-center">
                            <div class="row g-0 gap-2">
                                <img src="{{ asset('assets/svg/profile.svg') }}" alt=""
                                    style="width: 30px; height: auto;" class="mx-auto">
                                <p class="mb-0 fw-light" style="font-size: 16px;">
                                    Edite permissões, adicione ou redefina usuários
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-black py-2 text-center w-100 rounded-bottom-4">
                        <small>Usuários</small>
                    </div>
                </a>
            </div>
            <div class="col-6 col-lg-3 col-xxl-2 d-flex">
                <a href="{{ route('profile.edit') }}"
                    class="rounded-4 menu-item d-flex flex-column justify-content-between w-100 text-decoration-none text-white">
                    <div class="row g-0">
                        <div class="pt-3 pb-2 px-4 text-center">
                            <div class="row g-0 gap-2">
                                <img src="{{ asset('assets/svg/setting.svg') }}" alt=""
                                    style="width: 30px; height: auto;" class="mx-auto">
                                <p class="mb-0 fw-light" style="font-size: 16px;">
                                    Altere seu nome, e-mail ou senha
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-black py-2 text-center w-100 rounded-bottom-4">
                        <small>Meu Perfil</small>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection