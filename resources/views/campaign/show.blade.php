@extends('adminlte::page')

@section('title', $campaign->name ?? 'Campaña')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="mb-0">{{ $campaign->name }}</h1>

    <div class="d-flex gap-2">
        <a href="{{ route('campaigns.report', $campaign->id) }}" class="btn btn-danger btn-sm" target="_blank">
            <i class="fas fa-file-pdf"></i> PDF
        </a>

        @if($campaignFinance)
        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#transactionModal">
            <i class="fas fa-plus"></i> Transacción
        </button>
        @endif
    </div>
</div>
@endsection

@section('content')

<!-- ===================== CAMPAÑA INFO ===================== -->
<div class="card shadow-sm border-0 mb-4">

    <div class="card-header bg-white border-bottom">
        <h5 class="mb-0">Información de la campaña</h5>
    </div>

    <div class="card-body">
        <div class="row g-3">

            <div class="col-md-6">
                <div class="p-3 bg-light rounded">
                    <small class="text-muted">Creador</small>
                    <div class="fw-semibold">{{ $campaign->user->name ?? 'N/A' }}</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 bg-light rounded">
                    <small class="text-muted">Nombre</small>
                    <div class="fw-semibold">{{ $campaign->name }}</div>
                </div>
            </div>

            <div class="col-12">
                <div class="p-3 bg-light rounded">
                    <small class="text-muted">Descripción</small>
                    <div>{{ $campaign->description ?? 'Sin descripción' }}</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 bg-light rounded">
                    <small class="text-muted">Fecha inicio</small>
                    <div>{{ $campaign->start_date }}</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 bg-light rounded">
                    <small class="text-muted">Fecha fin</small>
                    <div>{{ $campaign->end_date }}</div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ===================== EVENTOS HEADER ===================== -->
<div class="d-flex justify-content-between align-items-center mb-3">

    <h4 class="mb-0">Eventos</h4>

    <div class="d-flex gap-2">
        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#createEventModal">
            <i class="fas fa-plus"></i> Evento
        </button>

        <button class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#deletedEventsModal">
            <i class="fas fa-history"></i> Eliminados
        </button>
    </div>
</div>

<!-- ===================== EVENTOS LIST ===================== -->
@if ($campaign->events->count())

<div class="row">

    @foreach ($campaign->events as $event)

    <div class="col-md-6 mb-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h5 class="fw-bold mb-2">{{ $event->name }}</h5>
                <p class="mb-1 text-muted">
                    {{ $event->description ?? 'Sin descripción' }}
                </p>
                <hr>
                <div class="small text-muted">
                    <div><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('Y/m/d') }}</div>
                    <div><strong>Creador:</strong> {{ $event->user->name ?? 'N/A' }}</div>
                    <div><strong>Ubicaciones:</strong> {{ $event->eventLocations->count() }}</div>
                    <div><strong>Participantes:</strong> {{ $event->eventParticipants->count() }}</div>
                </div>

            </div>

            <!-- ================= MODAL EDITAR EVENTO ================= -->
            <div class="modal fade" id="editEventModal{{ $event->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">

                    <form action="{{ route('events.update', $event->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="creator_id" value="{{ auth()->id() }}">
                        <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">

                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">Editar evento</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <div class="modal-body">

                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input type="text" name="name" value="{{ $event->name }}" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Descripción</label>
                                    <textarea name="description" class="form-control">{{ $event->description }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>Fecha del evento</label>
                                    <input type="date" name="event_date"
                                        value="{{ \Carbon\Carbon::parse($event->event_date)->format('Y-m-d') }}"
                                        class="form-control" required>
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Guardar cambios</button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
            <div class="card-footer bg-white border-0 p-2">
                <div class="d-flex gap-2 w-100">

                    <a href="{{ route('events.show', $event->id) }}"
                    class="btn btn-outline-primary btn-sm flex-fill d-flex align-items-center justify-content-center">
                        Ver
                    </a>

                    <button type="button"
                            class="btn btn-outline-success btn-sm flex-fill d-flex align-items-center justify-content-center"
                            data-toggle="modal"
                            data-target="#editEventModal{{ $event->id }}">
                        Editar
                    </button>
                    <form action="{{ route('events.destroy', $event->id) }}"
                        method="POST"
                        class="flex-fill"
                        onsubmit="return confirm('¿Eliminar evento?')">

                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="btn btn-outline-danger btn-sm w-100 d-flex align-items-center justify-content-center">
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

</div>

@else

<div class="card border-0 shadow-sm">
    <div class="card-body text-center text-muted">
        No hay eventos registrados en esta campaña.
    </div>
</div>

@endif

<!-- ===================== MODAL CREAR EVENTO ===================== -->
<div class="modal fade" id="createEventModal">
    <div class="modal-dialog">
        <form action="{{ route('events.store') }}" method="POST">
            @csrf
            <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">
            <input type="hidden" name="creator_id" value="{{ auth()->id() }}">

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Nuevo evento</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Fecha</label>
                        <input type="date" name="event_date" class="form-control" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary">Guardar</button>
                </div>

            </div>
        </form>
    </div>
</div>

<!-- ===================== MODAL ELIMINADOS ===================== -->
<div class="modal fade" id="deletedEventsModal">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Eventos eliminados</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

                @php
                    $deletedEvents = $campaign->events()->onlyTrashed()->get();
                @endphp

                @forelse ($deletedEvents as $deleted)

                <div class="border rounded p-3 mb-2">

                    <strong>{{ $deleted->name }}</strong>

                    <div class="small text-muted">
                        {{ $deleted->description ?? 'Sin descripción' }}
                    </div>

                    <div class="mt-2 d-flex gap-2">

                        <form action="{{ route('events.restore', $deleted->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-success btn-sm">Restaurar</button>
                        </form>

                        <form action="{{ route('events.forceDelete', $deleted->id) }}" method="POST"
                            onsubmit="return confirm('Eliminar permanentemente?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm">Eliminar</button>
                        </form>

                    </div>

                </div>

                @empty
                    <p class="text-muted">No hay eventos eliminados.</p>
                @endforelse

            </div>

        </div>

    </div>
</div>

<!-- ===================== MODAL TRANSACCIONES ===================== -->
<div class="modal fade" id="transactionModal">
    <div class="modal-dialog modal-lg">

        <form method="POST" action="{{ route('transactions.store') }}">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Registrar transacción</h5>
                    <button class="btn-close" data-dismiss="modal"></button>
                </div>

                <div class="modal-body row">

                    @if ($errors->any())
                    <div class="col-12 alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <input type="hidden" name="related_campaign_id" value="{{ $campaign->id }}">

                    <div class="col-md-6 form-group">
                        <label>Tipo</label>
                        <select name="type" class="form-control" required>
                            <option value="ingreso">Ingreso</option>
                            <option value="gasto">Gasto</option>
                        </select>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Monto</label>
                        <input type="number" step="0.01" name="amount" class="form-control" required>
                    </div>

                    <div class="col-md-12 form-group">
                        <label>Descripción</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Fecha</label>
                        <input type="date" name="transaction_date" class="form-control" required>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Hora</label>
                        <input type="time" name="transaction_time" class="form-control" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">Guardar</button>
                    <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
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
