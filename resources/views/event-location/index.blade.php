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
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">{{ __('Ubicaciones de Eventos') }}</span>
                            <div class="float-right">
                                @can('event-locations.crear')
                                <a href="{{ route('event-locations.create') }}" class="btn btn-outline-primary btn-sm">
                                    {{ __('Crear Nueva') }}
                                </a>
                                @endcan
                                {{-- Botón para ver ubicaciones eliminadas --}}
                                @can('event-locations.verEliminados')
                                <a href="{{ route('event-locations.trashed') }}" class="btn btn-outline-dark btn-sm">
                                    <i class="fa fa-trash-alt"></i> {{ __('Ver Eliminadas') }}
                                </a>
                                @endcan
                            </div>
                        </div>

                        {{-- Buscador --}}
                        <form method="GET" action="{{ route('event-locations.index') }}" class="mt-3">
                            @can('event-locations.filtrar')
                            <div class="row mb-3">
                                <!-- Búsqueda general -->
                                <div class="col-md-4">
                                    <input type="text" name="search" class="form-control" placeholder="Buscar por nombre, dirección o ID del evento"
                                        value="{{ request()->query('search') }}">
                                </div>

                                <!-- Filtro por evento -->
                                <div class="col-md-3">
                                    <select name="event_id" class="form-control">
                                        <option value="">-- Filtrar por Evento --</option>
                                        @foreach(\App\Models\Event::all() as $event)
                                            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                                {{ $event->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Horas -->
                                <div class="col-md-2">
                                    <input type="time" name="start_from" class="form-control" value="{{ request('start_from') }}" placeholder="Inicio desde">
                                </div>

                                <div class="col-md-2">
                                    <input type="time" name="start_to" class="form-control" value="{{ request('start_to') }}" placeholder="Inicio hasta">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <!-- Fechas -->
                                <div class="col-md-3">
                                    <label for="date_from">Fecha desde:</label>
                                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="date_to">Fecha hasta:</label>
                                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                                </div>
                                
                            </div>
                            @endcan
                                @can('event-locations.buscar')
                                <div class="col-md-2 align-self-end">
                                    <button class="btn btn-outline-secondary btn-block" type="submit">Buscar</button>
                                </div>
                                @endcan
                        </form>


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
                                            <td>{{ $eventLocation->event->name }}</td>
                                            <td>{{ $eventLocation->location_name }}</td>
                                            <td>{{ $eventLocation->address }}</td>
                                            <td>{{ $eventLocation->latitud }}</td>
                                            <td>{{ $eventLocation->longitud }}</td>
                                            <td>{{ $eventLocation->start_hour }}</td>
                                            <td>{{ $eventLocation->end_hour }}</td>
                                            <td>
                                                <form action="{{ route('event-locations.destroy', $eventLocation->id) }}" method="POST">
                                                    @can('event-locations.ver')
                                                    <a href="{{ route('event-locations.show', $eventLocation->id) }}" class="btn btn-outline-primary btn-sm">
                                                        <i class="fa fa-fw fa-eye"></i> {{ __('Ver') }}
                                                    </a>
                                                    @endcan
                                                    @can('event-locations.editar')
                                                    <a href="{{ route('event-locations.edit', $eventLocation->id) }}" class="btn btn-outline-success btn-sm">
                                                        <i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}
                                                    </a>
                                                    @endcan
                                                    @csrf
                                                    @method('DELETE')
                                                    @can('event-locations.eliminar')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                                        onclick="event.preventDefault(); confirm('¿Estás seguro de que deseas eliminar?') ? this.closest('form').submit() : false;">
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

                    {!! $eventLocations->withQueryString()->links() !!}
                </div>
            </div>
        </div>
    </div>
@stop
