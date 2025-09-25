@extends('adminlte::page')

@section('title', $campaign->name ?? __('Show') . " " . __('Campaign'))

@section('content_header')
    <h1 class="mb-4 text-dark">{{ __('Show') }} {{ __('Campaign') }}</h1>
@endsection

@section('content')
    <div class="card shadow-sm rounded">
        <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom">
            <h3 class="card-title mb-0 text-dark">{{ $campaign->name }}</h3>
            <a href="{{ route('campaigns.report', $campaign->id) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Descargar Reporte PDF
            </a>
            @if($campaignFinance)
                <button class="btn btn-primary" data-toggle="modal" data-target="#transactionModal">
                    <i class="fas fa-plus"></i> Registrar Transacción
                </button>
            @endif

        </div>

        <div class="card-body bg-light">
            <dl class="row">
                <dt class="col-sm-3 text-dark">Creador:</dt>
                <dd class="col-sm-9 text-dark">{{ $campaign->user->name ?? 'N/A' }}</dd>

                <dt class="col-sm-3 text-dark">Nombre:</dt>
                <dd class="col-sm-9 text-dark">{{ $campaign->name }}</dd>

                <dt class="col-sm-3 text-dark">Descripción:</dt>
                <dd class="col-sm-9 text-dark">{{ $campaign->description }}</dd>

                <dt class="col-sm-3 text-dark">Fecha de Inicio:</dt>
                <dd class="col-sm-9 text-dark">{{ $campaign->start_date }}</dd>

                <dt class="col-sm-3 text-dark">Fecha de Fin:</dt>
                <dd class="col-sm-9 text-dark">{{ $campaign->end_date }}</dd>
            </dl>
        </div>
    </div>

    {{-- Eventos --}}
    <button class="btn btn-primary" data-toggle="modal" data-target="#createEventModal">
                    <i class="fas fa-plus"></i> Agregar Evento
                </button>
    @if ($campaign->events->count())
        <div class="d-flex justify-content-end my-3">
            {{-- Aquí puedes agregar botones u otros elementos si quieres --}}
        </div>

        <div class="card shadow-sm rounded mt-4">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h4 class="mb-0 text-dark">Eventos de la Campaña</h4>
                <button class="btn btn-primary" data-toggle="modal" data-target="#createEventModal">
                    <i class="fas fa-plus"></i> Agregar Evento
                </button>
                <!-- Botón para mostrar eventos anteriores -->
                <button class="btn btn-secondary" data-toggle="modal" data-target="#deletedEventsModal">
                    <i class="fas fa-history"></i> Eventos Anteriores
                </button>
            </div>

            <div class="card-body">
                @foreach ($campaign->events as $event)
                    <div class="border rounded p-3 mb-3 shadow-sm bg-white">
                        <div class="d-flex justify-content-between flex-column flex-md-row gap-2">
                            <div>
                                <h5 class="fw-bold text-dark">{{ $event->name }}</h5>
                                <p class="text-dark"><strong>Descripción:</strong> {{ $event->description ?? 'Sin descripción' }}</p>
                                <p class="text-dark"><strong>Fecha del evento:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('Y/m/d') }}</p>
                                <p class="text-dark"><strong>Creador:</strong> {{ $event->user->name ?? 'N/A' }}</p>
                                <p class="text-dark"><strong>Ubicaciones:</strong> {{ $event->eventLocations->count() }}</p>
                                <p class="text-dark"><strong>Participantes:</strong> {{ $event->eventParticipants->count() }}</p>
                            </div>

                            <div class="d-flex flex-column align-items-end gap-1">
                                <a href="{{ route('events.show', $event->id) }}" class="btn btn-outline-primary btn-sm w-100">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                                <button class="btn btn-outline-success btn-sm w-100" data-toggle="modal" data-target="#editEventModal{{ $event->id }}">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <form action="{{ route('events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este evento?')" class="w-100">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm w-100">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Modal de edición para este evento --}}
                    <div class="modal fade" id="editEventModal{{ $event->id }}" tabindex="-1" role="dialog" aria-labelledby="editEventLabel{{ $event->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form action="{{ route('events.update', $event->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="creator_id" value="{{ auth()->id() }}">
                                <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">
                                <div class="modal-content bg-white text-dark">
                                    <div class="modal-header border-bottom">
                                        <h5 class="modal-title" id="editEventLabel{{ $event->id }}">Editar Evento</h5>
                                        <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Cerrar">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label class="text-dark">Nombre</label>
                                            <input type="text" name="name" value="{{ $event->name }}" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Descripción</label>
                                            <textarea name="description" class="form-control">{{ $event->description }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Fecha del Evento</label>
                                            <input type="date" name="event_date" value="{{ $event->event_date }}" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="modal-footer border-top">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="card shadow-sm rounded mt-4 bg-white">
            <div class="card-body">
                <p class="text-muted mb-0">Esta campaña no tiene eventos registrados.</p>
            </div>
        </div>
    @endif

    <!-- Modal para crear evento -->
    <div class="modal fade" id="createEventModal" tabindex="-1" role="dialog" aria-labelledby="createEventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('events.store') }}" method="POST">
                @csrf
                <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">
                <input type="hidden" name="creator_id" value="{{ auth()->id() }}">
                <div class="modal-content bg-white text-dark">
                    <div class="modal-header border-bottom">
                        <h5 class="modal-title" id="createEventModalLabel">Agregar Evento a la Campaña</h5>
                        <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name" class="text-dark">Nombre del Evento</label>
                            <input type="text" name="name" id="name" class="form-control" required maxlength="150">
                        </div>

                        <div class="form-group">
                            <label for="description" class="text-dark">Descripción</label>
                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="event_date" class="text-dark">Fecha del Evento</label>
                            <input type="date" name="event_date" id="event_date" class="form-control" required>
                        </div>
                    </div>

                    <div class="modal-footer border-top">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Evento</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de eventos eliminados -->
    <div class="modal fade" id="deletedEventsModal" tabindex="-1" role="dialog" aria-labelledby="deletedEventsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content bg-white text-dark">
                <div class="modal-header">
                    <h5 class="modal-title">Eventos Eliminados</h5>
                    <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @php
                        $deletedEvents = $campaign->events()->onlyTrashed()->get();
                    @endphp

                    @if($deletedEvents->count())
                        @foreach ($deletedEvents as $deleted)
                            <div class="border rounded p-3 mb-3 shadow-sm bg-light">
                                <h5>{{ $deleted->name }}</h5>
                                <p><strong>Descripción:</strong> {{ $deleted->description ?? 'Sin descripción' }}</p>
                                <p><strong>Fecha del evento:</strong> {{ \Carbon\Carbon::parse($deleted->event_date)->format('Y/m/d') }}</p>

                                <div class="d-flex gap-2">
                                    <!-- Restaurar -->
                                    <form action="{{ route('events.restore', $deleted->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-undo"></i> Restaurar
                                        </button>
                                    </form>

                                    <!-- Eliminar Definitivamente -->
                                    <form action="{{ route('events.forceDelete', $deleted->id) }}" method="POST" onsubmit="return confirm('¿Eliminar permanentemente este evento?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-times"></i> Eliminar Definitivamente
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No hay eventos eliminados.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para transacciones -->
    <div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route('transactions.store') }}">
                @csrf
                
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="transactionModalLabel">Registrar Transacción</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body row g-3">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <input type="hidden" name="related_campaign_id" value="{{ $campaign->id }}">

                        <!-- Evento -->
                        <div class="col-md-6">
                            <label for="related_event_id" class="form-label">Evento (opcional)</label>
                            <select name="related_event_id" id="event-select" class="form-select">
                                <option value="">-- Ninguno --</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}">{{ $event->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Ubicación -->
                        <div class="col-md-6">
                            <label for="related_event_location_id" class="form-label">Ubicación (opcional)</label>
                            <select name="related_event_location_id" id="location-select" class="form-select">
                                <option value="">-- Ninguna --</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}" data-event-id="{{ $loc->event_id }}">{{ $loc->location_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="account_id" class="form-label">Cuenta financiera</label>
                            <select id="account_select" class="form-select" disabled>
                                @foreach($accounts as $acc)
                                    <option value="{{ $acc->id }}" {{ $acc->id == $selectedAccountId ? 'selected' : '' }}>
                                        {{ $acc->name }} ({{ $acc->type }})
                                    </option>
                                @endforeach
                            </select>

                            <input type="hidden" id="hidden_account_id" name="account_id" value="">

                        </div>


                        <div class="col-md-6">
                            <label for="type" class="form-label">Tipo</label>
                            <select name="type" class="form-select" required>
                                <option value="ingreso">Ingreso</option>
                                <option value="gasto">Gasto</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="amount" class="form-label">Monto</label>
                            <input type="number" step="0.01" name="amount" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="transaction_date" class="form-label">Fecha</label>
                            <input type="date" name="transaction_date" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="transaction_time" class="form-label">Hora</label>
                            <input type="time" name="transaction_time" class="form-control" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Guardar Transacción</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                    
                </div>
            </form>
        </div>
    </div>


@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const eventSelect = document.getElementById('event-select');
        const locationSelect = document.getElementById('location-select');
        const allLocations = Array.from(locationSelect.options);

        eventSelect.addEventListener('change', function () {
            const selectedEventId = this.value;
            locationSelect.innerHTML = '';

            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = '-- Ninguna --';
            locationSelect.appendChild(defaultOption);

            allLocations.forEach(option => {
                if (option.dataset.eventId === selectedEventId || option.value === '') {
                    locationSelect.appendChild(option.cloneNode(true));
                }
            });
        });
    });
</script>
<script>
    window.addEventListener('DOMContentLoaded', () => {
        const select = document.getElementById('account_select');
        const hiddenInput = document.getElementById('hidden_account_id');
        hiddenInput.value = select.options[select.selectedIndex].value;
    });
</script>
<script>
    @if ($errors->any())
        var modal = new bootstrap.Modal(document.getElementById('transactionModal'));
        modal.show();
    @endif
</script>
