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
                            <th>No</th>
                            <th>Nombre del Creador</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks as $task)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $task->user->name }}</td>
                                <td>{{ $task->name }}</td>
                                <td>{{ $task->description }}</td>
                                <td>
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                                        @can('tasks.ver')
                                        <a class="btn btn-sm btn-primary" href="{{ route('tasks.show', $task->id) }}">
                                            <i class="fa fa-fw fa-eye"></i> {{ __('Mostrar') }}
                                        </a>
                                        @endcan
                                        @can('tasks.editar')
                                        <a class="btn btn-sm btn-success" href="{{ route('tasks.edit', $task->id) }}">
                                            <i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}
                                        </a>
                                        @endcan
                                        @csrf
                                        @can('tasks.eliminar')
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('¿Está seguro de eliminar?') ? this.closest('form').submit() : false;">
                                            <i class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}
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
