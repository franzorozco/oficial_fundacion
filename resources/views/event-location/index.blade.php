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
                                <a href="{{ route('event-locations.create') }}" class="btn btn-outline-primary btn-sm">
                                    {{ __('Crear Nueva') }}
                                </a>
                                <a href="{{ route('event-locations.trashed') }}" class="btn btn-outline-dark btn-sm">
                                    <i class="fa fa-trash-alt"></i> {{ __('Ver Eliminadas') }}
                                </a>
                            </div>
                        </div>

                        {{-- Buscador --}}
                        <form method="GET" action="{{ route('event-locations.index') }}" class="mt-3">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Buscar por nombre, dirección o ID del evento"
                                    value="{{ request()->query('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                                </div>
                            </div>
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
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($eventLocations as $eventLocation)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $eventLocation->event_id }}</td>
                                            <td>{{ $eventLocation->location_name }}</td>
                                            <td>{{ $eventLocation->address }}</td>
                                            <td>{{ $eventLocation->latitud }}</td>
                                            <td>{{ $eventLocation->longitud }}</td>
                                            <td>
                                                <form action="{{ route('event-locations.destroy', $eventLocation->id) }}" method="POST">
                                                    <a href="{{ route('event-locations.show', $eventLocation->id) }}" class="btn btn-outline-primary btn-sm">
                                                        <i class="fa fa-fw fa-eye"></i> {{ __('Ver') }}
                                                    </a>
                                                    <a href="{{ route('event-locations.edit', $eventLocation->id) }}" class="btn btn-outline-success btn-sm">
                                                        <i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}
                                                    </a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                                        onclick="event.preventDefault(); confirm('¿Estás seguro de que deseas eliminar?') ? this.closest('form').submit() : false;">
                                                        <i class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}
                                                    </button>
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
