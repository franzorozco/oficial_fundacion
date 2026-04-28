@extends('adminlte::page')

@section('title', __('Tareas'))

@section('content_header')
    <h1>{{ __('Tareas') }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            @can('tasks.crear')
            <span id="card_title">
                {{ __('Tareas') }}
            </span>
            @endcan
            @can('tasks.crear')
            <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-sm">
                {{ __('Crear Nueva') }}
            </a>
            @endcan
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success m-4">
                <p>{{ $message }}</p>
            </div>
        @endif

        <div class="card-body bg-white">
            <div class="table-responsive">
<table class="table table-striped table-hover">
    <thead class="thead">
        <tr>
            <th>#</th>
            <th>Creador</th>
            <th>Tarea</th>
            <th>Descripción</th>
            <th>Días</th>
            <th>Horas</th>
            <th>Ubicación</th>
            <th>Transporte</th>
            <th>Habilidades</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tasks as $task)
            <tr>
                <td>{{ ++$i }}</td>

                {{-- CREADOR --}}
                <td>{{ $task->creator?->name ?? 'Sin usuario' }}</td>

                {{-- NOMBRE --}}
                <td><strong>{{ $task->name }}</strong></td>

                {{-- DESCRIPCIÓN --}}
                <td>{{ $task->description ?? '-' }}</td>

                {{-- DÍAS --}}
                <td>{{ $task->required_days ?? '-' }}</td>

                {{-- HORAS --}}
                <td>{{ $task->required_hours ?? '-' }}</td>

                {{-- UBICACIÓN --}}
                <td>{{ $task->location ?? '-' }}</td>

                {{-- TRANSPORTE --}}
                <td>
                    @if($task->requires_transport)
                        <span class="badge bg-warning text-dark">Sí</span>
                    @else
                        <span class="badge bg-secondary">No</span>
                    @endif
                </td>

                {{-- 🔥 SKILLS --}}
                <td>
                    @if($task->skills->count())
                        @foreach ($task->skills as $skill)
                            <span class="badge bg-info text-dark mb-1">
                                {{ $skill->name }} ({{ $skill->pivot->required_level }})
                            </span>
                        @endforeach
                    @else
                        <span class="text-muted">Sin requisitos</span>
                    @endif
                </td>

                {{-- ACCIONES --}}
                <td>
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline">

                        @can('tasks.ver')
                        <a class="btn btn-sm btn-primary" href="{{ route('tasks.show', $task->id) }}">
                            <i class="fa fa-eye"></i>
                        </a>
                        @endcan

                        @can('tasks.editar')
                        <a class="btn btn-sm btn-success" href="{{ route('tasks.edit', $task->id) }}">
                            <i class="fa fa-edit"></i>
                        </a>
                        @endcan

                        @csrf
                        @can('tasks.eliminar')
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="event.preventDefault(); confirm('¿Eliminar?') ? this.closest('form').submit() : false;">
                            <i class="fa fa-trash"></i>
                        </button>
                        @endcan

                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
            </div>
        </div>
    </div>

    {!! $tasks->withQueryString()->links() !!}
@endsection
