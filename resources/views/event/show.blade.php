@extends('adminlte::page')

@section('title', $event->name ?? 'Evento')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="mb-0">{{ $event->name }}</h1>

    <a class="btn btn-outline-secondary btn-sm" href="{{ route('events.index') }}">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>
@endsection

@section('content')

<!-- ===================== INFO EVENTO ===================== -->
<div class="card shadow-sm border-0 mb-4">

    <div class="card-header bg-white border-bottom">
        <h5 class="mb-0">Información del evento</h5>
    </div>

    <div class="card-body">
        <div class="row g-3">

            <div class="col-md-6">
                <div class="p-3 bg-light rounded">
                    <small class="text-muted">Campaña</small>
                    <div class="fw-semibold">{{ $event->campaign->name }}</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 bg-light rounded">
                    <small class="text-muted">Creador</small>
                    <div>{{ $event->user->name }}</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 bg-light rounded">
                    <small class="text-muted">Nombre</small>
                    <div>{{ $event->name }}</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 bg-light rounded">
                    <small class="text-muted">Fecha</small>
                    <div>{{ $event->event_date }}</div>
                </div>
            </div>

            <div class="col-12">
                <div class="p-3 bg-light rounded">
                    <small class="text-muted">Descripción</small>
                    <div>{{ $event->description ?? 'Sin descripción' }}</div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ===================== UBICACIONES ===================== -->
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h4 class="mb-0">Ubicaciones</h4>

    <div class="d-flex gap-2">
        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalNuevaUbicacion">
            <i class="fas fa-map-marker-alt"></i> Nueva
        </button>

        <button class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#modalUbicacionesAnteriores">
            <i class="fas fa-history"></i> Eliminadas
        </button>
    </div>
</div>

<div class="row">

@forelse($event->eventLocations as $location)

    <div class="col-md-6 col-lg-4 mb-4">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-body">

                <!-- MAPA -->
                <div id="map-{{ $location->id }}"
                    class="rounded bg-secondary mb-3"
                    style="height:200px;">
                </div>

                <!-- TITULO -->
                <h5 class="fw-bold mb-2">
                    {{ $location->location_name }}
                </h5>

                <hr>

                <!-- INFO -->
                <div class="small text-muted">

                    <div class="mb-2">
                        <strong>Dirección:</strong><br>
                        {{ $location->address ?? 'No especificada' }}
                    </div>

                    <div class="mb-2">
                        <strong>Lat:</strong> {{ $location->latitud ?? 'N/A' }}<br>
                        <strong>Lng:</strong> {{ $location->longitud ?? 'N/A' }}
                    </div>

                    <div class="mb-2">
                        <strong>Registro:</strong><br>
                        {{ optional($location->created_at)->format('d/m/Y H:i') }}
                    </div>

                    <div>
                        <strong>Horario:</strong><br>
                        {{ $location->start_hour ? \Carbon\Carbon::parse($location->start_hour)->format('H:i') : '--' }}
                        -
                        {{ $location->end_hour ? \Carbon\Carbon::parse($location->end_hour)->format('H:i') : '--' }}
                    </div>

                </div>

            </div>

            <!-- ACCIONES -->
            <div class="card-footer bg-white border-0">

                <div class="btn-group w-100">

                    <a href="{{ route('event-locations.show', $location->id) }}"
                        class="btn btn-outline-info btn-sm">
                        Ver
                    </a>

                    <a href="{{ route('event-locations.edit', $location->id) }}"
                        class="btn btn-outline-warning btn-sm">
                        Editar
                    </a>

                    <form action="{{ route('event-locations.destroy', $location->id) }}"
                        method="POST"
                        onsubmit="return confirm('¿Eliminar ubicación?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm">
                            Eliminar
                        </button>
                    </form>

                </div>

            </div>

        </div>

    </div>

@empty

    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center text-muted py-5">
                <i class="fas fa-map-marker-alt fa-2x mb-2"></i><br>
                No hay ubicaciones registradas en este evento
            </div>
        </div>
    </div>

@endforelse

</div>


<!-- ===================== PARTICIPANTES ===================== -->
<div class="mt-5">

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h4 class="mb-0">Participantes</h4>

        <div class="d-flex gap-2">
            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalAgregarParticipante">
                + Participante
            </button>

            <button class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#modalParticipantesEliminados">
                <i class="fas fa-user-slash"></i> Eliminados
            </button>
        </div>
    </div>

    <div class="row">

        @forelse($event->eventParticipants as $participant)

        @php
            $user = $participant->user;
            $profilePhoto = $user->profile_photo
                ? asset('storage/' . $user->profile_photo)
                : asset('storage/users/user_default.jpg');
        @endphp

        <div class="col-md-6 col-lg-4 mb-4">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body text-center">

                    <!-- FOTO -->
                    <img src="{{ $profilePhoto }}"
                        class="rounded-circle shadow mb-3"
                        style="width:80px; height:80px; object-fit:cover;">

                    <!-- NOMBRE -->
                    <h5 class="fw-bold mb-1">{{ $user->name }}</h5>

                    <!-- EMAIL -->
                    <div class="text-muted small mb-2">
                        {{ $user->email }}
                    </div>

                    <hr>

                    <!-- INFO -->
                    <div class="small text-muted text-start">

                        <div class="mb-1">
                            <strong>Registro:</strong><br>
                            {{ $participant->registration_date }}
                        </div>

                        <div class="mb-1">
                            <strong>Estado:</strong>
                            <span class="badge bg-{{ $participant->status == 'activo' ? 'success' : 'secondary' }}">
                                {{ ucfirst($participant->status) }}
                            </span>
                        </div>

                        <div>
                            <strong>Observaciones:</strong><br>
                            {{ $participant->observations ?? 'Ninguna' }}
                        </div>

                    </div>

                </div>

                <!-- ACCIONES -->
                <div class="card-footer bg-white border-0">

                    <div class="btn-group w-100">

                        <a href="{{ route('users.show', $user->id) }}"
                            class="btn btn-outline-info btn-sm">
                            Ver
                        </a>

                        <form action="{{ route('event-participants.destroy', $participant->id) }}"
                            method="POST"
                            onsubmit="return confirm('¿Eliminar participante?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm">
                                Eliminar
                            </button>
                        </form>

                    </div>

                </div>

            </div>

        </div>

        @empty

        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center text-muted">
                    <i class="fas fa-user-times fa-2x mb-2"></i><br>
                    No hay participantes registrados en este evento.
                </div>
            </div>
        </div>

        @endforelse

    </div>

</div>

<!-- ===================== MODAL NUEVA UBICACION ===================== -->
<div class="modal fade" id="modalNuevaUbicacion" tabindex="-1" aria-hidden="true">
    
    <!-- IMPORTANTE: centrado + ancho grande -->
    <div class="modal-dialog modal-dialog-centered modal-xl" style="max-width: 95%;">
        
        <form action="{{ route('event-locations.store') }}" method="POST" class="w-100">
            @csrf
            <input type="hidden" name="event_id" value="{{ $event->id }}">

            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                <!-- HEADER -->
                <div class="modal-header bg-white border-bottom px-4 py-3">
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Nueva ubicación</h5>
                        <small class="text-muted">
                            Haz clic en el mapa para autocompletar los datos
                        </small>
                    </div>

                    <button type="button" class="btn-close" data-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body p-4">

                    <div class="row g-4">

                        <!-- ================= FORM ================= -->
                        <div class="col-lg-4">

                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">

                                    <h6 class="fw-bold mb-3">Datos</h6>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Nombre del lugar</label>
                                        <input type="text"
                                            name="location_name"
                                            class="form-control"
                                            placeholder="Ej: Plaza principal"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Dirección</label>
                                        <input type="text"
                                            name="address"
                                            id="direccion"
                                            class="form-control bg-light"
                                            readonly>
                                        <small class="text-muted">Se llena automáticamente</small>
                                    </div>

                                    <div class="row">
                                        <div class="col-6 mb-3">
                                            <label class="form-label fw-semibold">Latitud</label>
                                            <input type="text"
                                                name="latitud"
                                                id="latitud"
                                                class="form-control bg-light"
                                                readonly>
                                        </div>

                                        <div class="col-6 mb-3">
                                            <label class="form-label fw-semibold">Longitud</label>
                                            <input type="text"
                                                name="longitud"
                                                id="longitud"
                                                class="form-control bg-light"
                                                readonly>
                                        </div>
                                    </div>

                                    <hr>

                                    <h6 class="fw-bold mb-3">Horario (opcional)</h6>

                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label">Inicio</label>
                                            <input type="time"
                                                name="start_hour"
                                                class="form-control">
                                        </div>

                                        <div class="col-6">
                                            <label class="form-label">Fin</label>
                                            <input type="time"
                                                name="end_hour"
                                                class="form-control">
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <!-- ================= MAPA ================= -->
                        <div class="col-lg-8">

                            <div class="card border-0 shadow-sm h-100">

                                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold mb-0">Mapa</h6>
                                    <small class="text-muted">Selecciona una ubicación</small>
                                </div>

                                <div class="card-body p-2">

                                    <div id="nuevoMapa"
                                        class="rounded"
                                        style="width: 100%; height: 500px;">
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer bg-white border-top px-4 py-3 d-flex justify-content-between">

                    <button type="button"
                        class="btn btn-outline-secondary"
                        data-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit"
                        class="btn btn-primary px-4">
                        <i class="fas fa-save"></i> Guardar ubicación
                    </button>

                </div>

            </div>
        </form>
    </div>
</div>


<!-- ===================== MODAL UBICACIONES ELIMINADAS ===================== -->
<div class="modal fade" id="modalUbicacionesAnteriores" tabindex="-1" aria-labelledby="modalUbicacionesAnterioresLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content border-0 shadow">

            <!-- HEADER -->
            <div class="modal-header bg-white border-bottom">
                <div>
                    <h5 class="modal-title mb-0">Ubicaciones eliminadas</h5>
                    <small class="text-muted">Puedes restaurarlas o eliminarlas definitivamente</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <div class="row">

                    @forelse($event->eventLocationsTrashed as $location)

                        <div class="col-md-6 col-lg-4 mb-4">

                            <div class="card border-0 shadow-sm h-100">

                                <div class="card-body">

                                    <!-- TITULO -->
                                    <h5 class="fw-bold mb-2">
                                        {{ $location->location_name }}
                                    </h5>

                                    <hr>

                                    <!-- INFO -->
                                    <div class="small text-muted">

                                        <div class="mb-2">
                                            <strong>Dirección:</strong><br>
                                            {{ $location->address ?? 'No especificada' }}
                                        </div>

                                        <div class="mb-2">
                                            <strong>Latitud:</strong>
                                            {{ $location->latitud ?? 'N/A' }}
                                        </div>

                                        <div>
                                            <strong>Longitud:</strong>
                                            {{ $location->longitud ?? 'N/A' }}
                                        </div>

                                    </div>

                                </div>

                                <!-- ACCIONES -->
                                <div class="card-footer bg-white border-0">

                                    <div class="d-grid gap-2">

                                        <form action="{{ route('event-locations.restore', $location->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-outline-success btn-sm w-100">
                                                <i class="fas fa-undo"></i> Restaurar
                                            </button>
                                        </form>

                                        <form action="{{ route('event-locations.force-delete', $location->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('¿Eliminar definitivamente esta ubicación?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm w-100">
                                                <i class="fas fa-trash"></i> Eliminar permanente
                                            </button>
                                        </form>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center text-muted py-5">
                                    <i class="fas fa-map-marker-alt fa-2x mb-2"></i><br>
                                    No hay ubicaciones eliminadas
                                </div>
                            </div>
                        </div>

                    @endforelse

                </div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer bg-white border-top">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>

    </div>
</div>

    <!-- ===================== MODAL AGREGAR PARTICIPANTE ===================== -->
<div class="modal fade" id="modalAgregarParticipante" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <form action="{{ route('event-participants.store') }}" method="POST" class="w-100">
            @csrf
            <input type="hidden" name="event_id" value="{{ $event->id }}">

            <div class="modal-content border-0 shadow-lg rounded-4">

                <!-- HEADER -->
                <div class="modal-header bg-white border-bottom px-4 py-3">
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Agregar participante</h5>
                        <small class="text-muted">Selecciona usuario y ubicación</small>
                    </div>

                    <button type="button" class="btn-close" data-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body p-4">

                    <div class="row g-4">

                        <!-- UBICACION -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Ubicación</label>

                            <select class="form-select" name="event_locations_id" required>
                                <option value="" disabled selected>Selecciona una ubicación</option>
                                @foreach($event->eventLocations as $location)
                                    <option value="{{ $location->id }}">
                                        {{ $location->location_name }} - {{ $location->address }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- USUARIO -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Participante</label>

                            <select class="form-select" name="user_id" required>
                                <option value="" disabled selected>Selecciona un usuario</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }} - {{ $user->email }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- OBSERVACIONES -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Observaciones</label>

                            <textarea class="form-control"
                                name="observations"
                                rows="3"
                                placeholder="Opcional..."></textarea>
                        </div>

                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer bg-white border-top px-4 py-3 d-flex justify-content-between">

                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-user-plus"></i> Agregar
                    </button>

                </div>

            </div>
        </form>

    </div>
</div>

<!-- ===================== MODAL PARTICIPANTES ELIMINADOS ===================== -->
<div class="modal fade" id="modalParticipantesEliminados" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">

        <div class="modal-content border-0 shadow-lg rounded-4">

            <!-- HEADER -->
            <div class="modal-header bg-white border-bottom px-4 py-3">
                <div>
                    <h5 class="modal-title fw-bold mb-0">Participantes eliminados</h5>
                    <small class="text-muted">Puedes restaurarlos o eliminarlos definitivamente</small>
                </div>

                <button type="button" class="btn-close" data-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body p-4">

                <div class="row g-4">

                    @forelse($event->eventParticipants()->onlyTrashed()->get() as $deletedParticipant)

                        @php
                            $user = $deletedParticipant->user;
                            $profilePhoto = $user->profile_photo
                                ? asset('storage/' . $user->profile_photo)
                                : asset('storage/users/user_default.jpg');
                        @endphp

                        <div class="col-md-6 col-lg-4">

                            <div class="card border-0 shadow-sm h-100">

                                <!-- BODY -->
                                <div class="card-body text-center">

                                    <!-- FOTO -->
                                    <img src="{{ $profilePhoto }}"
                                        class="rounded-circle shadow mb-3"
                                        style="width:80px; height:80px; object-fit:cover;">

                                    <!-- NOMBRE -->
                                    <h5 class="fw-bold mb-1">{{ $user->name }}</h5>

                                    <!-- EMAIL -->
                                    <div class="text-muted small mb-2">
                                        {{ $user->email }}
                                    </div>

                                    <hr>

                                    <!-- INFO -->
                                    <div class="small text-muted text-start">

                                        <div class="mb-1">
                                            <strong>Registro:</strong><br>
                                            {{ $deletedParticipant->registration_date }}
                                        </div>

                                        <div class="mb-1">
                                            <strong>Estado:</strong>
                                            <span class="badge bg-secondary">
                                                {{ ucfirst($deletedParticipant->status) }}
                                            </span>
                                        </div>

                                        <div>
                                            <strong>Observaciones:</strong><br>
                                            {{ $deletedParticipant->observations ?? 'Ninguna' }}
                                        </div>

                                    </div>

                                </div>

                                <!-- FOOTER -->
                                <div class="card-footer bg-white border-0">

                                    <div class="btn-group w-100">

                                        <!-- RESTAURAR -->
                                        <form action="{{ route('event-participants.restore', $deletedParticipant->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <button class="btn btn-outline-success btn-sm w-100">
                                                <i class="fas fa-undo"></i> Restaurar
                                            </button>
                                        </form>

                                        <!-- ELIMINAR -->
                                        <form action="{{ route('event-participants.forceDelete', $deletedParticipant->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('¿Eliminar definitivamente?')">

                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-outline-danger btn-sm w-100">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center text-muted">
                                    No hay participantes eliminados.
                                </div>
                            </div>
                        </div>

                    @endforelse

                </div>

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
            center: { lat: -16.4897, lng: -68.1193 },
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
