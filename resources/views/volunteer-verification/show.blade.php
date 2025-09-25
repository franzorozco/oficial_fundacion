@extends('adminlte::page')

@section('title', __('Detalles de Verificación de Voluntario'))

@section('content_header')
    <h1 class="mb-3">{{ __('Detalles de Verificación de Voluntario') }}</h1>
@endsection

@section('content')
    <div class="card shadow border-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom">
            <span class="h5 mb-0">{{ __('Información de la Verificación') }}</span>
            <a href="{{ route('volunteer-verifications.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-chevron-left mr-1"></i> {{ __('Volver') }}
            </a>
        </div>

        <div class="card-body bg-white">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Usuario:</strong>
                    <p>{{ $volunteerVerification->user->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <strong>Responsable:</strong>
                    <p>{{ $volunteerVerification->userResp->name ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Tipo de Documento:</strong>
                    <p>{{ $volunteerVerification->document_type }}</p>
                </div>
                <div class="col-md-6">
                    <strong>Nombre del Documento:</strong>
                    <p>{{ $volunteerVerification->name_document }}</p>
                </div>
            </div>

            <div class="mb-3">
                <strong>Documento:</strong><br>
                @php
                    $url = $volunteerVerification->document_url;
                    $isPdf = str_ends_with(strtolower($url), '.pdf');
                @endphp
                @if ($isPdf)
                    <a href="{{ $url }}" class="btn btn-outline-dark btn-sm" target="_blank" download>
                        <i class="fas fa-file-pdf mr-1"></i> Descargar PDF
                    </a>
                @else
                    <a href="{{ $url }}" class="btn btn-outline-dark btn-sm" target="_blank">
                        <i class="fas fa-link mr-1"></i> Ver Enlace
                    </a>
                @endif
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Estado:</strong><br>
                    <span class="badge 
                        @if($volunteerVerification->status == 'approved') badge-success 
                        @elseif($volunteerVerification->status == 'rejected') badge-danger 
                        @else badge-warning 
                        @endif px-3 py-1">
                        {{ ucfirst($volunteerVerification->status) }}
                    </span>
                </div>
                <div class="col-md-6">
                    <strong>Comentario:</strong>
                    <p>{{ $volunteerVerification->coment ?? 'Ninguno' }}</p>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <form action="{{ route('volunteer-verifications.approve', $volunteerVerification->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="button" class="btn btn-success btn-sm" onclick="openModal('approve', {{ $volunteerVerification->id }})">
                        <i class="fas fa-check mr-1"></i>Aceptar
                    </button>
                </form>

                <form action="{{ route('volunteer-verifications.reject', $volunteerVerification->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="button" class="btn btn-danger btn-sm" onclick="openModal('reject', {{ $volunteerVerification->id }})">
                        <i class="fas fa-times mr-1"></i>Rechazar
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="actionModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="actionForm" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Confirmar acción</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                <p id="modalMessage">¿Estás seguro de realizar esta acción?</p>
                <div class="form-group">
                    <label for="coment">Justificación / Nota:</label>
                    <textarea name="coment" id="coment" class="form-control" required></textarea>
                </div>
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Confirmar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
            </form>
        </div>
    </div>
@endsection 
<script>
function openModal(action, id) {
    const form = document.getElementById('actionForm');
    const modalMessage = document.getElementById('modalMessage');
    const modalTitle = document.getElementById('modalLabel');

    let route = '';
    if (action === 'approve') {
        route = `/volunteer-verifications/${id}/approve`; // Ajusta si usas prefijo de ruta
        modalMessage.innerText = '¿Estás seguro de aprobar esta solicitud?';
        modalTitle.innerText = 'Aprobar solicitud';
    } else if (action === 'reject') {
        route = `/volunteer-verifications/${id}/reject`;
        modalMessage.innerText = '¿Estás seguro de rechazar esta solicitud?';
        modalTitle.innerText = 'Rechazar solicitud';
    }

    form.action = route;
    document.getElementById('coment').value = ''; // Limpia comentario
    $('#actionModal').modal('show');
}
</script>