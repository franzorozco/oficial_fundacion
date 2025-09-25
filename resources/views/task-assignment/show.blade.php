@extends('adminlte::page')

@section('title', __('Show') . ' Task Assignment')

@section('content_header')
    <h1>{{ __('Show') }} Task Assignment</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>{{ __('Task Assignment Details') }}</span>
            <a class="btn btn-primary btn-sm" href="{{ route('task-assignments.index') }}">
                {{ __('Back') }}
            </a>
        </div>

        <div class="card-body bg-white">
            <div class="form-group mb-3">
                <strong>{{ __('Task:') }}</strong>
                {{ $taskAssignment->task->name ?? 'N/A' }}
            </div>
            <div class="form-group mb-3">
                <strong>{{ __('Donation Request:') }}</strong>
                {{ $taskAssignment->donationRequest->referencia ?? 'N/A' }}
            </div>
            <div class="form-group mb-3">
                <strong>{{ __('User:') }}</strong>
                {{ $taskAssignment->assignedUser->name ?? 'N/A' }}
            </div>
            <div class="form-group mb-3">
                <strong>{{ __('Supervisor:') }}</strong>
                {{ $taskAssignment->supervisorUser->name ?? 'N/A' }}
            </div>
            <div class="form-group mb-3">
                <strong>{{ __('Status:') }}</strong>
                {{ $taskAssignment->status }}
            </div>
            <div class="form-group mb-3">
                <strong>{{ __('Assigned At:') }}</strong>
                {{ \Carbon\Carbon::parse($taskAssignment->assigned_at)->format('d/m/Y H:i') }}
            </div>
            <div class="form-group mb-3">
                <strong>{{ __('Notes:') }}</strong>
                {{ $taskAssignment->notes }}
            </div>
            <div class="form-group mb-3">
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
