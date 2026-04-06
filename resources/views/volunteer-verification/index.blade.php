@extends('adminlte::page')

@section('title', 'Verificaciones de Voluntarios')

@section('content_header')
    <h1 class="mb-0">Verificaciones de Voluntarios</h1>
@stop

@section('content')

<div class="card shadow-sm">
    {{-- HEADER --}}
<div class="card-header bg-white">

    <form method="GET"
        action="{{ route('volunteer-verifications.index') }}">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">

            {{-- IZQUIERDA: BUSCADOR --}}
            <div class="d-flex align-items-center gap-2 flex-grow-1">

                <input type="text"
                    name="search"
                    class="form-control"
                    style="max-width: 280px;"
                    placeholder="Buscar verificaciones..."
                    value="{{ request('search') }}">

                <button class="btn btn-secondary">
                    <i class="fa fa-search"></i>
                </button>

                <a href="{{ route('volunteer-verifications.index') }}"
                   class="btn btn-outline-secondary">
                    Limpiar
                </a>

            </div>

            {{-- DERECHA: ACCIONES --}}
            <div class="d-flex flex-wrap gap-2">

                @can('volunteer-verifications.crear')
                    <a href="{{ route('volunteer-verifications.create') }}"
                       class="btn btn-primary">
                        <i class="fa fa-plus"></i> Nueva
                    </a>
                @endcan

                @can('volunteer-verifications.regenerarPDF')
                    <a href="{{ route('volunteer-verifications.pdf') }}"
                       class="btn btn-outline-success">
                        <i class="fa fa-file-pdf"></i>
                    </a>
                @endcan

                @can('volunteer-verifications.verEliminados')
                    <a href="{{ route('volunteer-verifications.trashed') }}"
                       class="btn btn-outline-danger">
                        Eliminados
                    </a>
                @endcan

                @can('volunteer-verifications.misDecisiones')
                    <a href="{{ route('volunteer-verifications.mis-decisiones') }}"
                       class="btn btn-outline-info">
                        Mis decisiones
                    </a>
                @endcan

            </div>

        </div>

    </form>

</div>

    {{-- ALERTA --}}
    @if ($message = Session::get('success'))
        <div class="alert alert-success m-3">
            {{ $message }}
        </div>
    @endif

    {{-- TABLA --}}
    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Usuario</th>
                        <th>Responsable</th>
                        <th>Documento</th>
                        <th>Archivo</th>
                        <th>Estado</th>
                        <th>Comentario</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($volunteerVerifications as $v)
                        <tr>
                            <td>{{ ++$i }}</td>

                            <td>{{ $v->user?->name ?? 'N/A' }}</td>

                            <td>{{ $v->userResp?->name ?? 'N/A' }}</td>

                            <td>{{ $v->document_type }}</td>

                            <td>
                                <a href="{{ $v->document_url }}"
                                   target="_blank"
                                   class="btn btn-sm btn-outline-info">
                                    Ver archivo
                                </a>
                            </td>

                            {{-- ESTADO --}}
                            <td>
                                @php
                                    $status = $v->status;
                                    $badge = match($status) {
                                        'aprobado' => 'bg-success',
                                        'rechazado' => 'bg-danger',
                                        'reconsideracion' => 'bg-warning text-dark',
                                        default => 'bg-secondary'
                                    };
                                @endphp

                                <span class="badge {{ $badge }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>

                            <td>{{ $v->coment ?? '-' }}</td>

                            {{-- ACCIONES --}}
                            <td class="text-center">

    <div class="d-inline-flex align-items-center gap-1">

        {{-- APROBAR --}}
        @can('volunteer-verifications.aprobar')
            <button type="button"
                class="btn btn-success btn-sm"
                onclick="openModal('approve', {{ $v->id }})">
                <i class="fa fa-check"></i>
            </button>
        @endcan

        {{-- RECHAZAR --}}
        @can('volunteer-verifications.rechazar')
            <button type="button"
                class="btn btn-danger btn-sm"
                onclick="openModal('reject', {{ $v->id }})">
                <i class="fa fa-times"></i>
            </button>
        @endcan

        {{-- VER --}}
        @can('volunteer-verifications.ver')
            <a href="{{ route('volunteer-verifications.show', $v->id) }}"
               class="btn btn-outline-primary btn-sm">
                <i class="fa fa-eye"></i>
            </a>
        @endcan

        {{-- EDITAR --}}
        @can('volunteer-verifications.editar')
            <a href="{{ route('volunteer-verifications.edit', $v->id) }}"
               class="btn btn-outline-success btn-sm">
                <i class="fa fa-edit"></i>
            </a>
        @endcan

        {{-- ELIMINAR --}}
        @can('volunteer-verifications.eliminar')
            <form method="POST"
                action="{{ route('volunteer-verifications.destroy', $v->id) }}"
                class="d-inline">
                @csrf @method('DELETE')

                <button type="submit"
                    class="btn btn-outline-danger btn-sm"
                    onclick="return confirm('¿Eliminar?')">
                    <i class="fa fa-trash"></i>
                </button>
            </form>
        @endcan

    </div>

</td>

                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

    </div>

    {{-- PAGINACIÓN --}}
    <div class="card-footer">
        {!! $volunteerVerifications->withQueryString()->links() !!}
    </div>

</div>

{{-- MODAL --}}
<div class="modal fade" id="actionModal">
    <div class="modal-dialog">
        <form id="actionForm" method="POST">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5 id="modalLabel">Confirmar acción</h5>
                    <button type="button" class="btn-close" data-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p id="modalMessage"></p>

                    <textarea name="coment"
                        class="form-control"
                        placeholder="Escribe una justificación..."
                        required></textarea>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Confirmar</button>
                    <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                        Cancelar
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

@endsection

@section('js')

<script>
function openModal(action, id) {
    const form = document.getElementById('actionForm');
    const modalMessage = document.getElementById('modalMessage');
    const modalTitle = document.getElementById('modalLabel');

    let route = '';
    if (action === 'approve') {
        route = `/volunteer-verifications/${id}/approve`;
        modalMessage.innerText = '¿Estás seguro de aprobar esta solicitud?';
        modalTitle.innerText = 'Aprobar solicitud';
    } else if (action === 'reject') {
        route = `/volunteer-verifications/${id}/reject`;
        modalMessage.innerText = '¿Estás seguro de rechazar esta solicitud?';
        modalTitle.innerText = 'Rechazar solicitud';
    }

    form.action = route;
    document.getElementById('coment').value = '';
    $('#actionModal').modal('show');
}
</script>
@endsection