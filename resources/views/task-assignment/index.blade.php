@extends('adminlte::page')

@section('title', __('Task Assignments'))

@section('content_header')
    <h1>{{ __('Task Assignments') }}</h1>
@stop

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="card">
        @can('task-assignments.crear')
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>{{ __('Task Assignments') }}</span>
            <a href="{{ route('task-assignments.create') }}" class="btn btn-primary btn-sm">
                {{ __('Create New') }}
            </a>
        </div>
        @endcan
        <div class="card-body bg-white">
            @can('task-assignments.filtrar')
            <form method="GET" action="{{ route('task-assignments.index') }}" class="mb-3">
                <div class="row g-3">

                    {{-- Búsqueda general --}}
                    <div class="col-md-4">
                        <label for="search" class="form-label">Búsqueda general</label>
                        <input type="search" name="search" id="search" value="{{ request('search') }}" class="form-control" placeholder="Buscar por nombre, estado, notas...">
                    </div>

                    {{-- Usuario Responsable --}}
                    <div class="col-md-4">
                        <label for="user_id" class="form-label">Usuario responsable</label>
                        <select name="user_id" id="user_id" class="form-select">
                            <option value="">Todos</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Supervisor --}}
                    <div class="col-md-4">
                        <label for="supervisor" class="form-label">Supervisor</label>
                        <select name="supervisor" id="supervisor" class="form-select">
                            <option value="">Todos</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ request('supervisor') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Estado --}}
                    <div class="col-md-4">
                        <label for="status" class="form-label">Estado</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Todos</option>
                            @foreach (['solicitada', 'asignada', 'en_progreso', 'completada', 'cancelada'] as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tipo de asignación --}}
                    <div class="col-md-4">
                        <label for="type" class="form-label">Tipo de asignación</label>
                        <select name="type" id="type" class="form-select">
                            <option value="">Todos</option>
                            <option value="task" {{ request('type') == 'task' ? 'selected' : '' }}>Tarea</option>
                            <option value="donation" {{ request('type') == 'donation' ? 'selected' : '' }}>Donación</option>
                        </select>
                    </div>

                    {{-- Rango de fecha y hora --}}
                    <div class="col-md-4">
                        <label for="from" class="form-label">Desde (fecha y hora)</label>
                        <input type="datetime-local" name="from" id="from" value="{{ request('from') }}" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label for="to" class="form-label">Hasta (fecha y hora)</label>
                        <input type="datetime-local" name="to" id="to" value="{{ request('to') }}" class="form-control">
                    </div>

                    {{-- Botones --}}
                    <div class="col-md-2 d-grid align-self-end">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </div>

                    <div class="col-md-2 d-grid align-self-end">
                        <a href="{{ route('task-assignments.index') }}" class="btn btn-secondary">Limpiar</a>
                    </div>
                    
                </div>
            </form>
            @endcan
            @can('task-assignments.pdf')
            <div class="col-md-2 d-grid align-self-end">
                <button type="submit" formaction="{{ route('task-assignments.export-pdf') }}" class="btn btn-outline-danger">
                    Descargar PDF
                </button>
            </div>
            @endcan
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Task</th>
                            <th>Donation Request</th>
                            <th>usuario  responsable</th>
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
                                    @can('task-assignments.ver')
                                    <a class="btn btn-sm btn-primary" href="{{ route('task-assignments.show', $taskAssignment->id) }}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    @endcan
                                    @can('task-assignments.editar')
                                    <a class="btn btn-sm btn-success" href="{{ route('task-assignments.edit', $taskAssignment->id) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endcan
                                    @can('task-assignments.actualizar_estado')
                                    <!-- Botón Aceptar -->
                                    <button type="button" class="btn btn-sm btn-success" 
                                            onclick="openStatusModal('{{ $taskAssignment->id }}', 'asignada')">
                                        Aceptar
                                    </button>

                                    <!-- Botón Rechazar -->
                                    <button type="button" class="btn btn-sm btn-warning" 
                                            onclick="openStatusModal('{{ $taskAssignment->id }}', 'denegada')">
                                        Rechazar
                                    </button>
                                    @endcan
                                    @can('task-assignments.eliminar')
                                    <!-- Botón Eliminar -->
                                    <form action="{{ route('task-assignments.destroy', $taskAssignment->id) }}" method="POST" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar asignación?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Modal -->
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


    {!! $taskAssignments->withQueryString()->links() !!}
@stop

@section('css')
    {{-- Custom CSS opcional --}}
@stop

@section('js')
    <script>
    function openStatusModal(taskAssignmentId, status) {
        // Actualizamos el action del formulario según id y status
        const form = document.getElementById('statusForm');
        form.action = `/task-assignments/${taskAssignmentId}/update-status/${status}`;

        // Limpiamos el textarea por si quedó algo previo
        document.getElementById('notes').value = '';

        // Abrimos el modal con Bootstrap 5
        const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
        statusModal.show();
    }
    </script>
@stop

