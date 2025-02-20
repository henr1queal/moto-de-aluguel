<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alugue uma moto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <style>
        input,
        select {
            color: #FFFFFF !important;
        }

        body {
            background-color: #242424;
            ;
            font-family: "Roboto", serif;
            color: #FFFFFF;
        }

        .content {
            overflow-y: auto;
        }

        .menu-button {
            margin-top: 10px;
        }
        
        .menu-button a {
            max-height: 44.3px;            
        }

        .menu {
            background-color: #1f1f1f;
            border-top: 1px solid #303030;
            position: fixed;
            left: 0;
            width: 100%;
            z-index: 9999;
            /* Garante que o menu fique acima de outros conteúdos */
        }

        @media(min-width: 1200px) {
            .fs-1 {
                font-size: 1.8rem !important;
            }
        }
    </style>
    @yield('head')
</head>

<body>
    <div class="content">
        <div style="height: 50px"></div>
        @yield('content')
        <div style="height: 85px;"></div>
    </div>
    @yield('options-button')
    <div class="menu bottom-0 rounded-top-5 pt-2">
        <div class="row justify-content-evenly justify-content-lg-center g-0 align-items-end gap-lg-5">
            <a href="{{ route('home') }}" class="col-auto text-center text-white text-decoration-none">
                <div style="height: 36px;" class="d-flex align-items-end">
                    <img src="{{ asset('assets/svg/home.svg') }}" alt="" class="d-block mx-auto mb-1 h-auto"
                        style="width: 28px;">
                </div>
                <small>Home</small>
            </a>

            <a href="{{ route('vehicle.index') }}" class="col-auto text-center text-white text-decoration-none">
                <div style="height: 36px;" class="d-flex align-items-end">
                    <img src="{{ asset('assets/svg/bike.svg') }}" alt="" class="d-block mx-auto h-auto"
                        style="width: 36px;">
                </div>
                <small>Motocicletas</small>
            </a>

            <a href="{{ route('rental.index') }}" class="col-auto text-center text-white text-decoration-none">
                <div style="height: 36px;" class="d-flex align-items-end">
                    <img src="{{ asset('assets/svg/book.svg') }}" alt="" class="d-block mx-auto mb-1 img-fluid"
                        style="width: 26px;">
                </div>
                <small>Locações</small>
            </a>
            <a href="{{ route('payment.index') }}"
                class="col-auto text-center text-white text-decoration-none d-none d-md-block">
                <div style="height: 36px;" class="d-flex align-items-end">
                    <img src="{{ asset('assets/svg/money.svg') }}" alt="" class="d-block mx-auto mb-1 img-fluid"
                        style="width: 26px;">
                </div>
                <small>Finanças</small>
            </a>
            <a href="{{ route('notifications') }}" class="col-auto text-center text-white text-decoration-none">
                <div style="height: 36px;" class="d-flex align-items-end position-relative">
                    <img src="{{ asset('assets/svg/notification.svg') }}" alt=""
                        class="d-block mx-auto mb-1 img-fluid" style="width: 26px;">
                    <span id="badge-notificacoes"
                        class="translate-middle badge rounded-pill bg-danger position-absolute top-0 end-0 mt-1"
                        style="font-size: 0.75rem; padding: 4px 6px;">
                        0
                    </span>
                </div>
                <small>Lembretes</small>
            </a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            setTimeout(async () => {
                try {
                    // Recupera a contagem atual de notificações do localStorage
                    let storedTotal = parseInt(localStorage.getItem("total_notifications"), 10) || 0;
                    let quintaFeiraLida = localStorage.getItem("quinta_feira_lida");

                    // Faz a requisição para buscar novas notificações
                    const response = await fetch('/notificacoes/contagem');
                    const data = await response.json();

                    let total = data.total;
                    let quintaFeira = data.quinta_feira;

                    // Se quintaFeiraLida for "false", adiciona +1 ao total
                    if (quintaFeiraLida === "false") {
                        total++;
                    }

                    // Gerencia o estado da notificação de quinta-feira
                    if (!quintaFeira) {
                        if (quintaFeiraLida) {
                            localStorage.setItem("quinta_feira_lida", null);
                        }
                    } else {
                        if (quintaFeiraLida === "null") {
                            localStorage.setItem("quinta_feira_lida", "false");
                            total++;
                        }
                    }

                    // Se a nova contagem for maior, atualiza. Caso contrário, mantém a antiga

                    // Salva a contagem no localStorage
                    localStorage.setItem("total_notifications", total);
                    localStorage.setItem("quinta_feira", quintaFeira);
                    // Atualiza o badge de notificações
                    atualizarBadgeNotificacoes();
                } catch (error) {
                    console.error("Erro ao buscar notificações:", error);
                }
            }, 1000);
        });

        function atualizarBadgeNotificacoes() {
            let badge = document.getElementById("badge-notificacoes");
            let total = parseInt(localStorage.getItem("total_notifications"), 10) || 0;

            badge.textContent = total;
            badge.style.display = "inline-block";
        }

        // Atualiza o badge ao carregar a página, antes mesmo da requisição
        atualizarBadgeNotificacoes();
    </script>
    @yield('scripts')
</body>

</html>
