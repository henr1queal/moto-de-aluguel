@extends('layouts.bootstrap')

@section('content')
    <div class="container text-center">
        <h1>🔔 Notificações e Lembretes</h1>

        <h3 class="text-center mt-5 pt-lg-4">Pagamentos</h3>

        <!-- Notificações de Pagamentos -->
        @foreach (['hoje' => '⚠️ Hoje', 'faltam_1_dia' => '⏳ Falta  1 dia', 'faltam_2_dias' => '⌛ Faltam 2 dias', 'vencidos' => '❌ Vencidos'] as $key => $label)
            <div class="notificacao-secao mt-5">
                <h6>{{ $label }}</h6>
                @if (count($notifications[$key]) > 0)
                    <ul class="list-group">
                        @foreach ($notifications[$key] as $payment)
                            <a href="{{ route('rental.show', ['rental' => $payment->rental->id]) }}"
                                class="list-group-item bg-transparent text-white">
                                <strong>{{ $payment->rental->landlord_name }}</strong>
                                <br><strong>{{ $payment->rental->vehicle->brand }} {{ $payment->rental->vehicle->model }}
                                    ({{ $payment->rental->vehicle->license_plate }})
                                </strong>
                                <br><strong>Pagamento:</strong> R$ {{ number_format($payment->cost, 2, ',', '.') }} -
                                <strong>Data:</strong>
                                {{ \Carbon\Carbon::parse($payment->payment_date)->translatedFormat('d/m/Y') }}
                            </a>
                        @endforeach
                    </ul>
                @else
                    <p class="text-white mb-0">Tudo limpo por aqui.</p>
                @endif
            </div>
        @endforeach

        <h3 class="text-center mt-5 pt-lg-4">Trocas de óleo e manutenções</h3>

        <!-- Notificações de Trocas de Óleo -->
        <div class="notificacao-secao mt-5">
            <h4 class="mb-2">🛢️ Próximas Trocas de Óleo</h4>
            @if (count($oilChanges) > 0)
                <ul class="list-group">
                    @foreach ($oilChanges as $oil)
                        <a href="{{ route('rental.show', ['rental' => $oil['rental']]) }}"
                            class="list-group-item bg-transparent text-white">
                            <strong>{{ $oil['person'] }}</strong>
                            <br>🏍️ <strong>{{ $oil['veiculo'] }} ({{ $oil['placa'] }})</strong> -
                            <span class="{{ $oil['km_restante'] <= 0 ? 'text-danger' : 'text-warning' }}">
                                {{ abs($oil['km_restante']) }} km
                            </span>
                        </a>
                    @endforeach
                </ul>
            @else
                <p class="text-white mb-0">Tudo limpo por aqui.</p>
            @endif
        </div>

        <!-- Notificações de Revisões -->
        <div class="notificacao-secao mt-5">
            <h4 class="mb-2">🔧 Próximas Revisões</h4>
            @if (count($revisions) > 0)
                <ul class="list-group">
                    @foreach ($revisions as $revision)
                        <a href="{{ route('rental.show', ['rental' => $revision['rental']]) }}"
                            class="list-group-item bg-transparent text-white">
                            <strong>{{ $revision['person'] }}</strong>
                            <br>🏍️ <strong>{{ $revision['veiculo'] }} ({{ $revision['placa'] }})</strong> -
                            <span class="{{ $revision['km_restante'] <= 0 ? 'text-danger' : 'text-warning' }}">
                                {{ abs($revision['km_restante']) }} km
                            </span>
                        </a>
                    @endforeach
                </ul>
            @else
                <p class="text-white mb-0">Tudo limpo por aqui.</p>
            @endif
        </div>

        <h3 class="text-center mt-5 pt-lg-4">✉️ Multas</h3>

        <div class="notificacao-secao mt-5">
            <div id="quinta-feira-container">
                <div class="alert alert-info mb-0" id="quinta-feira-alert" style="display: none;">
                    📌 <strong>Lembrete semanal:</strong> <br>Verificar possíveis multas.
                    <br><button class="btn btn-sm btn-success" onclick="marcarQuintaFeiraComoLida()">Já verifiquei
                        todas!</button>
                </div>
            </div>
            <p class="text-white mb-0" id="tudo-limpo" style="display: none;">Tudo limpo por aqui.</p>
        </div>
    </div>
@endsection

<style>
    .notificacao-secao {
        margin-top: 20px;
        padding: 15px;
        border-radius: 5px;
        border: 1px solid white;
    }
</style>

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let quintaFeiraLida = localStorage.getItem("quinta_feira_lida");

            let quintaFeiraContainer = document.getElementById("quinta-feira-container");
            let quintaFeiraAlert = document.getElementById("quinta-feira-alert");
            let tudoLimpoText = document.getElementById("tudo-limpo");

            // Se `quinta_feira_lida` for "false", mostrar o alerta
            if (quintaFeiraLida === "false") {
                if (quintaFeiraAlert) quintaFeiraAlert.style.display = "block";
            } else {
                // Se `quinta_feira_lida` não for false, esconder alerta e mostrar "Tudo limpo"
                if (quintaFeiraContainer) quintaFeiraContainer.style.display = "none";
                if (tudoLimpoText) tudoLimpoText.style.display = "block";
            }
        });

        // Marcar quinta-feira como lida
        function marcarQuintaFeiraComoLida() {
            localStorage.setItem("quinta_feira_lida", "true");

            let quintaFeiraContainer = document.getElementById("quinta-feira-container");
            let tudoLimpoText = document.getElementById("tudo-limpo");

            if (quintaFeiraContainer) quintaFeiraContainer.style.display = "none";
            if (tudoLimpoText) tudoLimpoText.style.display = "block";

            atualizarContagemNotificacoes();
        }

        // Atualiza a contagem total de notificações no localStorage e no badge
        function atualizarContagemNotificacoes() {
            fetch('/notificacoes/contagem')
                .then(response => response.json())
                .then(data => {
                    let total = data.total;
                    let quintaFeiraLida = localStorage.getItem("quinta_feira_lida");

                    // Se `quinta_feira_lida === false`, soma +1 ao total
                    if (quintaFeiraLida === "false") {
                        total++;
                    }

                    localStorage.setItem("total_notifications", total);
                    atualizarBadgeNotificacoes();
                });
        }

        // Atualizar o badge no menu
        function atualizarBadgeNotificacoes() {
            let badge = document.getElementById("badge-notificacoes");
            let total = parseInt(localStorage.getItem("total_notifications"), 10) || 0;

            if (total > 0) {
                badge.textContent = total;
                badge.style.display = "inline-block";
            }
        }
    </script>
@endsection
