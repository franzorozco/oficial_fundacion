@extends('adminlte::page')

@section('title', 'Eventos')

@section('content_header')
    <h1>{{ __('Eventos') }}</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <span id="card_title">
                                {{ __('Eventos') }}
                            </span>

                            <div class="float-right">
                                <a href="{{ route('events.create') }}" class="btn btn-outline-primary btn-sm" data-placement="left">
                                    {{ __('Crear Nuevo') }}
                                </a>
                                <a href="{{ route('events.trashed') }}" class="btn btn-outline-dark btn-sm" data-placement="left">
                                    <i class="fa fa-trash"></i> {{ __('Ver Eliminados') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <!-- Barra de búsqueda y filtros -->
                    <div class="card-body pt-0">
                        <form method="GET" action="{{ route('events.index') }}" class="mb-4">
                            <div class="row align-items-end">
                                <div class="col-md-4">
                                    <label for="name" class="form-label">{{ __('Nombre del Evento') }}</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                           value="{{ request('name') }}" placeholder="Buscar por nombre...">
                                </div>

                                <div class="col-md-4">
                                    <label for="campaign_id" class="form-label">{{ __('Campaña') }}</label>
                                    <select name="campaign_id" id="campaign_id" class="form-control">
                                        <option value="">{{ __('Todas las Campañas') }}</option>
                                        @foreach(App\Models\Campaign::all() as $campaign)
                                            <option value="{{ $campaign->id }}" {{ request('campaign_id') == $campaign->id ? 'selected' : '' }}>
                                                {{ $campaign->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="fa fa-search"></i> {{ __('Filtrar') }}
                                    </button>
                                    <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">
                                        <i class="fa fa-times"></i> {{ __('Limpiar') }}
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

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
                                        <th></th>
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
                                                <form action="{{ route('events.destroy', $event->id) }}" method="POST">
                                                    <a class="btn btn-outline-primary btn-sm" href="{{ route('events.show', $event->id) }}">
                                                        <i class="fa fa-fw fa-eye"></i> {{ __('Mostrar') }}
                                                    </a>
                                                    <a class="btn btn-outline-success btn-sm" href="{{ route('events.edit', $event->id) }}">
                                                        <i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}
                                                    </a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                                            onclick="event.preventDefault(); confirm('¿Estás seguro de eliminar?') ? this.closest('form').submit() : false;">
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
                </div>
                {!! $events->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@stop
