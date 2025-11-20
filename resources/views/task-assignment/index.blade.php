@extends('adminlte::page')

@section('title', __('Task Assignments'))

@section('content_header')
    <h1>{{ __('Task Assignments') }}</h1>
@endsection

@section('content')
@if ($message = Session::get('success'))
    <div class="alert alert-success m-3">
        <p class="mb-0">{{ $message }}</p>
    </div>
@endif

<div class="card">

    <!-- HEADER: TÍTULO + BOTONES -->
    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
        <span class="h5 m-0">{{ __('Task Assignments') }}</span>

        <div class="d-flex flex-wrap gap-2">
            @can('task-assignments.crear')
            <a href="{{ route('task-assignments.create') }}" class="btn btn-outline-success btn-sm">
                <i class="fas fa-plus"></i> Create New
            </a>
            @endcan

            @can('task-assignments.pdf')
            <form method="GET" action="{{ route('task-assignments.export-pdf') }}">
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-file-pdf"></i> Descargar PDF
                </button>
            </form>
            @endcan

            @can('task-assignments.filtrar')
            <button class="btn btn-outline-secondary btn-sm" type="button" data-toggle="collapse" data-target="#filtrosCollapse" aria-expanded="false">
                <i class="fa fa-sliders-h"></i> Filtros
            </button>
            @endcan
        </div>
    </div>

    <!-- FILTROS COLAPSABLES -->
    @can('task-assignments.filtrar')
    <div class="collapse mt-3" id="filtrosCollapse">
        <div class="card-body border">
            <form method="GET" action="{{ route('task-assignments.index') }}">
                <div class="row g-3">

                    <!-- Búsqueda general -->
                    <div class="col-md-3">
                        <label class="form-label">Búsqueda general</label>
                        <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Nombre, estado, notas...">
                    </div>

                    <!-- Usuario responsable -->
                    <div class="col-md-3">
                        <label class="form-label">Usuario responsable</label>
                        <select name="user_id" class="form-select">
                            <option value="">Todos</option>
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Supervisor -->
                    <div class="col-md-3">
                        <label class="form-label">Supervisor</label>
                        <select name="supervisor" class="form-select">
                            <option value="">Todos</option>
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ request('supervisor') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Estado -->
                    <div class="col-md-3">
                        <label class="form-label">Estado</label>
                        <select name="status" class="form-select">
                            <option value="">Todos</option>
                            @foreach (['solicitada', 'asignada', 'en_progreso', 'completada', 'cancelada'] as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tipo -->
                    <div class="col-md-3">
                        <label class="form-label">Tipo de asignación</label>
                        <select name="type" class="form-select">
                            <option value="">Todos</option>
                            <option value="task" {{ request('type') == 'task' ? 'selected' : '' }}>Tarea</option>
                            <option value="donation" {{ request('type') == 'donation' ? 'selected' : '' }}>Donación</option>
                        </select>
                    </div>

                    <!-- Rango fechas -->
                    <div class="col-md-3">
                        <label class="form-label">Desde (fecha y hora)</label>
                        <input type="datetime-local" name="from" value="{{ request('from') }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Hasta (fecha y hora)</label>
                        <input type="datetime-local" name="to" value="{{ request('to') }}" class="form-control">
                    </div>

                    <!-- Botones filtrar/limpiar -->
                    <div class="col-md-2 d-grid align-self-end">
                        <button type="submit" class="btn btn-outline-primary">Filtrar</button>
                    </div>
                    <div class="col-md-2 d-grid align-self-end">
                        <a href="{{ route('task-assignments.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                    </div>

                </div>
            </form>
        </div>
    </div>
    @endcan

    <!-- TABLA -->
    <div class="card-body bg-white mt-3">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Task</th>
                        <th>Donation Request</th>
                        <th>Usuario Responsable</th>
                        <th>Supervisor</th>
                        <th>Status</th>
                        <th>Assigned At</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($taskAssignments as $taskAssignment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $taskAssignment->task->name ?? 'N/A' }}</td>
                        <td>{{ $taskAssignment->donationRequest->referencia ?? 'N/A' }}</td>
                        <td>{{ $taskAssignment->assignedUser->name ?? 'N/A' }}</td>
                        <td>{{ $taskAssignment->supervisorUser->name ?? 'N/A' }}</td>
                        <td>{{ $taskAssignment->status }}</td>
                        <td>{{ \Carbon\Carbon::parse($taskAssignment->assigned_at)->format('d/m/Y H:i') }}</td>
                        <td>{{ $taskAssignment->notes }}</td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @can('task-assignments.ver')
                                <a class="btn btn-outline-primary btn-sm" href="{{ route('task-assignments.show', $taskAssignment->id) }}">
                                    <i class="fa fa-eye"></i>
                                </a>
                                @endcan
                                @can('task-assignments.editar')
                                <a class="btn btn-outline-success btn-sm" href="{{ route('task-assignments.edit', $taskAssignment->id) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                @endcan
                                @can('task-assignments.actualizar_estado')
                                <button type="button" class="btn btn-outline-success btn-sm" onclick="openStatusModal('{{ $taskAssignment->id }}', 'asignada')">Aceptar</button>
                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="openStatusModal('{{ $taskAssignment->id }}', 'denegada')">Rechazar</button>
                                @endcan
                                @can('task-assignments.eliminar')
                                <form action="{{ route('task-assignments.destroy', $taskAssignment->id) }}" method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar asignación?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
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

        <!-- PAGINACIÓN -->
        <div class="mt-3">
            {!! $taskAssignments->withQueryString()->links() !!}
        </div>
    </div>
</div>

<!-- MODAL ESTADO -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="statusForm" method="POST" action="">
            @csrf
            @method('PATCH')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Motivo de la decisión</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="notes" class="form-label">Por favor ingresa la razón:</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('js')
<script>
function openStatusModal(taskAssignmentId, status) {
    const form = document.getElementById('statusForm');
    form.action = `/task-assignments/${taskAssignmentId}/update-status/${status}`;
    document.getElementById('notes').value = '';
    const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
    statusModal.show();
}
</script>
@endsection
