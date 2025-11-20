@extends('adminlte::page')

@section('title', 'Participantes del Evento')

@section('content_header')
    <h1>{{ __('Participantes del Evento') }}</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">

                <!-- HEADER: TÍTULO + BOTONES -->
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">

                    <!-- Título -->
                    <span id="card_title" class="h5 m-0">
                        {{ __('Participantes del Evento') }}
                    </span>

                    <!-- Botones -->
                    <div class="d-flex flex-wrap gap-2">
                        @can('event-participants.crear')
                        <a href="{{ route('event-participants.create') }}" class="btn btn-outline-success btn-sm">
                            <i class="fa fa-plus"></i> Crear Nuevo
                        </a>
                        @endcan

                        <!-- Botón filtros -->
                        <button class="btn btn-outline-secondary btn-sm" type="button" data-toggle="collapse"
                                data-target="#filtrosCollapse" aria-expanded="false" aria-controls="filtrosCollapse">
                            <i class="fa fa-sliders-h"></i> Filtros
                        </button>
                    </div>

                </div>

                <!-- Mensaje de éxito -->
                @if($message = Session::get('success'))
                    <div class="alert alert-success m-3">
                        <p class="mb-0">{{ $message }}</p>
                    </div>
                @endif

                <!-- FILTROS -->
                <div class="collapse mt-3" id="filtrosCollapse">
                    <form method="GET" action="{{ route('event-participants.index') }}" class="w-100">

                        <div class="row g-3">

                            <!-- Observaciones -->
                            @can('event-participants.filtrar')
                            <div class="col-md-4">
                                <div class="card card-body p-3 shadow-sm">
                                    <h6 class="mb-2"><i class="fa fa-search"></i> Observaciones</h6>
                                    <input type="text" name="search" class="form-control"
                                           placeholder="Buscar por observación"
                                           value="{{ request('search') }}">
                                </div>
                            </div>

                            <!-- Estado -->
                            <div class="col-md-3">
                                <div class="card card-body p-3 shadow-sm">
                                    <h6 class="mb-2"><i class="fa fa-flag"></i> Estado</h6>
                                    <select name="status" class="form-control">
                                        <option value="">-- Todos --</option>
                                        <option value="activo" {{ request('status') == 'activo' ? 'selected' : '' }}>Activo</option>
                                        <option value="inactivo" {{ request('status') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Evento -->
                            <div class="col-md-3">
                                <div class="card card-body p-3 shadow-sm">
                                    <h6 class="mb-2"><i class="fa fa-bullhorn"></i> Evento</h6>
                                    <select name="event_id" class="form-control">
                                        <option value="">-- Todos los Eventos --</option>
                                        @foreach($uniqueEventIds as $id)
                                            <option value="{{ $id }}" {{ request('event_id') == $id ? 'selected' : '' }}>
                                                {{ $id }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Botones Filtrar / Limpiar -->
                            @can('event-participants.buscar')
                            <div class="col-md-2">
                                <div class="card card-body p-3 shadow-sm h-100 d-flex align-items-center justify-content-center flex-column">
                                    <button type="submit" class="btn btn-primary mb-2">
                                        <i class="fa fa-search"></i> Aplicar
                                    </button>
                                    <a href="{{ route('event-participants.index') }}" class="btn btn-outline-secondary">
                                        <i class="fa fa-times"></i> Limpiar
                                    </a>
                                </div>
                            </div>
                            @endcan

                            @endcan
                        </div>
                    </form>
                </div>

                <!-- TABLA -->
                <div class="card-body bg-white mt-3">
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
                                        <td>{{ $eventParticipant->event->name ?? '—' }}</td>
                                        <td>{{ $eventParticipant->user->name ?? '—' }}</td>
                                        <td>{{ $eventParticipant->registration_date }}</td>
                                        <td>{{ $eventParticipant->observations }}</td>
                                        <td>{{ $eventParticipant->status }}</td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                @can('event-participants.ver')
                                                <a href="{{ route('event-participants.show', $eventParticipant->id) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                @endcan
                                                @can('event-participants.editar')
                                                <a href="{{ route('event-participants.edit', $eventParticipant->id) }}" class="btn btn-outline-success btn-sm">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                @endcan
                                                @can('event-participants.eliminar')
                                                <form action="{{ route('event-participants.destroy', $eventParticipant->id) }}" method="POST"
                                                      onsubmit="return confirm('¿Seguro que deseas eliminar?');">
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
                </div>

                {!! $eventParticipants->withQueryString()->links() !!}

            </div>
        </div>
    </div>
</div>
@stop
