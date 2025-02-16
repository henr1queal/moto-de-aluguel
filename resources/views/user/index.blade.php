@extends('layouts.bootstrap')

@section('head')
    <style>
        .copy-btn {
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
                <h1 style="font-size: 20px;">Usuários</h1>
            </div>
        </div>

        @foreach ($users as $user)
            <div class="row mb-3" style="border-bottom: 1px solid white; padding-bottom: 8px;">
                <div class="col-12 d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                    data-bs-target="#{{ $user->id }}" aria-expanded="false" aria-controls="{{ $user->id }}">
                    <span>{{ $user->name }}</span>
                    <span class="badge {{ $user->superuser ? 'text-bg-success' : 'text-bg-secondary' }} rounded-3 fs-6"
                        style="border: 1px solid white;">{{ $user->superuser ? 'Admin' : 'Supervisor' }}</span>
                </div>
            </div>
            @if ($loop->iteration !== 1)
                <!-- Collapse correspondente -->
                <div class="collapse mb-3" id="{{ $user->id }}">
                    <div class="card card-body" style="background-color: #343a40; color: white;">
                        <div class="row">
                            <div class="col">
                                <p><strong>Email:</strong> {{ $user->email }}</p>
                            </div>
                        </div>

                        <!-- Formulário para alterar permissão -->
                        <form method="POST" action="{{ route('user.toggle-role', ['user' => $user->id]) }}">
                            @csrf
                            <button type="submit" class="btn btn-info btn-sm">
                                Tornar {{ $user->superuser ? 'Supervisor' : 'Admin' }}
                            </button>
                        </form>

                        <!-- Botão para gerar nova senha -->
                        <form method="POST" action="{{ route('user.new-password', ['user' => $user->id]) }}"
                            class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">Gerar Nova Senha</button>
                        </form>
                    </div>
                </div>
            @endif
        @endforeach


        <!-- Formulário para adicionar novo usuário -->
        <div class="row mt-5">
            <div class="col text-center">
                <h1 style="font-size: 20px;">Adicionar usuário</h1>
            </div>
            <div class="col-12 mt-3">
                <form action="{{ route('user.store') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome:</label>
                        <input type="text" class="form-control text-white bg-transparent" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail:</label>
                        <input type="email" class="form-control text-white bg-transparent" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <input type="radio" class="btn-check" name="superuser" id="success-outlined" autocomplete="off"
                            value="1" checked>
                        <label class="btn btn-sm btn-outline-success" for="success-outlined">Administrador</label>

                        <input type="radio" class="btn-check" name="superuser" id="danger-outlined" autocomplete="off"
                            value="0">
                        <label class="btn btn-sm btn-outline-info" for="danger-outlined">Supervisor</label>
                    </div>
                    <button type="submit" class="btn btn-primary mt-4">Adicionar</button>
                </form>
            </div>
        </div>

        <!-- Exibe e-mail e senha gerada -->
        @if (session('email'))
            <div class="alert alert-success mt-3">
                <strong>E-mail:</strong> {{ session('email') }}<br>
                <strong>Senha:</strong> <span id="passwordText">{{ session('password') }}</span>
                <button class="btn btn-sm btn-secondary ms-2 copy-btn" onclick="copyToClipboard()">Copiar</button>
            </div>
        @endif
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
        });

        function copyToClipboard() {
            var passwordText = document.getElementById("passwordText").textContent;
            navigator.clipboard.writeText(passwordText).then(() => {
                alert("Senha copiada!");
            });
        }
    </script>
@endsection
