@extends('adminlte::page')

@section('title', 'Campañas')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="mb-0">Campañas</h1>
    </div>
@endsection

@section('content')

<form method="GET" action="{{ route('campaigns.index') }}">

<div class="card shadow-sm">

    <!-- HEADER -->
    <div class="card-header bg-white border-bottom">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

            <h5 class="mb-0 fw-bold">Listado de campañas</h5>

            <div class="d-flex flex-wrap gap-2">

                @can('campaigns.crear')
                <a href="{{ route('campaigns.create') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-plus"></i> Nueva
                </a>
                @endcan

                @php
                    $pdfParams = request()->all();
                @endphp

                @can('campaigns.regenerarPDF')
                <a href="{{ route('campaigns.pdf.all', $pdfParams) }}" class="btn btn-outline-danger btn-sm" target="_blank">
                    PDF
                </a>
                @endcan

                @can('campaigns.verEliminadas')
                <a href="{{ route('campaigns.trashed') }}" class="btn btn-outline-dark btn-sm">
                    Eliminadas
                </a>
                @endcan

                <button type="button"
                    class="btn btn-outline-secondary btn-sm"
                    data-toggle="collapse"
                    data-target="#filtrosCollapse">
                    Filtros
                </button>

            </div>
        </div>

    </div>

    <!-- 🔎 BUSCADOR (FUERA DE FILTROS) -->
    <div class="p-3 border-bottom bg-light">

        <div class="row align-items-center">

            <div class="col-md-10">
                <input type="text"
                    name="search"
                    class="form-control"
                    placeholder="Buscar campañas por nombre, creador..."
                    value="{{ request('search') }}">
            </div>

            <div class="col-md-2 mt-2 mt-md-0">
                <button class="btn btn-primary w-100">
                    Buscar
                </button>
            </div>

        </div>
    </div>

    <!-- FILTROS -->
    <div class="collapse border-bottom" id="filtrosCollapse">

        <div class="card-body bg-white">

            <div class="row g-3">

                <!-- ESTADO -->
                <div class="col-md-4">
                    <div class="p-3 border rounded h-100">
                        <label class="fw-semibold mb-2">Estado</label>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="filters[]" value="activa"
                                {{ in_array('activa', request('filters', [])) ? 'checked' : '' }}>
                            <label class="form-check-label">Activas</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="filters[]" value="inactiva"
                                {{ in_array('inactiva', request('filters', [])) ? 'checked' : '' }}>
                            <label class="form-check-label">Inactivas</label>
                        </div>
                    </div>
                </div>

                <!-- EVENTOS -->
                <div class="col-md-4">
                    <div class="p-3 border rounded h-100">
                        <label class="fw-semibold mb-2">Eventos</label>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="filters[]" value="con_eventos"
                                {{ in_array('con_eventos', request('filters', [])) ? 'checked' : '' }}>
                            <label class="form-check-label">Con eventos</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="filters[]" value="sin_eventos"
                                {{ in_array('sin_eventos', request('filters', [])) ? 'checked' : '' }}>
                            <label class="form-check-label">Sin eventos</label>
                        </div>
                    </div>
                </div>

                <!-- PARTICIPANTES -->
                <div class="col-md-4">
                    <div class="p-3 border rounded h-100">
                        <label class="fw-semibold mb-2">Participantes</label>

                        <input type="number"
                            name="min_participantes"
                            class="form-control mb-2"
                            placeholder="Mínimo"
                            value="{{ request('min_participantes') }}">

                        <input type="number"
                            name="max_participantes"
                            class="form-control"
                            placeholder="Máximo"
                            value="{{ request('max_participantes') }}">
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- ALERT -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success m-3 mb-0">
            {{ $message }}
        </div>
    @endif

    <!-- TABLA -->
    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Creador</th>
                        <th>Campaña</th>
                        <th>Eventos</th>
                        <th>Ubicaciones</th>
                        <th>Participantes</th>
                        <th>Fechas</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($campaigns as $campaign)
                    <tr>

                        <td>{{ ++$i }}</td>
                        <td>{{ $campaign->user->name ?? 'N/A' }}</td>

                        <td class="fw-semibold">
                            {{ $campaign->name }}
                        </td>

                        <td>{{ $campaign->events_count }}</td>
                        <td>{{ $campaign->total_ubicaciones }}</td>
                        <td>{{ $campaign->total_participantes }}</td>

                        <td>
                            <small>
                                {{ $campaign->start_date }} <br>
                                {{ $campaign->end_date }}
                            </small>
                        </td>

                        <td>
                            <span class="badge {{ $campaign->show_cam ? 'bg-success' : 'bg-secondary' }}">
                                {{ $campaign->show_cam ? 'Activa' : 'Oculta' }}
                            </span>
                        </td>

                        <td class="text-center">
                            <div class="btn-group">

                                @can('campaigns.ver')
                                <a href="{{ route('campaigns.show', $campaign->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fa fa-eye"></i>
                                </a>
                                @endcan

                                @can('campaigns.editar')
                                <a href="{{ route('campaigns.edit', $campaign->id) }}" class="btn btn-outline-success btn-sm">
                                    <i class="fa fa-edit"></i>
                                </a>
                                @endcan

                                @can('campaigns.eliminar')
                                <form action="{{ route('campaigns.destroy', $campaign->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm('¿Eliminar campaña?')">
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

</form>

<!-- PAGINACIÓN -->
<div class="mt-3">
    {!! $campaigns->withQueryString()->links() !!}
</div>

    @endsection