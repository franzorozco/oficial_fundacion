@extends('adminlte::page')

@section('title', 'Participantes del Evento')

@section('content_header')
    <h1>Participantes del Evento</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span id="card_title">Participantes del Evento</span>
                        @can('event-participants.crear')
                        <a href="{{ route('event-participants.create') }}" class="btn btn-outline-primary btn-sm">
                            Crear Nuevo
                        </a>
                        @endcan
                    </div>

                    {{-- Filtros y búsqueda --}}
                    <div class="card-body">
                        <form method="GET" action="{{ route('event-participants.index') }}" class="form-inline mb-3">
                            @can('event-participants.filtrar')
                            <input type="text" name="search" class="form-control mr-2" placeholder="Buscar por Observación" value="{{ request('search') }}">

                            <select name="status" class="form-control mr-2">
                                <option value="">-- Estado --</option>
                                <option value="activo" {{ request('status') == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ request('status') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>

                            <select name="event_id" class="form-control mr-2">
                                <option value="">-- Evento --</option>
                                @foreach($uniqueEventIds as $id)
                                    <option value="{{ $id }}" {{ request('event_id') == $id ? 'selected' : '' }}>{{ $id }}</option>
                                @endforeach
                            </select>
                            @endcan
                            @can('event-participants.buscar')
                            <button type="submit" class="btn btn-outline-secondary">Filtrar</button>
                            @endcan
                        </form>

                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID del Evento</th>
                                        <th>ID del Usuario</th>
                                        <th>Fecha de Registro</th>
                                        <th>Observaciones</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($eventParticipants as $eventParticipant)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $eventParticipant->event->name }}</td>
                                            <td>{{ $eventParticipant->user->name }}</td>
                                            <td>{{ $eventParticipant->registration_date }}</td>
                                            <td>{{ $eventParticipant->observations }}</td>
                                            <td>{{ $eventParticipant->status }}</td>
                                            <td>
                                                <form action="{{ route('event-participants.destroy', $eventParticipant->id) }}" method="POST" style="display:inline;">
                                                    @can('event-participants.ver')
                                                    <a href="{{ route('event-participants.show', $eventParticipant->id) }}" class="btn btn-outline-primary btn-sm">
                                                        <i class="fa fa-fw fa-eye"></i> Ver
                                                    </a>
                                                    @endcan
                                                    @can('event-participants.editar')
                                                    <a href="{{ route('event-participants.edit', $eventParticipant->id) }}" class="btn btn-outline-success btn-sm">
                                                        <i class="fa fa-fw fa-edit"></i> Editar
                                                    </a>
                                                    @endcan
                                                    @csrf
                                                    @method('DELETE')
                                                    @can('event-participants.eliminar')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="event.preventDefault(); confirm('¿Estás seguro de eliminar?') ? this.closest('form').submit() : false;">
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

                {!! $eventParticipants->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@stop
