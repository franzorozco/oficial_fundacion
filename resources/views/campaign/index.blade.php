@extends('adminlte::page')

@section('title', 'Campañas')

@section('content_header')
    <h1>{{ __('Campañas') }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 w-100">

        <!-- TÍTULO -->
        <span id="card_title" class="h5 m-0">
            {{ __('Campañas') }}
        </span>

        <!-- BOTONES DE ACCIONES -->
        <div class="d-flex flex-wrap gap-2">
            @can('campaigns.crear')
            <a href="{{ route('campaigns.create') }}" class="btn btn-outline-success btn-sm">
                <i class="fa fa-plus"></i> Crear Nueva
            </a>
            @endcan

            @php
                $pdfParams = request()->only(['search', 'filters', 'min_participantes', 'max_participantes']);
            @endphp

            @can('campaigns.regenerarPDF')
            <a href="{{ route('campaigns.pdf.all', $pdfParams) }}" class="btn btn-outline-info btn-sm">
                <i class="fa fa-file-pdf"></i> PDF
            </a>
            @endcan

            @can('campaigns.verEliminadas')
            <a href="{{ route('campaigns.trashed') }}" class="btn btn-outline-dark btn-sm">
                <i class="fa fa-trash-restore"></i> Eliminadas
            </a>
            @endcan

            <!-- BOTÓN MOSTRAR/OCULTAR FILTROS -->
            <button class="btn btn-outline-secondary btn-sm" type="button" data-toggle="collapse"
                data-target="#filtrosCollapse" aria-expanded="false" aria-controls="filtrosCollapse">
                <i class="fa fa-sliders-h"></i> Filtros
            </button>
        </div>
    </div>

    <!-- BLOQUE DE FILTROS -->
    <div class="collapse mt-3" id="filtrosCollapse">

        <form action="{{ route('campaigns.index') }}" method="GET" class="w-100" role="search">

            <div class="row g-3">

                <!-- BÚSQUEDA -->
                <div class="col-12">
                    <div class="card card-body p-3 shadow-sm">
                        <h6 class="mb-3"><i class="fa fa-search"></i> Búsqueda</h6>

                        @can('campaigns.buscar')
                        <input type="text" name="search" id="search" class="form-control"
                            placeholder="Buscar campañas..." value="{{ request('search') }}">
                        @endcan
                    </div>
                </div>

                <!-- ESTADOS -->
                <div class="col-md-3 col-sm-6">
                    <div class="card card-body p-3 shadow-sm">
                        <h6 class="mb-2"><i class="fa fa-flag"></i> Estado</h6>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="filters[]" value="activa"
                                id="filtro_activa"
                                {{ in_array('activa', request('filters', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="filtro_activa">Activas</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="filters[]" value="inactiva"
                                id="filtro_inactiva"
                                {{ in_array('inactiva', request('filters', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="filtro_inactiva">Inactivas</label>
                        </div>
                    </div>
                </div>

                <!-- EVENTOS -->
                <div class="col-md-3 col-sm-6">
                    <div class="card card-body p-3 shadow-sm">
                        <h6 class="mb-2"><i class="fa fa-calendar"></i> Eventos asociados</h6>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="filters[]" value="con_eventos"
                                id="filtro_con_eventos"
                                {{ in_array('con_eventos', request('filters', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="filtro_con_eventos">Con eventos</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="filters[]" value="sin_eventos"
                                id="filtro_sin_eventos"
                                {{ in_array('sin_eventos', request('filters', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="filtro_sin_eventos">Sin eventos</label>
                        </div>
                    </div>
                </div>

                <!-- PARTICIPANTES -->
                <div class="col-md-3 col-sm-6">
                    <div class="card card-body p-3 shadow-sm">
                        <h6 class="mb-2"><i class="fa fa-users"></i> Participantes</h6>

                        <label class="form-label">Mínimo</label>
                        <input type="number" name="min_participantes" class="form-control mb-2" min="0"
                            value="{{ request('min_participantes') }}">

                        <label class="form-label">Máximo</label>
                        <input type="number" name="max_participantes" class="form-control" min="0"
                            value="{{ request('max_participantes') }}">
                    </div>
                </div>

                <!-- OTROS CRITERIOS -->
                <div class="col-md-3 col-sm-6">
                    <div class="card card-body p-3 shadow-sm">
                        <h6 class="mb-2"><i class="fa fa-list"></i> Otros criterios</h6>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="filters[]" value="mayor_eventos"
                                id="filtro_mayor_eventos"
                                {{ in_array('mayor_eventos', request('filters', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="filtro_mayor_eventos">Más eventos</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="filters[]" value="multi_ubicacion"
                                id="filtro_multi_ubicacion"
                                {{ in_array('multi_ubicacion', request('filters', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="filtro_multi_ubicacion">Multisedes</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="filters[]" value="eventos_largos"
                                id="filtro_eventos_largos"
                                {{ in_array('eventos_largos', request('filters', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="filtro_eventos_largos">Eventos largos</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BOTÓN APLICAR -->
            <div class="mt-4 text-end">
                <button class="btn btn-primary" type="submit">
                    <i class="fa fa-filter"></i> Aplicar filtros
                </button>
            </div>

        </form>
    </div>
</div>

    </form>
</div>
            </div>

        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success m-4">
                <p class="mb-0">{{ $message }}</p>
            </div>
        @endif

        <div class="card-body bg-white">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nombre de Creador</th>
                            <th>Nombre de Campaña</th>
                            <th>Eventos registrados</th>
                            <th>Numero de Ubicaciones</th>
                            <th>Ubicaciones (Nombre)</th>
                            <th>Total Participantes</th>
                            <th>Descripción</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Vista en Web</th>
                            <th>Obsercaciones</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($campaigns as $campaign)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $campaign->user->name ?? 'N/A' }}</td>
                                <td>{{ $campaign->name }}</td>
                                <td>{{ $campaign->events_count }}</td>
                                <td>{{ $campaign->total_ubicaciones }}</td>
                                <td>
                                    @php
                                        $ubicaciones = [];
                                        foreach ($campaign->events as $event) {
                                            foreach ($event->locations as $location) {
                                                $ubicaciones[] = $location->location_name;
                                            }
                                        }
                                    @endphp
                                    {{ implode(', ', array_unique($ubicaciones)) ?: 'N/A' }}
                                </td>

                                <td>{{ $campaign->total_participantes }}</td>
                                <td>{{ $campaign->description }}</td>
                                <td>{{ $campaign->start_date }}</td>
                                <td>{{ $campaign->end_date }}</td>
                                <td>{{ $campaign->show_cam ? 'Sí' : 'No' }}</td>
                                <td>{{ $campaign->observations ?? 'N/A' }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @can('campaigns.ver')
                                        <a class="btn btn-outline-primary btn-sm" href="{{ route('campaigns.show', $campaign->id) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @endcan
                                        @can('campaigns.editar')
                                        <a class="btn btn-outline-success btn-sm" href="{{ route('campaigns.edit', $campaign->id) }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @endcan
                                        @can('campaigns.eliminar')
                                        <form action="{{ route('campaigns.destroy', $campaign->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar?');">
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

    <div class="mt-3">
        {!! $campaigns->withQueryString()->links() !!}
    </div>
@endsection
