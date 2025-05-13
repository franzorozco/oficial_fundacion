@extends('adminlte::page')

@section('title', $campaign->name ?? __('Show') . " " . __('Campaign'))

@section('content_header')
    <h1 class="mb-4">{{ __('Show') }} {{ __('Campaign') }}</h1>
@endsection

@section('content')
    <div class="card shadow-sm rounded">
        <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white">
            <h3 class="card-title mb-0">{{ $campaign->name }}</h3>
            <a href="{{ route('campaigns.index') }}" class="btn btn-outline-light btn-sm">
                <i class="fas fa-arrow-left"></i> {{ __('Back') }}
            </a>
        </div>

        <div class="card-body bg-light">
            <dl class="row">
                <dt class="col-sm-3">Creator Id:</dt>
                <dd class="col-sm-9">{{ $campaign->creator_id }}</dd>

                <dt class="col-sm-3">Name:</dt>
                <dd class="col-sm-9">{{ $campaign->name }}</dd>

                <dt class="col-sm-3">Description:</dt>
                <dd class="col-sm-9">{{ $campaign->description }}</dd>

                <dt class="col-sm-3">Start Date:</dt>
                <dd class="col-sm-9">{{ $campaign->start_date }}</dd>

                <dt class="col-sm-3">End Date:</dt>
                <dd class="col-sm-9">{{ $campaign->end_date }}</dd>

                <dt class="col-sm-3">Start Hour:</dt>
                <dd class="col-sm-9">{{ $campaign->start_hour }}</dd>

                <dt class="col-sm-3">End Hour:</dt>
                <dd class="col-sm-9">{{ $campaign->end_hour }}</dd>
            </dl>
        </div>
    </div>

    {{-- Eventos --}}
    @if ($campaign->events->count())
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
                                <a href="{{ route('events.edit', $event->id) }}" class="btn btn-outline-success btn-sm w-100">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
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
@endsection
