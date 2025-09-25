@extends('adminlte::page')

@section('title', __('Mostrar Solicitud de Donación'))

@section('content_header')
    <h1>{{ __('Detalles de la Solicitud de Donación') }}</h1>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="card-title">Información Completa de la Solicitud</span>
        <a class="btn btn-primary btn-sm" href="{{ route('donation-requests.index') }}">
            {{ __('Volver') }}
        </a>
    </div>

    <div class="card-body bg-white">
        {{-- Información General y del Usuario Solicitante --}}
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white">
                        <strong>Información General</strong>
                    </div>
                    <div class="card-body bg-light">
                        <div class="form-group mb-2">
                            <strong>Solicitante:</strong>
                            {{ $donationRequest->applicantUser->name ?? 'N/A' }}
                        </div>
                        <div class="form-group mb-2">
                            <strong>Tipo de Donación:</strong>
                            {{ $donationRequest->donation->name ?? 'N/A' }}
                        </div>
                        <div class="form-group mb-2">
                            <strong>Fecha de Solicitud:</strong>
                            {{ $donationRequest->request_date->format('d/m/Y H:i') }}
                        </div>
                        <div class="form-group mb-2">
                            <strong>Estado:</strong>
                            <span class="badge bg-info text-dark text-uppercase">{{ $donationRequest->state }}</span>
                        </div>
                        <div class="form-group mb-2">
                            <strong>Encargado:</strong>
                            {{ $donationRequest->userInCharge->name ?? 'No asignado' }}
                        </div>
                        <div class="form-group mb-2">
                            <strong>Notas Adicionales:</strong><br>
                            {{ $donationRequest->notes ?? 'Sin notas' }}
                        </div>
                        @if ($donationRequest->state === 'pendiente')
                        <div class="mt-4 d-flex justify-content-center gap-3">
                            <!-- Botón para abrir modal de aceptación -->
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#acceptModal-{{ $donationRequest->id }}">
                                Aceptar Solicitud
                            </button>

                            <!-- Botón para abrir modal de rechazo -->
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal-{{ $donationRequest->id }}">
                                Rechazar Solicitud
                            </button>
                            
                            <!-- Modal Aceptar -->
                                <div class="modal fade" id="acceptModal-{{ $donationRequest->id }}" tabindex="-1" aria-labelledby="acceptModalLabel-{{ $donationRequest->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form method="POST" action="{{ route('donation-requests.accept', $donationRequest->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="acceptModalLabel-{{ $donationRequest->id }}">Confirmar Aceptación</h5>
                                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Cerrar"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>¿Estás seguro de que deseas aceptar esta solicitud?</p>
                                                    <div class="mb-3">
                                                        <label class="form-label">Observaciones (opcional)</label>
                                                        <textarea name="observations" class="form-control" rows="3"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">Aceptar</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Modal Rechazar -->
                                <div class="modal fade" id="rejectModal-{{ $donationRequest->id }}" tabindex="-1" aria-labelledby="rejectModalLabel-{{ $donationRequest->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form method="POST" action="{{ route('donation-requests.reject', $donationRequest->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="rejectModalLabel-{{ $donationRequest->id }}">Confirmar Rechazo</h5>
                                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Cerrar"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>¿Estás seguro de que deseas rechazar esta solicitud?</p>
                                                    <div class="mb-3">
                                                        <label class="form-label">Observaciones (opcional)</label>
                                                        <textarea name="observations" class="form-control" rows="3"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-danger">Rechazar</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                        </div>
                    @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white">
                        <strong>Información del Usuario Solicitante</strong>
                    </div>
                    <div class="card-body bg-light">
                        <div class="form-group mb-2">
                            <strong>Nombre:</strong>
                            {{ $donationRequest->applicantUser->name ?? 'N/A' }}
                        </div>
                        <div class="form-group mb-2">
                            <strong>Correo Electrónico:</strong>
                            {{ $donationRequest->userInCharge->email ?? 'N/A' }}
                        </div>
                        <div class="form-group mb-2">
                            <strong>Teléfono:</strong>
                            {{ $donationRequest->applicantUser->phone ?? 'N/A' }}
                        </div>
                        <div class="form-group mb-2">
                            <strong>Dirección:</strong>
                            {{ $donationRequest->applicantUser->address ?? 'N/A' }}
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        {{-- Descripción del Beneficiario --}}
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <strong>Descripción del Beneficiario</strong>
            </div>
            <div class="card-body bg-light">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <strong>Nombre:</strong>
                            {{ $donationRequest->description->recipient_name ?? 'N/A' }}
                        </div>
                        <div class="form-group mb-2">
                            <strong>Dirección:</strong>
                            {{ $donationRequest->description->recipient_address ?? 'N/A' }}
                        </div>
                        <div class="form-group mb-2">
                            <strong>Contacto:</strong>
                            {{ $donationRequest->description->recipient_contact ?? 'N/A' }}
                        </div>
                        <div class="form-group mb-2">
                            <strong>Tipo:</strong>
                            {{ ucfirst($donationRequest->description->recipient_type ?? 'N/A') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <strong>Razón de la Solicitud:</strong><br>
                            {{ $donationRequest->description->reason ?? 'N/A' }}
                        </div>
                        <div class="form-group mb-2">
                            <strong>Instrucciones Adicionales:</strong><br>
                            {{ $donationRequest->description->extra_instructions ?? 'N/A' }}
                        </div>
                        <div class="form-group mb-2">
                            <strong>Documentos de Soporte:</strong><br>
                            @if (!empty($donationRequest->description->supporting_documents))
                                <a href="{{ asset('storage/' . $donationRequest->description->supporting_documents) }}" target="_blank" class="btn btn-sm btn-primary">
                                    Descargar Documento
                                </a>
                            @else
                                N/A
                            @endif
                        </div>

                        <div class="form-group mb-2">
                            <strong>Ubicación Geográfica:</strong><br>
                            Latitud: {{ $donationRequest->description->latitude ?? 'N/A' }},
                            Longitud: {{ $donationRequest->description->longitude ?? 'N/A' }}
                        </div>
                    </div>
                </div>
                <div id="map" style="height: 400px; width: 100%;" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('js')
    
    <script>
        function initMap() {
            const lat = parseFloat({{ $donationRequest->description->latitude ?? 0 }});
            const lng = parseFloat({{ $donationRequest->description->longitude ?? 0 }});

            const mapOptions = {
                center: { lat: lat, lng: lng },
                zoom: 15,
            };

            const map = new google.maps.Map(document.getElementById("map"), mapOptions);

            new google.maps.Marker({
                position: { lat: lat, lng: lng },
                map: map,
                title: "Ubicación del beneficiario"
            });
        }
    </script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2GCanK5Gxm26zDyPrKc7MNy7WhAJZK7M&callback=initMap"
        async defer>
    </script>

@endsection

