@extends('layouts.bootstrap')

@section('head')
    <style>
        /* Estilização dos cards */
        .finance-card {
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
        <div class="row mb-5 justify-content-center gap-md-3">
            <div class="col col-md-auto text-center">
                <h5>Mês</h5>
                <select id="monthFilter" class="form-select bg-transparent">
                    @foreach ($months as $month)
                        <option class="text-black" value="{{ $month }}"
                            {{ $selectedMonth == $month ? 'selected' : '' }}>
                            {{ ucfirst(\Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y')) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col col-md-auto text-center">
                <h5>Semana</h5>
                <select id="weekFilter" class="form-select bg-transparent"></select>
            </div>
        </div>

        @foreach (['em_andamento' => 'Locações Ativas'] as $key => $title)
            <div class="row justify-content-center">
                <div class="col-12 col-md-4 mb-4 text-center @if (!$loop->last) mb-4 @endif">
                    <div class="finance-card bg-{{ $key }} rounded-4 p-2" style="border: 1px solid white">
                        <h5 class="finance-title text-center mb-0">Semanal</h5>
                        <hr class="my-2">
                        <p><strong>Pagos na semana selecionada:</strong> <br><span class="amount text-success"
                                id="receivedWeek{{ ucfirst($key) }}">R$ 0,00</span></p>
                        <p class="mb-0"><strong>Pendentes na semana selecionada:</strong> <br><span class="amount text-danger"
                                id="notReceivedWeek{{ ucfirst($key) }}">R$ 0,00</span></p>
                    </div>
                </div>
                <div class="col-12 col-md-4 mb-4 text-center">
                    <div class="finance-card bg-{{ $key }} rounded-4 p-2" style="border: 1px solid white">
                        <h5 class="finance-title text-center mb-0">Mensal</h5>
                        <hr class="my-2">
                        <p><strong>Pagos no mês selecionado:</strong> <br><span class="amount text-success"
                                id="receivedMonth{{ ucfirst($key) }}">R$ 0,00</span></p>
                        <p class="mb-0"><strong>Pendentes no mês selecionado:</strong> <br><span class="amount text-danger"
                                id="notReceivedMonth{{ ucfirst($key) }}">R$ 0,00</span></p>
                    </div>
                </div>
                <div class="col-12 col-md-4 text-center">
                    <div class="finance-card bg-{{ $key }} rounded-4 p-2" style="border: 1px solid white">
                        <h5 class="finance-title text-center mb-0">Valores totais</h5>
                        <hr class="my-2">
                        <p><strong>Total Pago:</strong> <br><span class="amount text-success"
                                id="totalReceived{{ ucfirst($key) }}">R$ 0,00</span></p>
                        <p class="mb-0"><strong>Total Pendentes:</strong> <br><span class="amount text-danger"
                                id="totalNotReceived{{ ucfirst($key) }}">R$ 0,00</span></p>
                    </div>
                </div>
            </div>
            <hr>
        @endforeach

        <div class="row gap-4 gap-lg-0">
            <div class="col-12 text-center">
                <h4 class="text-white my-lg-4">Semana detalhada:</h4>
            </div>
            @foreach ($weekData as $key => $data)
                <div class="col-12 col-lg text-center">
                    <div class="border h-100 pb-2 rounded-2" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#detailsModal"
                        data-day="{{ $data['day'] }}" data-content="{{ json_encode($data) }}">
                        <div class="bg-primary rounded-top-2">{{ $data['day'] }}</div>
                        <div class="mt-2">
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <strong><span>Total:</strong></span> <span
                                    class="badge rounded-2 text-bg-light mb-1">{{ $data['total'] }}</span>
                            </div>
                            @if ($data['pendentes'])
                                <div class="text-primary">Pendentes: {{ $data['pendentes'] }}</div>
                            @endif
                            @if ($data['pagos'])
                                <div class="text-success">Pagos: {{ $data['pagos'] }}</div>
                            @endif
                            @if ($data['vencidos'])
                                <div class="text-danger">Vencidos: {{ $data['vencidos'] }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Modal Único -->
        <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background-color: rgb(28 28 28);">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailsModalLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="modalContent"></div>
                    </div>
                </div>
            </div>
        </div>
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
                        ["em_andamento"].forEach(status => {
                            document.getElementById(`totalReceived${capitalize(status)}`).textContent =
                                `R$ ${(data[status]?.total?.received ?? 0).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;
                            document.getElementById(`totalNotReceived${capitalize(status)}`)
                                .textContent =
                                `R$ ${(data[status]?.total?.not_received ?? 0).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;

                            document.getElementById(`receivedMonth${capitalize(status)}`).textContent =
                                `R$ ${(data[status]?.month?.received ?? 0).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;
                            document.getElementById(`notReceivedMonth${capitalize(status)}`)
                                .textContent =
                                `R$ ${(data[status]?.month?.not_received ?? 0).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;

                            document.getElementById(`receivedWeek${capitalize(status)}`).textContent =
                                `R$ ${(data[status]?.week?.received ?? 0).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;
                            document.getElementById(`notReceivedWeek${capitalize(status)}`)
                                .textContent =
                                `R$ ${(data[status]?.week?.not_received ?? 0).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;
                        });
                    })
                    .catch(error => console.error('Erro ao carregar os dados:', error));
            }

            function updateWeekFilter() {
                const selectedMonth = monthFilter.value;
                fetch(`/financas/semanas?month=${selectedMonth}`)
                    .then(response => response.json())
                    .then(weeks => {
                        weekFilter.innerHTML = "";
                        weeks.forEach(week => {
                            let option = document.createElement("option");
                            option.value = week.week;
                            option.textContent = week.range;
                            option.classList.add("text-black");
                            weekFilter.appendChild(option);
                        });
                        weekFilter.value = weeks[0]?.week || "";
                        fetchTotals(selectedMonth, weekFilter.value);
                    });
            }

            monthFilter.addEventListener("change", updateWeekFilter);
            weekFilter.addEventListener("change", () => fetchTotals(monthFilter.value, weekFilter.value));
            updateWeekFilter();

            function capitalize(str) {
                return str.charAt(0).toUpperCase() + str.slice(1);
            }

            const modal = document.getElementById("detailsModal");

            modal.addEventListener("show.bs.modal", function(event) {
                const button = event.relatedTarget;
                const day = button.getAttribute("data-day");
                const content = JSON.parse(button.getAttribute("data-content"));

                document.getElementById("detailsModalLabel").innerText = day;

                let modalHtml = '';

                ['pagos', 'pendentes', 'vencidos'].forEach(category => {
                    if (content[category] > 0 || content.rentals[category].length > 0) {
                        modalHtml +=
                            `<h6 class="mt-2 ${category === 'pagos' ? 'text-success' : (category === 'pendentes' ? 'text-primary' : 'text-danger')}">${capitalize(category)}</h6><ul class="list-unstyled">`;

                        content.rentals[category].forEach(rental => {
                            console.log(rental)
                            modalHtml +=
                                `<li class="mt-3"><a href="/locacoes/${rental.id}" class="text-white">${rental.name} — R$ ${rental.cost}</a></li>`;
                        });

                        modalHtml += '</ul>';
                    }
                });

                document.getElementById("modalContent").innerHTML = modalHtml || '<p>Nenhum item.</p>';
            });

        });
    </script>
@endsection
