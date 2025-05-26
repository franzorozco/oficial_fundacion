@extends('adminlte::page')

@section('title', 'Ubicaciones Eliminadas')

@section('content_header')
    <h1>{{ __('Ubicaciones de Eventos Eliminadas') }}</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">{{ __('Ubicaciones Eliminadas') }}</span>
                            <div class="float-right">
                                <a href="{{ route('event-locations.index') }}" class="btn btn-outline-secondary btn-sm">
                                    {{ __('Volver a la Lista') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
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
                                                <form action="{{ route('event-locations.forceDelete', $eventLocation->id) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                                        onclick="return confirm('¿Estás seguro de que deseas eliminar permanentemente?')">
                                                        <i class="fa fa-fw fa-trash"></i> Eliminar Definitivamente
                                                    </button>
                                                </form>

                                                <form action="{{ route('event-locations.restore', $eventLocation->id) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-success btn-sm">
                                                        <i class="fa fa-fw fa-undo"></i> Restaurar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {!! $eventLocations->links() !!}
                </div>
            </div>
        </div>
    </div>
@stop
