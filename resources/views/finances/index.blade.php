@extends('layouts.bootstrap')

@section('head')
    <style>
        /* Estilização dos cards */
        .finance-card rounded-4 p-2  style="border: 1px solid white"{
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
        }

        .finance-title {
            font-size: 18px;
            font-weight: bold;
        }

        .bg-andamento {
            background-color: #e0f7fa;
            color: #00796b;
            border-left: 5px solid #00796b;
        }

        .bg-cancelado {
            background-color: #ffebee;
            color: #c62828;
            border-left: 5px solid #c62828;
        }

        .bg-finalizado {
            background-color: #e8f5e9;
            color: #2e7d32;
            border-left: 5px solid #2e7d32;
        }

        .amount {
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row g-0 mb-3">
            <div class="col text-center">
                <h4 class="fw-bold">Pagamentos</h4>
            </div>
        </div>

        <!-- Filtro por MÊS -->
        <div class="row mb-3">
            <div class="col">
                <h5>Mensal</h5>
                <select id="monthFilter" class="form-select bg-transparent">
                    @foreach ($months as $month)
                        <option class="text-black" value="{{ $month }}" {{ $selectedMonth == $month ? 'selected' : '' }}>
                            {{ ucfirst(\Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y')) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Filtro por SEMANA -->
        <div class="row mb-4">
            <div class="col">
                <h5>Semanal</h5>
                <select id="weekFilter" class="form-select bg-transparent"></select>
            </div>
        </div>

        @foreach (['em_andamento' => 'Locações Ativas', 'cancelados' => 'Locações Canceladas', 'finalizados' => 'Locações Finalizadas'] as $key => $title)
            <div class="row justify-content-center">
                <div class="col-8 col-md-4 mb-4">
                    <div class="finance-card bg-{{ $key }} rounded-4 p-2" style="border: 1px solid white">
                        <h5 class="finance-title text-center">{{ $title }}</h5>
                        <hr>
                        <p><strong>Total Pago:</strong> <br><span class="amount text-success" id="totalReceived{{ ucfirst($key) }}">R$ 0,00</span></p>
                        <p class="mb-0"><strong>Total Pendentes:</strong> <br><span class="amount text-danger" id="totalNotReceived{{ ucfirst($key) }}">R$ 0,00</span></p>
                    </div>
                </div>
                <div class="col-8 col-md-4 mb-4">
                    <div class="finance-card bg-{{ $key }} rounded-4 p-2" style="border: 1px solid white">
                        <h5 class="finance-title text-center">Mensal</h5>
                        <hr>
                        <p><strong>Pagos no Mês:</strong> <br><span class="amount text-success" id="receivedMonth{{ ucfirst($key) }}">R$ 0,00</span></p>
                        <p class="mb-0"><strong>Pendentes no Mês:</strong> <br><span class="amount text-danger" id="notReceivedMonth{{ ucfirst($key) }}">R$ 0,00</span></p>
                    </div>
                </div>
                <div class="col-8 col-md-4 @if(!$loop->last) mb-4 @endif">
                    <div class="finance-card bg-{{ $key }} rounded-4 p-2" style="border: 1px solid white">
                        <h5 class="finance-title text-center">Semanal</h5>
                        <hr>
                        <p><strong>Pagos na Semana:</strong> <br><span class="amount text-success" id="receivedWeek{{ ucfirst($key) }}">R$ 0,00</span></p>
                        <p class="mb-0"><strong>Pendentes na Semana:</strong> <br><span class="amount text-danger" id="notReceivedWeek{{ ucfirst($key) }}">R$ 0,00</span></p>
                    </div>
                </div>
            </div>
            <hr>
        @endforeach
    </div>
@endsection


@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const monthFilter = document.getElementById("monthFilter");
        const weekFilter = document.getElementById("weekFilter");

        function fetchTotals(month, week) {
            fetch(`/financas/totais?month=${month}&week=${week}`)
                .then(response => response.json())
                .then(data => {
                    ['em_andamento', 'cancelados', 'finalizados'].forEach(status => {
                        document.getElementById(`totalReceived${capitalize(status)}`).textContent =
                            `R$ ${(data[status].total.received ?? 0).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;
                        document.getElementById(`totalNotReceived${capitalize(status)}`).textContent =
                            `R$ ${(data[status].total.not_received ?? 0).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;

                        document.getElementById(`receivedMonth${capitalize(status)}`).textContent =
                            `R$ ${(data[status].month.received ?? 0).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;
                        document.getElementById(`notReceivedMonth${capitalize(status)}`).textContent =
                            `R$ ${(data[status].month.not_received ?? 0).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;

                        document.getElementById(`receivedWeek${capitalize(status)}`).textContent =
                            `R$ ${(data[status].week.received ?? 0).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;
                        document.getElementById(`notReceivedWeek${capitalize(status)}`).textContent =
                            `R$ ${(data[status].week.not_received ?? 0).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;
                    });
                })
                .catch(error => console.error('Erro ao carregar os dados:', error));
        }

        function capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        function updateWeekFilter() {
            const selectedMonth = monthFilter.value;
            fetch(`/financas/semanas?month=${selectedMonth}`)
                .then(response => response.json())
                .then(weeks => {
                    weekFilter.innerHTML = "";
                    weeks.forEach((week, index) => {
                        let option = document.createElement("option");
                        option.value = week;
                        option.textContent = `Semana ${index + 1}`;
                        option.classList.add("text-black");
                        weekFilter.appendChild(option);
                    });

                    if (weeks.length > 0) {
                        weekFilter.value = weeks[0];
                    }

                    fetchTotals(monthFilter.value, weekFilter.value);
                });
        }

        monthFilter.addEventListener("change", updateWeekFilter);
        weekFilter.addEventListener("change", () => fetchTotals(monthFilter.value, weekFilter.value));

        updateWeekFilter();
    });
</script>

@endsection
