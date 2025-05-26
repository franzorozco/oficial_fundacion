@extends('adminlte::page')

@section('title', $campaign->name ?? __('Show') . " " . __('Campaign'))

@section('content_header')
    <h1 class="mb-4">{{ __('Show') }} {{ __('Campaign') }}</h1>
@endsection

@section('content')
    <div class="card shadow-sm rounded">
        <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white">
            <h3 class="card-title mb-0">{{ $campaign->name }}</h3>
        </div>

        <div class="card-body bg-light">
            <dl class="row">
                <dt class="col-sm-3">Creador:</dt>
                <dd class="col-sm-9">{{ $campaign->user->name ?? 'N/A' }}</dd>

                <dt class="col-sm-3">Nombre:</dt>
                <dd class="col-sm-9">{{ $campaign->name }}</dd>

                <dt class="col-sm-3">Descripción:</dt>
                <dd class="col-sm-9">{{ $campaign->description }}</dd>

                <dt class="col-sm-3">Fecha de Inicio:</dt>
                <dd class="col-sm-9">{{ $campaign->start_date }}</dd>

                <dt class="col-sm-3">Fecha de Fin:</dt>
                <dd class="col-sm-9">{{ $campaign->end_date }}</dd>
            </dl>
        </div>
    </div>

    {{-- Eventos --}}
    @if ($campaign->events->count())
        <div class="d-flex justify-content-end my-3">
            <button class="btn btn-primary" data-toggle="modal" data-target="#createEventModal">
                <i class="fas fa-plus"></i> Agregar Evento
            </button>
        </div>

        <div class="card shadow-sm rounded mt-4">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Eventos de la Campaña</h4>
            </div>

            <div class="card-body">
                @foreach ($campaign->events as $event)
                    <div class="border rounded p-3 mb-3 shadow-sm">
                        <div class="d-flex justify-content-between flex-column flex-md-row gap-2">
                            <div>
                                <h5 class="fw-bold">{{ $event->name }}</h5>
                                <p><strong>Descripción:</strong> {{ $event->description ?? 'Sin descripción' }}</p>
                                <p><strong>Fecha del evento:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('Y/m/d') }}</p>
                                <p><strong>Creador:</strong> {{ $event->user->name ?? 'N/A' }}</p>
                                <p><strong>Ubicaciones:</strong> {{ $event->eventLocations->count() }}</p>
                                <p><strong>Participantes:</strong> {{ $event->eventParticipants->count() }}</p>
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
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editEventLabel{{ $event->id }}">Editar Evento</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
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
                                            <label>Fecha del Evento</label>
                                            <input type="date" name="event_date" value="{{ $event->event_date }}" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
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
        <div class="card shadow-sm rounded mt-4">
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
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createEventModalLabel">Agregar Evento a la Campaña</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nombre del Evento</label>
                            <input type="text" name="name" id="name" class="form-control" required maxlength="150">
                        </div>

                        <div class="form-group">
                            <label for="description">Descripción</label>
                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="event_date">Fecha del Evento</label>
                            <input type="date" name="event_date" id="event_date" class="form-control" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Evento</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
