@extends('adminlte::page')

@section('title', 'Verificaciones de Voluntarios')

@section('content_header')
    <h1>{{ __('Verificaciones de Voluntarios') }}</h1>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <!-- Encabezado de la tarjeta -->
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <span id="card_title">
                                {{ __('Verificaciones de Voluntarios') }}
                            </span>
                            <div>
                                @can('volunteer-verifications.crear')
                                <a href="{{ route('volunteer-verifications.create') }}" class="btn btn-outline-primary btn-sm me-2">
                                    <i class="fa fa-plus"></i> {{ __('Nueva solicitud') }}
                                </a>
                                @endcan
                                @can('volunteer-verifications.regenerarPDF')
                                <a href="{{ route('volunteer-verifications.pdf') }}" class="btn btn-outline-success btn-sm me-2">
                                    <i class="fa fa-file-pdf"></i> {{ __('Generar PDF') }}
                                </a>
                                @endcan
                                @can('volunteer-verifications.verEliminados')
                                <a href="{{ route('volunteer-verifications.trashed') }}" class="btn btn-outline-dark btn-sm me-2">
                                    <i class="fas fa-trash-alt"></i> {{ __('Ver Eliminados') }}
                                </a>
                                @endcan
                                @can('volunteer-verifications.misDecisiones')
                                <a href="{{ route('volunteer-verifications.mis-decisiones') }}" class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-user-check"></i> {{ __('Mis Decisiones') }}
                                </a>
                                @endcan
                            </div>
                        </div>

                    </div>
 
                    <!-- Mensaje de éxito -->
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <!-- Formulario de búsqueda -->
                    <form method="GET" action="{{ route('volunteer-verifications.index') }}" class="p-3 pt-0">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Buscar..." value="{{ request()->input('search') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-search"></i> {{ __('Buscar') }}
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('volunteer-verifications.index') }}" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-eraser"></i> {{ __('Limpiar') }}
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Cuerpo de la tarjeta con tabla -->
                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nombre del Usuario</th>
                                        <th>Nombre del Responsable</th>
                                        <th>Tipo de Documento</th>
                                        <th>URL del Documento</th>
                                        <th>Nombre del Documento</th>
                                        <th>Estado</th>
                                        <th>Comentario</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($volunteerVerifications as $volunteerVerification)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $volunteerVerification->user?->name ?? 'N/A' }}</td>
                                            <td>{{ $volunteerVerification->userResp?->name ?? 'N/A' }}</td>
                                            <td>{{ $volunteerVerification->document_type }}</td>
                                            <td>
                                                <a href="{{ $volunteerVerification->document_url }}" target="_blank">Ver Documento</a>
                                            </td>
                                            <td>{{ $volunteerVerification->name_document }}</td>
                                            <td>
                                                @php
                                                    $status = $volunteerVerification->status;
                                                    $badgeClass = match($status) {
                                                        'aprobado' => 'bg-success',
                                                        'rechazado' => 'bg-danger',
                                                        'reconsideracion' => 'bg-warning text-dark',
                                                        default => 'bg-secondary'
                                                    };
                                                @endphp
                                                <span class="badge {{ $badgeClass }} text-uppercase">{{ $status }}</span>
                                            </td>
                                            <td>{{ $volunteerVerification->coment }}</td>
                                            <td>
                                                {{-- Grupo de decisiones --}}
                                                <div class="mb-1 d-flex flex-wrap gap-1">
                                                    @can('volunteer-verifications.aprobar')
                                                    <form action="{{ route('volunteer-verifications.approve', $volunteerVerification->id) }}" method="POST">
                                                        @csrf
                                                        <button type="button" class="btn btn-success btn-sm" onclick="openModal('approve', {{ $volunteerVerification->id }})">
                                                            <i class="fas fa-check mr-1"></i> Aceptar
                                                        </button>
                                                    </form>
                                                    @endcan
                                                    @can('volunteer-verifications.rechazar')
                                                    <form action="{{ route('volunteer-verifications.reject', $volunteerVerification->id) }}" method="POST">
                                                        @csrf
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="openModal('reject', {{ $volunteerVerification->id }})">
                                                            <i class="fas fa-times mr-1"></i> Rechazar
                                                        </button>
                                                    </form>
                                                    @endcan
                                                </div>

                                                {{-- Grupo de navegación --}}
                                                <div class="mb-1 d-flex flex-wrap gap-1">
                                                    @can('volunteer-verifications.ver')
                                                    <a class="btn btn-outline-primary btn-sm" href="{{ route('volunteer-verifications.show', $volunteerVerification->id) }}">
                                                        <i class="fa fa-eye"></i> Ver
                                                    </a>
                                                    @endcan
                                                    @can('volunteer-verifications.editar')
                                                    <a class="btn btn-outline-success btn-sm" href="{{ route('volunteer-verifications.edit', $volunteerVerification->id) }}">
                                                        <i class="fa fa-edit"></i> Editar
                                                    </a>
                                                    @endcan
                                                </div>

                                                {{-- Grupo de eliminación --}}
                                                <div class="d-flex flex-wrap gap-1">
                                                    <form action="{{ route('volunteer-verifications.destroy', $volunteerVerification->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        @can('volunteer-verifications.eliminar')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar?')">
                                                            <i class="fa fa-trash"></i> Eliminar
                                                        </button>
                                                        @endcan
                                                    </form>
                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>


                        </div>
                    </div>

                    <!-- Paginación -->
                    <div class="card-footer">
                        {!! $volunteerVerifications->withQueryString()->links() !!}
                    </div>
                </div>
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
