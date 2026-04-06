@extends('adminlte::page')

@section('title', 'Donations')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="mb-0">Donaciones</h1>
</div>
@endsection

@section('content')

<form method="GET" action="{{ route('donations.index') }}">

<div class="card shadow-sm">

    <!-- HEADER -->
    <div class="card-header bg-white border-bottom">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

            <h5 class="mb-0 fw-bold">Listado de Donaciones</h5>

            <div class="d-flex flex-wrap gap-2">

                @can('donations.crear')
                <a href="{{ route('donations.create') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-plus"></i> Nueva
                </a>
                @endcan

                @php
                    $pdfParams = request()->all();
                @endphp

                @can('donations.verpdfgeneral')
                <a href="{{ route('donations.pdf.all', $pdfParams) }}" class="btn btn-outline-danger btn-sm" target="_blank">
                    PDF
                </a>
                @endcan

                @can('donations.vereliminados')
                <a href="{{ route('donations.trashed') }}" class="btn btn-outline-dark btn-sm">
                    Eliminadas
                </a>
                @endcan

                @can('donations.filtrar')
                <button type="button"
                    class="btn btn-outline-secondary btn-sm"
                    data-toggle="collapse"
                    data-target="#filtrosCollapse">
                    Filtros
                </button>
                @endcan

            </div>
        </div>
    </div>

    <!-- BUSCADOR -->
    <div class="p-3 border-bottom bg-light">
        <div class="row align-items-center">
            <div class="col-md-10">
                <input type="text"
                    name="search"
                    class="form-control"
                    placeholder="Buscar por referencia, nombre, usuario..."
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
    @can('donations.filtrar')
    <div class="collapse border-bottom" id="filtrosCollapse">
        <div class="card-body bg-white">
            <div class="row g-3">

                <!-- RECIBIDO POR -->
                <div class="col-md-4">
                    <div class="p-3 border rounded h-100">
                        <label class="fw-semibold mb-2">Recibido por</label>
                        <input type="text" name="received_by" class="form-control"
                            value="{{ request('received_by') }}">
                    </div>
                </div>

                <!-- TIPO DONADOR -->
                <div class="col-md-4">
                    <div class="p-3 border rounded h-100">
                        <label class="fw-semibold mb-2">Tipo de Donador</label>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="filter[]" value="externo"
                                {{ in_array('externo', (array) request('filter')) ? 'checked' : '' }}>
                            <label class="form-check-label">Externo</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="filter[]" value="registrado"
                                {{ in_array('registrado', (array) request('filter')) ? 'checked' : '' }}>
                            <label class="form-check-label">Registrado</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="show_inactive" value="1"
                                {{ request('show_inactive') ? 'checked' : '' }}>
                            <label class="form-check-label">Inactivos</label>
                        </div>
                    </div>
                </div>

                <!-- FECHAS -->
                <div class="col-md-4">
                    <div class="p-3 border rounded h-100">
                        <label class="fw-semibold mb-2">Rango de fechas</label>
                        <input type="date" name="start_date" class="form-control mb-2" value="{{ request('start_date') }}">
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                </div>

                <!-- ESTADO -->
                <div class="col-md-4">
                    <div class="p-3 border rounded h-100">
                        <label class="fw-semibold mb-2">Estado</label>
                        <select name="status_filter" class="form-control">
                            <option value="">-- Seleccionar --</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}" {{ request('status_filter') == $status->id ? 'selected' : '' }}>
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- ORDEN -->
                <div class="col-md-4">
                    <div class="p-3 border rounded h-100">
                        <label class="fw-semibold mb-2">Ordenar</label>
                        <select name="order_by" class="form-control">
                            <option value="">-- Seleccionar --</option>
                            <option value="month_asc" {{ request('order_by') == 'month_asc' ? 'selected' : '' }}>Mes ↑</option>
                            <option value="month_desc" {{ request('order_by') == 'month_desc' ? 'selected' : '' }}>Mes ↓</option>
                            <option value="items_desc" {{ request('order_by') == 'items_desc' ? 'selected' : '' }}>Más ítems</option>
                        </select>
                    </div>
                </div>

                <!-- BOTONES -->
                <div class="col-md-4 d-flex flex-column justify-content-center">
                    <button class="btn btn-primary mb-2">
                        Aplicar filtros
                    </button>
                    <a href="{{ route('donations.index') }}" class="btn btn-outline-secondary">
                        Limpiar
                    </a>
                </div>

            </div>
        </div>
    </div>
    @endcan

    <!-- ALERT -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success m-3 mb-0">
            {{ $message }}
        </div>
    @endif

    <!-- TABLA -->
    @php
    function estadoColorClassById($id) {
        return match ($id) {
            1 => 'bg-warning text-dark',
            2 => 'bg-secondary text-white',
            3 => 'bg-success text-white',
            4 => 'bg-danger text-white',
            default => 'bg-light text-dark',
        };
    }
    @endphp

    <div class="card-body p-0">
        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Referencia</th>
                        <th>Donación</th>
                        <th>Donante</th>
                        <th>Usuario</th>
                        <th>Recibido</th>
                        <th>Estado</th>
                        <th>Campaña</th>
                        <th>Fecha</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($donations as $donation)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $donation->referencia ?? '-' }}</td>

                        <td class="fw-semibold">
                            {{ $donation->name_donation ?? '-' }}
                        </td>

                        <td>{{ $donation->externalDonor->names ?? '-' }}</td>
                        <td>{{ $donation->user->name ?? '-' }}</td>
                        <td>{{ $donation->receivedBy->name ?? '-' }}</td>

                        <td>
                            <span class="badge {{ estadoColorClassById($donation->status->id ?? 0) }}">
                                {{ $donation->status->name ?? '-' }}
                            </span>
                        </td>

                        <td>{{ $donation->campaign->name ?? '-' }}</td>
                        <td>{{ $donation->donation_date }}</td>

                        <td class="text-center">
                            <div class="btn-group">

                                @can('donations.ver')
                                <a href="{{ route('donations.show', $donation->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fa fa-eye"></i>
                                </a>
                                @endcan

                                @can('donations.editar')
                                <a href="{{ route('donations.edit', $donation->id) }}" class="btn btn-outline-success btn-sm">
                                    <i class="fa fa-edit"></i>
                                </a>
                                @endcan

                                @can('donations.verpdf')
                                <a href="{{ route('donations.pdf', $donation->id) }}" class="btn btn-outline-danger btn-sm" target="_blank">
                                    <i class="fa fa-file-pdf"></i>
                                </a>
                                @endcan

                                @can('donations.eliminar')
                                <form action="{{ route('donations.destroy', $donation->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm('¿Eliminar donación?')">
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
    {!! $donations->withQueryString()->links() !!}
</div>

@endsection