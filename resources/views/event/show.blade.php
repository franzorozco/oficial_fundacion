@extends('adminlte::page')
@section('title', $event->name ?? __('Show') . ' ' . __('Event'))
@section('content_header')
    <h1>{{ __('Show') }} Event</h1>
@endsection
@section('content')
    {{-- Información del Evento --}}
    <div class="card shadow-sm rounded">
        <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white">
            <h3 class="mb-0">{{ __('Show') }} {{ $event->name }}</h3>
            <a class="btn btn-outline-light btn-sm" href="{{ route('events.index') }}">
                <i class="fas fa-arrow-left"></i> {{ __('Back') }}
            </a>
        </div>
        <div class="card-body bg-light">
            <dl class="row">
                <dt class="col-sm-3">Campaña:</dt>
                <dd class="col-sm-9">{{ $event->campaign->name }}</dd>

                <dt class="col-sm-3">Creador:</dt>
                <dd class="col-sm-9">{{ $event->user->name }}</dd>

                <dt class="col-sm-3">Nombre:</dt>
                <dd class="col-sm-9">{{ $event->name }}</dd>

                <dt class="col-sm-3">Descripción:</dt>
                <dd class="col-sm-9">{{ $event->description }}</dd>

                <dt class="col-sm-3">Fecha del Evento:</dt>
                <dd class="col-sm-9">{{ $event->event_date }}</dd>
            </dl>
        </div>
    </div>

    {{-- Ubicaciones --}}
    <div class="mt-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4 class="mb-0">Ubicaciones del Evento</h4>
            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalNuevaUbicacion">
                + Nueva Ubicación
            </button>
            <!-- Correcto para Bootstrap 5 -->
            <button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#modalUbicacionesAnteriores">
                <i class="fas fa-history"></i> Ubicaciones Anteriores
            </button>
        </div>

        <div class="row">
            @foreach($event->eventLocations as $location)
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div id="map-{{ $location->id }}" style="width: 100%; height: 200px;" class="mb-3 rounded bg-secondary"></div>
                            <h5 class="card-title">{{ $location->location_name }}</h5>
                            <p class="card-text mb-1">
                                <strong>Dirección:</strong> {{ $location->address ?? 'No especificada' }}<br>
                                <strong>Latitud:</strong> {{ $location->latitud ?? 'N/A' }}<br>
                                <strong>Longitud:</strong> {{ $location->longitud ?? 'N/A' }}<br>
                                <strong>Registrado:</strong> {{ optional($location->created_at)->format('d/m/Y H:i') }}<br>
                                <strong>Hora de Inicio:</strong> {{ $location->start_hour ? \Carbon\Carbon::parse($location->start_hour)->format('H:i') : 'No definida' }}<br>
                                <strong>Hora de Fin:</strong> {{ $location->end_hour ? \Carbon\Carbon::parse($location->end_hour)->format('H:i') : 'No definida' }}
                            </p>
                            <div class="d-flex gap-2 mt-2">
                                <a href="{{ route('event-locations.show', $location->id) }}" class="btn btn-outline-info btn-sm">Ver</a>
                                <a href="{{ route('event-locations.edit', $location->id) }}" class="btn btn-outline-warning btn-sm">Editar</a>
                                <form action="{{ route('event-locations.destroy', $location->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta ubicación?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" type="submit">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Participantes --}}
    <div class="mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h4 class="mb-0">Participantes del Evento</h4>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalAgregarParticipante">
                    + Agregar Participante
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#modalParticipantesEliminados">
                    <i class="fas fa-user-slash"></i> Eliminados
                </button>
            </div>
        </div>

        @forelse($event->eventParticipants as $participant)
            @php
                $user = $participant->user;
                $profilePhoto = $user->profile_photo
                    ? asset('storage/' . $user->profile_photo)
                    : asset('storage/users/user_default.jpg');
            @endphp

            <div class="card mb-3 shadow-sm">
                <div class="card-body d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{ $profilePhoto }}" alt="Foto de {{ $user->name }}" class="rounded-circle shadow" style="width: 80px; height: 80px; object-fit: cover;">
                        <div>
                            <h5 class="mb-1">{{ $user->name ?? 'Sin nombre' }}</h5>
                            <div class="text-muted small">
                                <div><strong>Email:</strong> {{ $user->email ?? 'No disponible' }}</div>
                                <div><strong>Registro:</strong> {{ $participant->registration_date }}</div>
                                <div><strong>Estado:</strong> {{ ucfirst($participant->status) }}</div>
                                <div><strong>Observaciones:</strong> {{ $participant->observations ?? 'Ninguna' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-outline-info btn-sm">Ver</a>
                        <form action="{{ route('event-participants.destroy', $participant->id) }}" method="POST" onsubmit="return confirm('¿Eliminar participante del evento?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm" type="submit">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">No hay participantes registrados en este evento.</p>
        @endforelse
    </div>


    <!-- Modal Nueva Ubicación -->
    <div class="modal fade" id="modalNuevaUbicacion" tabindex="-1" aria-labelledby="modalNuevaUbicacionLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <form action="{{ route('event-locations.store') }}" method="POST">
            @csrf
            <input type="hidden" name="event_id" value="{{ $event->id }}">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="modalNuevaUbicacionLabel">Nueva Ubicación del Evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                    <label for="location_name" class="form-label">Nombre del lugar</label>
                    <input type="text" class="form-control" name="location_name" required>
                    </div>
                    <div class="col-md-6">
                    <label for="address" class="form-label">Dirección</label>
                    <input type="text"es class="form-control" name="address" id="direccion" readonly>
                    </div>
                    <div class="col-md-4">
                    <label for="latitud" class="form-label">Latitud</label>
                    <input type="text" class="form-control" name="latitud" id="latitud" readonly>
                    </div>
                    <div class="col-md-4">
                    <label for="longitud" class="form-label">Longitud</label>
                    <input type="text" class="form-control" name="longitud" id="longitud" readonly>
                    </div>
                    <div class="col-md-2">
                    <label for="start_hour" class="form-label">Hora Inicio</label>
                    <input type="time" class="form-control" name="start_hour">
                    </div>
                    <div class="col-md-2">
                    <label for="end_hour" class="form-label">Hora Fin</label>
                    <input type="time" class="form-control" name="end_hour">
                    </div>
                    <div class="col-12">
                    <label class="form-label">Ubicación en el mapa</label>
                    <div id="nuevoMapa" style="width: 100%; height: 400px;" class="rounded border"></div>
                    </div>
                </div>
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Guardar Ubicación</button>
                </div>
            </div>
            </form>
        </div>
    </div>

    <!-- Modal ubicaciones eliminadas-->
    <div class="modal fade" id="modalUbicacionesAnteriores" tabindex="-1" aria-labelledby="modalUbicacionesAnterioresLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title" id="modalUbicacionesAnterioresLabel">Ubicaciones Eliminadas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    @forelse($event->eventLocationsTrashed as $location)
                        <div class="card mb-3 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ $location->location_name }}</h5>
                            <p class="card-text">
                            <strong>Dirección:</strong> {{ $location->address ?? 'No especificada' }}<br>
                            <strong>Latitud:</strong> {{ $location->latitud ?? 'N/A' }}<br>
                            <strong>Longitud:</strong> {{ $location->longitud ?? 'N/A' }}
                            </p>
                            <div class="d-flex gap-2">
                            <form action="{{ route('event-locations.restore', $location->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-success btn-sm" type="submit">Restaurar</button>
                            </form>
                            <form action="{{ route('event-locations.force-delete', $location->id) }}" method="POST" onsubmit="return confirm('¿Eliminar permanentemente esta ubicación?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">Eliminar Definitivamente</button>
                            </form>
                            </div>
                        </div>
                        </div>
                    @empty
                        <p class="text-muted">No hay ubicaciones eliminadas.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Agregar Participante -->
    <div class="modal fade" id="modalAgregarParticipante" tabindex="-1" aria-labelledby="modalAgregarParticipanteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('event-participants.store') }}" method="POST">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAgregarParticipanteLabel">Agregar Participante al Evento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="event_locations_id" class="form-label">Seleccionar Ubicación</label>
                            <select class="form-select" name="event_locations_id" required>
                                <option value="" disabled selected>Seleccione una ubicación</option>
                                @foreach($event->eventLocations as $location)
                                    <option value="{{ $location->id }}">{{ $location->location_name }} - {{ $location->address }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Seleccionar Participante</label>
                            <select class="form-select" name="user_id" required>
                                <option value="" disabled selected>Seleccione un participante</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="observations" class="form-label">Observaciones</label>
                            <textarea class="form-control" name="observations" rows="2" placeholder="Opcional..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Agregar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Participantes Eliminados -->
    <div class="modal fade" id="modalParticipantesEliminados" tabindex="-1" aria-labelledby="modalParticipantesEliminadosLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title" id="modalParticipantesEliminadosLabel">Participantes Eliminados</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    @forelse($event->eventParticipants()->onlyTrashed()->get() as $deletedParticipant)
                        @php
                            $user = $deletedParticipant->user;
                            $profilePhoto = $user->profile_photo
                                ? asset('storage/' . $user->profile_photo)
                                : asset('storage/users/user_default.jpg');
                        @endphp
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body d-flex align-items-center justify-content-between flex-column flex-md-row gap-3">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $profilePhoto }}" alt="Foto de {{ $user->name }}" class="rounded-circle shadow-sm me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                    <div>
                                        <h5 class="card-title mb-1">{{ $user->name }}</h5>
                                        <p class="mb-0">
                                            <strong>Email:</strong> {{ $user->email }}<br>
                                            <strong>Registro:</strong> {{ $deletedParticipant->registration_date }}<br>
                                            <strong>Estado:</strong> {{ ucfirst($deletedParticipant->status) }}<br>
                                            <strong>Observaciones:</strong> {{ $deletedParticipant->observations ?? 'Ninguna' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <form action="{{ route('event-participants.restore', $deletedParticipant->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-outline-success btn-sm" type="submit">Restaurar</button>
                                    </form>
                                    <form action="{{ route('event-participants.forceDelete', $deletedParticipant->id) }}" method="POST" onsubmit="return confirm('¿Eliminar permanentemente este participante?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" type="submit">Eliminar Permanente</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No hay participantes eliminados.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
<script async
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2GCanK5Gxm26zDyPrKc7MNy7WhAJZK7M&callback=initMaps">
</script>

<script>
    let nuevoMapa, marcadorNuevo;

    function initMaps() {
        // Inicializa todos los mapas existentes
        initLocationMaps();

        // Inicializa el mapa del modal de nueva ubicación
        initNuevoMapa();
    }

    function initLocationMaps() {
        const locations = @json($event->eventLocations);

        locations.forEach(location => {
            const mapDiv = document.getElementById('map-' + location.id);
            if (!mapDiv) return;

            const map = new google.maps.Map(mapDiv, {
                center: { lat: parseFloat(location.latitud), lng: parseFloat(location.longitud) },
                zoom: 15,
            });

            new google.maps.Marker({
                position: { lat: parseFloat(location.latitud), lng: parseFloat(location.longitud) },
                map: map,
                title: location.location_name,
            });
        });
    }

    function initNuevoMapa() {
        nuevoMapa = new google.maps.Map(document.getElementById("nuevoMapa"), {
            center: { lat: -12.0464, lng: -77.0428 },
            zoom: 13,
        });

        nuevoMapa.addListener("click", function(event) {
            const lat = event.latLng.lat();
            const lng = event.latLng.lng();

            document.getElementById("latitud").value = lat;
            document.getElementById("longitud").value = lng;

            if (marcadorNuevo) marcadorNuevo.setMap(null);

            marcadorNuevo = new google.maps.Marker({
                position: { lat: lat, lng: lng },
                map: nuevoMapa,
            });

            getDireccion(lat, lng);
        });
    }

    function getDireccion(lat, lng) {
        const geocoder = new google.maps.Geocoder();
        const latlng = { lat: parseFloat(lat), lng: parseFloat(lng) };

        geocoder.geocode({ location: latlng }, (results, status) => {
            if (status === "OK" && results[0]) {
                document.getElementById("direccion").value = results[0].formatted_address;
            } else {
                document.getElementById("direccion").value = "Dirección no encontrada";
            }
        });
    }
</script>
@endsection
