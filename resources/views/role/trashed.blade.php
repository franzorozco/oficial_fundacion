@extends('adminlte::page')

@section('title', 'Roles Eliminados')

@section('content_header')
    <h1>Roles Eliminados</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('roles.index') }}" class="btn btn-secondary btn-sm">
                ← Volver a roles activos
            </a>
        </div>

        <div class="card-body bg-white">
            @if ($roles->count())
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nombre</th>
                                <th>Guard</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>{{ $role->guard_name }}</td>
                                    <td>
                                        <form action="{{ route('roles.restore', $role->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-success">
                                                Restaurar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {!! $roles->links() !!}
            @else
                <p>No hay roles eliminados.</p>
            @endif
        </div>
    </div>
@endsection
