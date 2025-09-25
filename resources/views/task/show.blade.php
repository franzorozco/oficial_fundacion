@extends('adminlte::page')

@section('title', $task->name ?? __('Mostrar') . ' ' . __('Tarea'))

@section('content_header')
    <h1>{{ __('Mostrar') }} Tarea</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">{{ __('Mostrar') }} Tarea</h3>
            <a href="{{ route('tasks.index') }}" class="btn btn-primary btn-sm">
                {{ __('Volver') }}
            </a>
        </div>

        <div class="card-body bg-white">
            <div class="mb-3">
                <strong>{{ __('Creador') }}:</strong> {{ $task->user->name }}
            </div>
            <div class="mb-3">
                <strong>{{ __('Nombre') }}:</strong> {{ $task->name }}
            </div>
            <div class="mb-3">
                <strong>{{ __('Descripción') }}:</strong> {{ $task->description }}
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Solicitadas -->
        <div class="col-md-4">
            <h4 class="mb-3">{{ __('Solicitadas') }}</h4>
            <ul class="list-group">
                @forelse($assignments['solicitada'] ?? [] as $assignment)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            {{ $assignment->user->name }}
                            <span class="badge bg-warning text-dark ms-2 text-capitalize">
                                {{ $assignment->status }}
                            </span>
                        </div>
                        <a href="{{ route('task-assignments.show', $assignment->id) }}" class="btn btn-info btn-sm">
                            {{ __('Ver') }}
                        </a>
                    </li>
                @empty
                    <li class="list-group-item text-muted">{{ __('No hay asignaciones solicitadas') }}</li>
                @endforelse
            </ul>
        </div>

        <!-- Denegadas -->
        <div class="col-md-4">
            <h4 class="mb-3">{{ __('Denegadas') }}</h4>
            <ul class="list-group">
                @forelse($assignments['denegada'] ?? [] as $assignment)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            {{ $assignment->user->name }}
                            <span class="badge bg-danger text-capitalize ms-2">
                                {{ $assignment->status }}
                            </span>
                        </div>
                        <a href="{{ route('task-assignments.show', $assignment->id) }}" class="btn btn-info btn-sm">
                            {{ __('Ver') }}
                        </a>
                    </li>
                @empty
                    <li class="list-group-item text-muted">{{ __('No hay asignaciones denegadas') }}</li>
                @endforelse
            </ul>
        </div>

        <!-- Aceptadas y procesos -->
        <div class="col-md-4">
            <h4 class="mb-3">{{ __('Aceptadas y en Proceso') }}</h4>
            <ul class="list-group">
                @php
                    $otherAssignments = $assignments->except(['solicitada', 'denegada'])->flatten();
                @endphp
                @forelse($otherAssignments as $assignment)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            {{ $assignment->user->name }}
                            <span class="badge bg-success text-capitalize ms-2">
                                {{ $assignment->status }}
                            </span>
                        </div>
                        <a href="{{ route('task-assignments.show', $assignment->id) }}" class="btn btn-info btn-sm">
                            {{ __('Ver') }}
                        </a>
                    </li>
                @empty
                    <li class="list-group-item text-muted">{{ __('No hay asignaciones aceptadas o en proceso') }}</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
