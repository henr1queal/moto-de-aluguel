@extends('layouts.bootstrap')
@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
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
            transform: translateX(-50%);
            top: 33%;
        }

        .vehicle {
            border: 1px solid white;
            cursor: pointer;
        }

        .content {
            height: calc(100dvh - 60px - 70px);
        }

        .choices__inner {
            border-radius: 0.5rem;
        }

        .choices__inner,
        .choices__input,
        .choices__list--dropdown {
            background-color: #212529 !important;
        }

        /* Texto azul quando passar o mouse */
        .choices__list--dropdown .choices__item--selectable:hover {
            color: black;
        }

        /* Texto preto no item destacado automaticamente (sem hover) */
        .choices__list--dropdown .choices__item--selectable.is-highlighted {
            background-color: gray;
            color: black;
        }

        .choices__list--multiple .choices__item {
            background-color: gray;
            border-color: #ffffff;
        }

        .choices__input::placeholder {
            color: #FFFFFF !important;
        }

        #searchPart::placeholder {
            color: gray !important;
        }

        #maintenance-modal {
            min-height: 50dvh;
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
                <h1 style="font-size: 20px;">Manutenções</h1>
            </div>
        </div>
        <div class="row mt-4 g-3 mb-3">
            @if ($myVehicles->count() === 0)
                <div class="col-12 text-center">
                    <span class="text-secondary">Adicione um veículo para vê-lo aqui.</span>
                </div>
            @else
                @foreach ($myVehicles as $vehicle)
                    <div class="col-6 col-lg-2 mb-4 mb-lg-5">
                        <div class="h-100 text-decoration-none text-white position-relative vehicle-card"
                            data-id="{{ $vehicle->id }}" data-plate="{{ $vehicle->license_plate }}"
                            data-actual-km="{{ $vehicle->actual_km }}"
                            @if ($vehicle->actualRental) data-status="{{ $vehicle->actualRental->landlord_name }}" @else data-status="Não alugada" @endif>
                            <div
                                class="h-100 vehicle rounded-2 text-center pt-1 position-relative d-flex flex-column justify-content-between">
                                <div class="px-1">
                                    <small>{{ $vehicle->brand }}</small>
                                    <br><small>{{ $vehicle->model }}</small>
                                    <br><small>ANO: {{ $vehicle->year }}</small>
                                    <br>
                                    @if ($vehicle->actualRental)
                                        <small class="text-warning">ALUGADA</small>
                                    @else
                                        <small class="text-success">DISPONÍVEL</small>
                                    @endif
                                </div>
                                <div class="position-relative">
                                    <img src="{{ asset('assets/svg/placa.svg') }}" alt="" class="w-100">
                                    <span class="fs-4 w-100 text-black position-absolute info-placa">
                                        <strong>{{ $vehicle->license_plate }}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="modal fade" id="vehicleModal" tabindex="-1" aria-labelledby="pecasModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="pecasModal"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="row g-0 p-3 text-end align-items-center">
                        <div class="col text-center">
                            <input type="text" id="searchPart"
                                class="form-control form-control-sm bg-transparent text-white" placeholder="Buscar peça..."
                                oninput="debouncedSearchParts()" />
                        </div>
                        <div class="col">
                            <button class="btn btn-primary btn-sm border-1 border-white" onclick="fetchPartsList()"
                                data-bs-toggle="modal" data-bs-target="#newMaintenanceModal">Nova manutenção</button>
                        </div>
                    </div>
                    <div class="row justify-content-center g-0 mt-2 mb-3">
                    </div>
                    <h5 class="text-center">HISTÓRICO</h5>
                    <div class="row text-center g-0 p-4">
                        <div class="col" id="pecasModalBody">
                            <div class="spinner-border text-light" role="status">
                                <span class="visually-hidden">Carregando...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="newMaintenanceModal" tabindex="-1" aria-labelledby="newMaintenanceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content bg-dark text-white" id="maintenance-modal">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="newMaintenanceModalLabel">Nova manutenção</h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-toggle="modal"
                        data-bs-target="#vehicleModal" onclick="removeActiveItems()"></button>
                </div>
                <form action="{{ route('maintenance.store') }}" method="POST" class="modal-body">
                    @csrf
                    <input type="hidden" name="vehicle_id" id="vehicle_id">
                    <div class="row mt-1">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-12 text-start">
                                    <h6>Detalhes</h6>
                                </div>
                                <div class="col-auto text-start">
                                    <label for="date" class="form-label fw-light">Data da troca<span
                                            class="text-danger"><strong>*</strong></span></label>
                                    <input type="date" class="form-control bg-transparent" id="date" required
                                        value="{{ date('Y-m-d') }}" name="date" min="1" max="999999">
                                </div>
                            </div>
                        </div>
                        <div class="col text-start mt-4">
                            <h6>Adicionar itens<span class="text-danger"><strong>*</strong></span></h6>
                        </div>
                        <div class="col-12 mb-4">
                            <select class="form-control bg-transparent text-black" id="parts" multiple>
                            </select>
                        </div>
                    </div>
                    <div id="item-details-container"></div>
                    <div id="submit-container" class="text-end mt-3" style="display: none;">
                        <button type="submit" class="btn btn-success w-100 border-white border-1">Salvar todos os
                            itens</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        let choices;
        let debounceTimer;

        function debouncedSearchParts() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const vehicleId = document.getElementById('vehicle_id').value;
                const search = document.getElementById('searchPart').value;
                fetchPartsChanged(vehicleId, 1, search);
            }, 1000); // Espera 400ms após digitação
        }

        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                localStorage.removeItem('listAllParts');
            @endif

            const toastElements = document.querySelectorAll('.toast');
            toastElements.forEach(toastElement => {
                const toast = new bootstrap.Toast(toastElement);
                toast.show();
            });

            const vehicleCards = document.querySelectorAll('.vehicle-card');
            const vehicleModal = new bootstrap.Modal(document.getElementById('vehicleModal'));
            const pecasModalBody = document.getElementById('pecasModalBody');

            vehicleCards.forEach(card => {
                card.addEventListener('click', () => {
                    const vehicleId = card.getAttribute('data-id');
                    document.getElementById('vehicle_id').value = vehicleId;

                    const vehicleplate = card.getAttribute('data-plate');
                    const vehicleStatus = card.getAttribute('data-status');
                    const actualKm = card.getAttribute('data-actual-km');
                    const pecasModal = document.getElementById('pecasModal');
                    const actualKmField = document.getElementById('actual_km');
                    pecasModal.innerHTML = `${vehicleplate} - ${vehicleStatus}`;
                    // Abre o modal com loading
                    pecasModalBody.innerHTML = `
                    <div class="text-center py-5">
                        <div class="spinner-border text-light" role="status">
                            <span class="visually-hidden">Carregando...</span>
                        </div>
                    </div>
                `;
                    vehicleModal.show();

                    fetchPartsChanged(vehicleId);

                    disableSubmit = () => {
                        const submitButton = document.getElementById('submit');
                        submitButton.disabled = true;
                    }
                });
            });

            const element = document.getElementById('parts');

            choices = new Choices(element, {
                removeItemButton: true,
                placeholder: true,
                placeholderValue: 'Digite ou selecione',
                searchEnabled: true,
                shouldSort: false,
                addItems: true,
                addChoices: true,
                duplicateItemsAllowed: false,
                renderSelectedChoices: 'auto',
                noChoicesText: 'Nenhum item a ser selecionado',
                itemSelectText: 'Clique para selecionar',
                customAddItemText: 'Selecione um item nunca selecionado.',
                addItemText: (value) => `Adicionar "${value}"`, // mostra texto clicável
                addItemFilter: (value) => {
                    const newLabel = value.trim().toLowerCase();

                    // Verifica se o label já foi adicionado
                    const existingItems = choices.getValue();

                    return !existingItems.some(item => {
                        const label = (item.label || item.value || '').toString().trim()
                            .toLowerCase();
                        return label === newLabel;
                    });
                }
            });

            const container = document.getElementById('item-details-container');

            // Mensagem inicial
            const emptyMessage = document.createElement('div');
            emptyMessage.id = 'no-items-message';
            emptyMessage.className = 'text-secondary fst-italic';
            emptyMessage.textContent = 'Nenhum item foi inserido ainda.';
            container.appendChild(emptyMessage);

            function updateEmptyMessage() {
                const hasItems = container.querySelectorAll('[data-value]').length > 0;
                emptyMessage.style.display = hasItems ? 'none' : 'block';

                const submitContainer = document.getElementById('submit-container');
                submitContainer.style.display = hasItems ? 'block' : 'none';
            }

            element.addEventListener('addItem', function(event) {
                const value = event.detail.value;
                const label = event.detail.label;

                // Evita duplicação por precaução
                if (container.querySelector(`[data-value="${value}"]`)) return;

                const block = document.createElement('div');
                block.classList.add('row', 'mt-2');
                block.dataset.value = value;

                block.innerHTML = `
      <div class="col-12 mb-2">
        <p class="text-white mb-0">${label}</p>
      </div>
      <div class="col-auto text-start">
        <label class="form-label fw-light">Tipo<span class="text-danger"><strong>*</strong></span></label>
        <select name="items[${value}][type]" class="form-select bg-transparent" required>
          <option class="text-black" value="UN.">UN.</option>
          <option class="text-black" value="ML.">ML.</option>
          <option class="text-black" value="LT.">LT.</option>
        </select>
      </div>
      <div class="col-auto text-start">
        <label class="form-label fw-light">Quantidade<span class="text-danger"><strong>*</strong></span></label>
        <input type="number" class="form-control bg-transparent" name="items[${value}][quantity]" required min="1" max="999999" value="1">
      </div>
      <div class="col text-start">
        <label class="form-label fw-light">Valor<span class="text-danger"><strong>*</strong></span></label>
        <input type="number" class="form-control bg-transparent" name="items[${value}][cost]" required max="999999">
      </div>
      <div class="col-12 text-start mt-3">
        <label class="form-label fw-light">Observação</label>
        <textarea class="form-control bg-transparent text-white" name="items[${value}][observation]" rows="3"></textarea>
      </div>
      <hr class="mt-4">
    `;

                container.appendChild(block);
                updateEmptyMessage();
            });

            element.addEventListener('removeItem', function(event) {
                const value = event.detail.value;
                const block = container.querySelector(`[data-value="${value}"]`);
                if (block) block.remove();
                updateEmptyMessage();
            });
        });

        function removeActiveItems() {
            if (choices) {
                choices.removeActiveItems(); // remove do select visualmente
            }

            const container = document.getElementById('item-details-container');
            container.innerHTML = ''; // remove todos os blocos de inputs

            const emptyMessage = document.createElement('div');
            emptyMessage.id = 'no-items-message';
            emptyMessage.className = 'text-secondary fst-italic';
            emptyMessage.textContent = 'Nenhum item foi inserido ainda.';
            container.appendChild(emptyMessage);
        }


        function formattedDate(dateString) {
            const date = new Date(dateString);
            const day = String(date.getUTCDate()).padStart(2, '0');
            const month = String(date.getUTCMonth() + 1).padStart(2, '0'); // Mês começa em 0
            const year = date.getUTCFullYear();
            return `${day}/${month}/${year}`;
        }

        function fetchPartsChanged(vehicleId, page = 1, search = '') {
            const apiUrl = `/manutencoes/veiculo/${vehicleId}?page=${page}&search=${encodeURIComponent(search)}`;
            fetch(apiUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro ao carregar os dados');
                    }
                    return response.json();
                })
                .then(data => {
                    const resultsContainer = document.getElementById('pecasModalBody');

                    if (data.total === 0) {
                        resultsContainer.innerHTML = `
                    <div class="text-center p-4">
                        <p class="text-secondary">Nenhuma peça foi encontrada.</p>
                    </div>
                `;
                        return;
                    }

                    let html = '';

                    data.data.forEach((item, index) => {
                        console.log(item)
                        const collapseId = `peca-collapse-${index}`;

                        html += `
                    <div class="row mb-3" style="border-bottom: 1px solid white; padding-bottom: 8px;">
                        <div class="col-12 d-flex justify-content-between align-items-center" 
                            data-bs-toggle="collapse" data-bs-target="#${collapseId}" 
                            aria-expanded="false" aria-controls="${collapseId}">
                            <span>${item.part_name}</span>
                            <span class="badge rounded-3 fs-6" style="border: 1px solid white;">
                                ${formattedDate(item.maintenance_date)}
                            </span>
                        </div>
                    </div>

                    <!-- Collapse -->
                    <div class="collapse mb-3" data-bs-parent="#pecasModalBody" id="${collapseId}">
                        <div class="card card-body" style="background-color: #343a40; color: white;">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col-12 text-start mb-3">
                                            <div class="row">
                                                <div class="col-6 text-start">
                                                    <label for="autonomy" class="form-label fw-light">Autonomia</label>
                                                    <input type="text" class="form-control bg-transparent" id="autonomy" required value="${item.final_km ? item.final_km - item.initial_km : item.vehicle_actual_km - item.initial_km} KM" name="quantity" min="1" max="999999" disabled>
                                                </div>
                                                <div class="col-6 text-start">
                                                    <label for="changed_with" class="form-label fw-light">Trocado com</label>
                                                    <input type="text" class="form-control bg-transparent" id="changed_with" required value="${item.initial_km} KM" name="changed_with" min="1" max="999999" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 text-start">
                                            <label for="quantity" class="form-label fw-light">Quantidade</label>
                                            <input type="text" class="form-control bg-transparent" id="quantity" required value="${item.quantity} ${item.type}" name="quantity" min="1" max="999999" disabled>
                                        </div>
                                        <div class="col-6 text-start">
                                            <label for="cost" class="form-label fw-light">Valor<span class="text-danger"><strong>*</strong></span></label>
                                            <input type="number" class="form-control bg-transparent" id="cost" value="${item.cost}" required name="cost" max="999999">
                                        </div>
                                        <div class="col-12 text-start mt-3">
                                            <div>
                                                <label for="observation" class="form-label fw-light">Observação<span class="text-danger"><strong>*</strong></span></label>
                                                <textarea class="form-control bg-transparent text-white" name="observation" rows="3">${item.observation === null ? '' : item.observation}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-3">
                                            <div class="d-flex justify-content-between">
                                                <button class="btn btn-danger border-white border-1 btn-delete" 
                                                data-maintenance-id="${item.maintenance_id}" 
                                                data-part-id="${item.part_id}">Deletar</button>
                                                <button class="btn btn-success border-white border-1 btn-update" 
                                                data-maintenance-id="${item.maintenance_id}" 
                                                data-part-id="${item.part_id}">Atualizar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                    });

                    // Paginação
                    html += `
                <div class="pagination d-flex justify-content-between align-items-center mt-3">
                    <button 
                        class="btn btn-sm btn-secondary" 
                        ${data.current_page > 1 ? '' : 'disabled'} 
                        onclick="fetchPartsChanged('${vehicleId}', ${data.current_page - 1})">
                        Anterior
                    </button>
                    <span>Página ${data.current_page} de ${data.last_page}</span>
                    <button 
                        class="btn btn-sm btn-secondary" 
                        ${data.current_page < data.last_page ? '' : 'disabled'} 
                        onclick="fetchPartsChanged('${vehicleId}', ${data.current_page + 1})">
                        Próxima
                    </button>
                </div>
            `;

                    resultsContainer.innerHTML = html;

                    // DELETE
                    document.querySelectorAll('.btn-delete').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const maintenanceId = this.getAttribute('data-maintenance-id');
                            const partId = this.getAttribute('data-part-id');

                            if (!confirm('Tem certeza que deseja remover esta peça?')) return;

                            fetch(`/manutencoes/${maintenanceId}/peca/${partId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').content,
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    alert(data.message);
                                    // Recarrega os dados do veículo após a remoção
                                    const vehicleId = document.getElementById('vehicle_id').value;
                                    fetchPartsChanged(vehicleId);
                                })
                                .catch(err => {
                                    console.error('Erro ao deletar peça:', err);
                                    alert('Erro ao deletar a peça.');
                                });
                        });
                    });

                    // PUT
                    document.querySelectorAll('.btn-update').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const maintenanceId = this.getAttribute('data-maintenance-id');
                            const partId = this.getAttribute('data-part-id');

                            const container = this.closest('.card-body');

                            const type = container.querySelector('[name="type"]').value;
                            const quantity = container.querySelector('[name="quantity"]').value;
                            const cost = container.querySelector('[name="cost"]').value;
                            const observation = container.querySelector('[name="observation"]').value;

                            fetch(`/manutencoes/${maintenanceId}/peca/${partId}`, {
                                    method: 'PUT',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').content,
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        type,
                                        quantity,
                                        cost,
                                        observation
                                    })
                                })
                                .then(res => res.json())
                                .then(data => {
                                    alert(data.message);
                                })
                                .catch(err => {
                                    console.error('Erro ao atualizar peça:', err);
                                    alert('Erro ao atualizar a peça.');
                                });
                        });
                    });

                })
                .catch(error => {
                    console.error('Erro ao buscar dados:', error);
                    document.getElementById('pecasModalBody').innerHTML = `
                <div class="text-center p-4">
                    <p class="text-danger">Erro ao carregar dados: ${error.message}</p>
                </div>
            `;
                });
        }

        function populateChoices(data) {
            if (!choices) return;

            // Limpa os existentes antes de adicionar os novos
            choices.clearChoices();

            // Formato esperado pelo Choices.js
            const formattedChoices = data.map(item => ({
                value: item.id, // adapta conforme estrutura da API
                label: item.name,
                selected: false,
                disabled: false
            }));

            choices.setChoices(formattedChoices, 'value', 'label', true);
        }


        function fetchPartsList() {
            const localStorageParts = localStorage.getItem('listAllParts');
            if (!localStorageParts) {
                const apiUrl = `/manutencoes/pecas`;

                fetch(apiUrl)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erro ao carregar os dados');
                        }
                        return response.json();
                    })
                    .then(data => {
                        localStorage.setItem('listAllParts', JSON.stringify(data))
                        populateChoices(data)
                    })
                    .catch(err => {
                        console.log(err)
                    })
            } else {
                const data = JSON.parse(localStorageParts);
                populateChoices(data);
            }
        }
    </script>
@endsection
