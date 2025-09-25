@extends('adminlte::page')

@section('title', 'Campañas')

@section('content_header')
    <h1>{{ __('Campañas') }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                <!-- Título -->
                <span id="card_title" class="h5 m-0">
                    {{ __('Campañas') }}
                </span>

                <!-- Formulario de búsqueda y filtros -->
                <form action="{{ route('campaigns.index') }}" method="GET" class="d-flex flex-column flex-md-row gap-3 flex-wrap w-100" role="search">
                    <!-- Búsqueda -->
                    @can('campaigns.buscar')
                    <div class="form-group">
                        <label for="search">Buscar campañas:</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="Buscar..." value="{{ request('search') }}">
                    </div>
                    @endcan
                    <!-- Filtros agrupados en columnas -->
                    <div class="d-flex flex-wrap gap-3">
                        <!-- Estado -->
                        <div class="form-group">
                            <label><strong>Estado de la campaña</strong></label><br>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="filters[]" value="activa" id="filtro_activa" {{ in_array('activa', request('filters', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="filtro_activa">Campañas activas</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="filters[]" value="inactiva" id="filtro_inactiva" {{ in_array('inactiva', request('filters', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="filtro_inactiva">Campañas inactivas</label>
                            </div>
                        </div>
                        <!-- Eventos asociados -->
                        <div class="form-group">
                            <label><strong>Eventos asociados</strong></label><br>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="filters[]" value="con_eventos" id="filtro_con_eventos" {{ in_array('con_eventos', request('filters', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="filtro_con_eventos">Con eventos</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="filters[]" value="sin_eventos" id="filtro_sin_eventos" {{ in_array('sin_eventos', request('filters', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="filtro_sin_eventos">Sin eventos</label>
                            </div>
                        </div>
                       <!-- Participación -->
                        <div class="form-group">
                            <label><strong>Rango de participantes</strong></label><br>
                            <div class="d-flex gap-2">
                                <div>
                                    <label for="min_participantes" class="form-label">Mínimo</label>
                                    <input type="number" name="min_participantes" id="min_participantes" class="form-control" min="0" value="{{ request('min_participantes') }}">
                                </div>
                                <div>
                                    <label for="max_participantes" class="form-label">Máximo</label>
                                    <input type="number" name="max_participantes" id="max_participantes" class="form-control" min="0" value="{{ request('max_participantes') }}">
                                </div>
                            </div>
                        </div>
                        <!-- Otros -->
                        <div class="form-group">
                            <label><strong>Otros criterios</strong></label><br>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="filters[]" value="mayor_eventos" id="filtro_mayor_eventos" {{ in_array('mayor_eventos', request('filters', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="filtro_mayor_eventos">Con más eventos</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="filters[]" value="multi_ubicacion" id="filtro_multi_ubicacion" {{ in_array('multi_ubicacion', request('filters', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="filtro_multi_ubicacion">Eventos multisedes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="filters[]" value="eventos_largos" id="filtro_eventos_largos" {{ in_array('eventos_largos', request('filters', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="filtro_eventos_largos">Eventos más largos</label>
                            </div>
                        </div>
                    </div>
                    @can('campaigns.filtrar')
                    <!-- Botón aplicar -->
                    <div class="align-self-end">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="fa fa-filter"></i> Aplicar filtros
                        </button>
                    </div>
                    @endcan
                </form>

                <!-- Botones de acciones -->
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
                        <i class="fa fa-file-pdf"></i> Descargar PDF
                    </a>
                    @endcan
                    @can('campaigns.verEliminadas')
                    <a href="{{ route('campaigns.trashed') }}" class="btn btn-outline-dark btn-sm">
                        <i class="fa fa-trash-restore"></i> Ver Eliminadas
                    </a>
                    @endcan
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
