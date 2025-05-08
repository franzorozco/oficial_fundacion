@extends('adminlte::page')

@section('title', $campaign->name ?? __('Show') . " " . __('Campaign'))

@section('content_header')
    <h1 class="mb-4">{{ __('Show') }} Campaign</h1>
@endsection

@section('content')
    <div class="card shadow-sm rounded">
        <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white">
            <h3 class="card-title mb-0">{{ __('Show') }} Campaign</h3>
            <a href="{{ route('campaigns.index') }}" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left"></i> {{ __('Back') }}
            </a>
        </div>

        <div class="card-body bg-light">
            <div class="form-group mb-2">
                <strong>Creator Id:</strong>
                {{ $campaign->creator_id }}
            </div>
            <div class="form-group mb-2">
                <strong>Name:</strong>
                {{ $campaign->name }}
            </div>
            <div class="form-group mb-2">
                <strong>Description:</strong>
                {{ $campaign->description }}
            </div>
            <div class="form-group mb-2">
                <strong>Start Date:</strong>
                {{ $campaign->start_date }}
            </div>
            <div class="form-group mb-2">
                <strong>End Date:</strong>
                {{ $campaign->end_date }}
            </div>
            <div class="form-group mb-2">
                <strong>Start Hour:</strong>
                {{ $campaign->start_hour }}
            </div>
            <div class="form-group mb-2">
                <strong>End Hour:</strong>
                {{ $campaign->end_hour }}
            </div>
        </div>
    </div>

    {{-- Eventos de la Campaña --}}
    @if ($campaign->events->count())
        <div class="card shadow-sm rounded mt-4">
            <div class="card-header bg-dark text-white">
                <h3 class="mb-0">Eventos de la Campaña</h3>
            </div>

            <div class="card-body">
                <div class="list-group">
                    @foreach ($campaign->events as $event)
                        <div class="list-group-item list-group-item-action flex-column align-items-start mb-3 rounded shadow-sm">
                            <div class="d-flex w-100 justify-content-between">
                                <div class="me-3">
                                    <h5 class="mb-1">{{ $event->name }}</h5>
                                    <p class="mb-1"><strong>Descripción:</strong> {{ $event->description ?? 'Sin descripción' }}</p>
                                    <p class="mb-1"><strong>Fecha del evento:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('Y/m/d') }}</p>
                                    <p class="mb-1"><strong>Creador:</strong> {{ $event->user->name ?? 'N/A' }}</p>

                                    {{-- Suma de ubicaciones y participantes --}}
                                    <p class="mb-1"><strong>Ubicaciones:</strong> {{ $event->eventLocations->count() }}</p>
                                    <p class="mb-1"><strong>Participantes:</strong> {{ $event->eventParticipants->count() }}</p>

                                </div>

                                <!-- Botones alineados a la derecha -->
                                <div class="text-end">
                                    <a href="{{ route('events.show', $event->id) }}" class="btn btn-outline-primary btn-sm mb-1">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                    <a href="{{ route('events.edit', $event->id) }}" class="btn btn-outline-warning btn-sm mb-1">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>

                                    <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este evento?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="card shadow-sm rounded mt-4">
            <div class="card-body">
                <p class="text-muted">Esta campaña no tiene eventos registrados.</p>
            </div>
        </div>
    @endif
@endsection

@section('css')
    <style>
        .list-group-item h5 {
            font-size: 1.1rem;
        }

        .list-group-item p {
            font-size: 0.9rem;
        }

        .btn-sm {
            font-size: 0.75rem;
        }

        .list-group-item {
            border: 1px solid #ddd;
        }

        /* Asegura que los datos de cada evento estén bien alineados */
        .list-group-item div {
            display: block;
            margin-bottom: 1rem;
        }

        /* Mantiene los botones a la derecha */
        .text-end {
            text-align: right;
        }
    </style>
@endsection

@section('js')
    {{-- Incluye Bootstrap JS si aún no está cargado --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
