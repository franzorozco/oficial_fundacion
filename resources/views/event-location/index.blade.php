@extends('adminlte::page')

@section('title', 'Ubicaciones de Eventos')

@section('content_header')
    <h1>{{ __('Ubicaciones de Eventos') }}</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">

                <!-- HEADER: TÍTULO + BOTONES -->
                <div class="card-header">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 w-100">

                        <!-- Título -->
                        <span id="card_title" class="h5 m-0">
                            {{ __('Ubicaciones de Eventos') }}
                        </span>

                        <!-- Botones -->
                        <div class="d-flex flex-wrap gap-2">

                            @can('event-locations.crear')
                            <a href="{{ route('event-locations.create') }}" class="btn btn-outline-success btn-sm">
                                <i class="fa fa-plus"></i> Crear Nueva
                            </a>
                            @endcan

                            @can('event-locations.verEliminados')
                            <a href="{{ route('event-locations.trashed') }}" class="btn btn-outline-dark btn-sm">
                                <i class="fa fa-trash-restore"></i> Ver Eliminadas
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
                        <div class="alert alert-success mt-3">
                            <p class="mb-0">{{ $message }}</p>
                        </div>
                    @endif

                    <!-- FILTROS -->
                    <div class="collapse mt-3" id="filtrosCollapse">
                        <form method="GET" action="{{ route('event-locations.index') }}" class="w-100">

                            <div class="row g-3">

                                <!-- Búsqueda -->
                                @can('event-locations.filtrar')
                                <div class="col-md-4">
                                    <div class="card card-body p-3 shadow-sm">
                                        <h6 class="mb-3"><i class="fa fa-search"></i> Búsqueda</h6>
                                        <input type="text" name="search" class="form-control"
                                               placeholder="Nombre, dirección o ID del evento"
                                               value="{{ request('search') }}">
                                    </div>
                                </div>

                                <!-- Filtro por Evento -->
                                <div class="col-md-4">
                                    <div class="card card-body p-3 shadow-sm">
                                        <h6 class="mb-3"><i class="fa fa-bullhorn"></i> Evento</h6>
                                        <select name="event_id" class="form-control">
                                            <option value="">-- Todas las Eventos --</option>
                                            @foreach(\App\Models\Event::all() as $event)
                                                <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                                    {{ $event->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Horas -->
                                <div class="col-md-2">
                                    <div class="card card-body p-3 shadow-sm">
                                        <h6 class="mb-2"><i class="fa fa-clock"></i> Hora Inicio Desde</h6>
                                        <input type="time" name="start_from" class="form-control"
                                               value="{{ request('start_from') }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="card card-body p-3 shadow-sm">
                                        <h6 class="mb-2"><i class="fa fa-clock"></i> Hora Inicio Hasta</h6>
                                        <input type="time" name="start_to" class="form-control"
                                               value="{{ request('start_to') }}">
                                    </div>
                                </div>

                                <!-- Fechas -->
                                <div class="col-md-3">
                                    <div class="card card-body p-3 shadow-sm">
                                        <h6 class="mb-2"><i class="fa fa-calendar"></i> Fecha Desde</h6>
                                        <input type="date" name="date_from" class="form-control"
                                               value="{{ request('date_from') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card card-body p-3 shadow-sm">
                                        <h6 class="mb-2"><i class="fa fa-calendar"></i> Fecha Hasta</h6>
                                        <input type="date" name="date_to" class="form-control"
                                               value="{{ request('date_to') }}">
                                    </div>
                                </div>

                                <!-- Botones Filtrar / Limpiar -->
                                @can('event-locations.buscar')
                                <div class="col-md-2">
                                    <div class="card card-body p-3 shadow-sm h-100 d-flex align-items-center justify-content-center flex-column">
                                        <button type="submit" class="btn btn-primary mb-2">
                                            <i class="fa fa-search"></i> Aplicar
                                        </button>
                                        <a href="{{ route('event-locations.index') }}" class="btn btn-outline-secondary">
                                            <i class="fa fa-times"></i> Limpiar
                                        </a>
                                    </div>
                                </div>
                                @endcan

                                @endcan
                            </div>
                        </form>
                    </div>
                </div>

                <!-- TABLA -->
                <div class="card-body bg-white">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead">
                                <tr>
                                    <th>No</th>
                                    <th>ID del Evento</th>
                                    <th>Nombre de Ubicación</th>
                                    <th>Dirección</th>
                                    <th>Latitud</th>
                                    <th>Longitud</th>
                                    <th>Hora de Inicio</th>
                                    <th>Hora de Fin</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($eventLocations as $eventLocation)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $eventLocation->event->name ?? '—' }}</td>
                                        <td>{{ $eventLocation->location_name }}</td>
                                        <td>{{ $eventLocation->address }}</td>
                                        <td>{{ $eventLocation->latitud }}</td>
                                        <td>{{ $eventLocation->longitud }}</td>
                                        <td>{{ $eventLocation->start_hour }}</td>
                                        <td>{{ $eventLocation->end_hour }}</td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                @can('event-locations.ver')
                                                <a href="{{ route('event-locations.show', $eventLocation->id) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                @endcan
                                                @can('event-locations.editar')
                                                <a href="{{ route('event-locations.edit', $eventLocation->id) }}" class="btn btn-outline-success btn-sm">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                @endcan
                                                @can('event-locations.eliminar')
                                                <form action="{{ route('event-locations.destroy', $eventLocation->id) }}" method="POST"
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

                {!! $eventLocations->withQueryString()->links() !!}
            </div>
        </div>
    </div>
</div>
@stop
