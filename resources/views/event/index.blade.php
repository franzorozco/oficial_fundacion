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
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 w-100">

        <!-- TÍTULO -->
        <span id="card_title" class="h5 m-0">
            {{ __('Eventos') }}
        </span>

        <!-- BOTONES DE ACCIONES -->
        <div class="d-flex flex-wrap gap-2">

            @can('events.crear')
            <a href="{{ route('events.create') }}" class="btn btn-outline-success btn-sm">
                <i class="fa fa-plus"></i> Crear Nuevo
            </a>
            @endcan

            @can('events.verEliminados')
            <a href="{{ route('events.trashed') }}" class="btn btn-outline-dark btn-sm">
                <i class="fa fa-trash-restore"></i> Eliminados
            </a>
            @endcan

            <!-- BOTÓN FILTROS -->
            <button class="btn btn-outline-secondary btn-sm" type="button" data-toggle="collapse"
                data-target="#filtrosCollapse" aria-expanded="false" aria-controls="filtrosCollapse">
                <i class="fa fa-sliders-h"></i> Filtros
            </button>
        </div>
    </div>

    <!-- MENSAJE -->
    @if($message = Session::get('success'))
        <div class="alert alert-success mt-3">
            <p class="mb-0">{{ $message }}</p>
        </div>
    @endif

    <!-- FILTROS -->
    <div class="collapse mt-3" id="filtrosCollapse">

        <form method="GET" action="{{ route('events.index') }}" class="w-100">

            <div class="row g-3">

                <!-- BÚSQUEDA POR NOMBRE -->
                @can('events.buscar')
                <div class="col-md-4">
                    <div class="card card-body p-3 shadow-sm">
                        <h6 class="mb-3"><i class="fa fa-search"></i> Nombre del Evento</h6>
                        <input type="text" name="name" id="name" class="form-control"
                               value="{{ request('name') }}" placeholder="Buscar por nombre...">
                    </div>
                </div>

                <!-- FILTRO CAMPAÑA -->
                <div class="col-md-4">
                    <div class="card card-body p-3 shadow-sm">
                        <h6 class="mb-3"><i class="fa fa-bullhorn"></i> Campaña</h6>
                        <select name="campaign_id" id="campaign_id" class="form-control">
                            <option value="">Todas las campañas</option>
                            @foreach(App\Models\Campaign::all() as $campaign)
                                <option value="{{ $campaign->id }}"
                                    {{ request('campaign_id') == $campaign->id ? 'selected' : '' }}>
                                    {{ $campaign->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endcan

                <!-- BOTONES FILTRAR -->
                @can('events.filtrar')
                <div class="col-md-4">
                    <div class="card card-body p-3 shadow-sm h-100 d-flex align-items-start justify-content-center flex-column">
                        <h6 class="mb-3"><i class="fa fa-filter"></i> Acciones</h6>
                        <button type="submit" class="btn btn-primary mb-2">
                            <i class="fa fa-search"></i> Aplicar Filtros
                        </button>
                        <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">
                            <i class="fa fa-times"></i> Limpiar
                        </a>
                    </div>
                </div>
                @endcan

            </div>

        </form>
    </div>
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
    <div class="d-flex flex-wrap gap-1">

        @can('events.ver')
        <a class="btn btn-outline-primary btn-sm" href="{{ route('events.show', $event->id) }}">
            <i class="fa fa-eye"></i>
        </a>
        @endcan

        @can('events.editar')
        <a class="btn btn-outline-success btn-sm" href="{{ route('events.edit', $event->id) }}">
            <i class="fa fa-edit"></i>
        </a>
        @endcan

        @can('events.eliminar')
        <form action="{{ route('events.destroy', $event->id) }}" method="POST"
              onsubmit="return confirm('¿Estás seguro de eliminar?');">
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
                </div>
                {!! $events->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@stop
