@extends('adminlte::page')

@section('title', 'Eventos Eliminados')

@section('content_header')
    <h1>{{ __('Eventos Eliminados') }}</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span id="card_title">{{ __('Eventos Eliminados') }}</span>

                        <div class="float-right">
                            <a href="{{ route('events.index') }}" class="btn btn-outline-primary btn-sm" data-placement="left">
                                {{ __('Volver a Eventos Activos') }}
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
                            <thead class="thead">
                                <tr>
                                    <th>No</th>
                                    <th>Id de Campaña</th>
                                    <th>Id del Creador</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Fecha del Evento</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($events as $event)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $event->campaign->name ?? '—' }}</td>
                                        <td>{{ $event->user->name ?? '—' }}</td>
                                        <td>{{ $event->name }}</td>
                                        <td>{{ $event->description }}</td>
                                        <td>{{ $event->event_date }}</td>
                                        <td>
                                            <form action="{{ route('events.restore', $event->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success btn-sm">
                                                    <i class="fa fa-fw fa-undo"></i> {{ __('Restaurar') }}
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('events.forceDelete', $event->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                                        onclick="event.preventDefault(); confirm('¿Estás seguro de eliminar permanentemente?') ? this.closest('form').submit() : false;">
                                                    <i class="fa fa-fw fa-trash"></i> {{ __('Eliminar Permanentemente') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {!! $events->withQueryString()->links() !!}
        </div>
    </div>
</div>
@stop
