@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <h1>Usuarios</h1>
@stop

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span id="card_title">Usuarios</span>
                
                <!-- Formulario de búsqueda -->
                <form method="GET" action="{{ route('users.index') }}" style="display: inline-block;">
                    <input type="text" name="search" class="form-control" placeholder="Buscar usuarios" value="{{ request('search') }}" style="width: 200px; display: inline-block;">
                    <button class="btn btn-secondary btn-sm" type="submit">Buscar</button>
                </form>
                <div class="float-right">
                    @can('users.create')
                    <a href="{{ route('users.create') }}" class="btn btn-outline-primary btn-sm">
                        Crear nuevo
                    </a>
                    @endcan
                    <a href="{{ route('users.trashed') }}" class="btn btn-outline-danger btn-sm">
                        Ver usuarios eliminados
                    </a>
                    <a href="{{ route('users.pdf', ['search' => request('search')]) }}" class="btn btn-outline-success btn-sm">
                        Regenerar PDF
                    </a>
                </div>

                
            </div>
        </div>

        <div class="card-body bg-white">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                <thead class="thead">
                    <tr>
                        <th>N°</th>
                        <th>Nombre</th>
                        <th>Correo electrónico</th>
                        <th>Celular</th>
                        <th>N° de documento</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->profile->document_number ?? 'N/A' }}</td>
                            <td>
                                @if ($user->roles->isNotEmpty())
                                    {{ $user->roles->first()->name }}
                                @else
                                    Sin rol
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                    @can('users.view')
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('users.show', $user->id) }}"><i class="fa fa-fw fa-eye"></i> Ver</a>
                                    @endcan
                                    @can('users.edit')
                                    <a class="btn btn-sm btn-outline-success" href="{{ route('users.edit', $user->id) }}"><i class="fa fa-fw fa-edit"></i> Editar</a>
                                    @endcan
                                    @can('users.editRol')
                                    <a class="btn btn-sm btn-outline-warning" href="{{ route('users.editRol', $user->id) }}"><i class="fa fa-fw fa-user-tag"></i> Rol</a>
                                    @endcan

                                    @csrf
                                    @method('DELETE')
                                    @can('users.delete')
                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                        onclick="event.preventDefault(); confirm('¿Estás seguro de eliminar?') ? this.closest('form').submit() : false;">
                                        <i class="fa fa-fw fa-trash"></i> Eliminar
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

    {!! $users->withQueryString()->links() !!}
@stop
