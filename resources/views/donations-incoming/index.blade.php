@extends('adminlte::page')

@section('title', 'Donations Incoming')

@section('content_header')
    <h1>{{ __('Donaciones entrantes') }}</h1>
@endsection

@section('content')
<div class="card">

    <!-- HEADER: TÍTULO + BOTONES -->
    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">

        <!-- Título -->
        <span id="card_title" class="h5 m-0">
            {{ __('Donaciones entrantes') }}
        </span>

        <!-- Botones -->
        <div class="d-flex flex-wrap gap-2">
            @can('donations-incoming.filtrar')
            <button class="btn btn-outline-secondary btn-sm" type="button" data-toggle="collapse"
                    data-target="#filtrosCollapse" aria-expanded="false" aria-controls="filtrosCollapse">
                <i class="fa fa-sliders-h"></i> Filtros
            </button>
            @endcan

            @can('donations-incoming.verpdfgeneral')
            <a href="{{ route('donations-incoming.pdf.all') }}" class="btn btn-outline-info btn-sm">
                <i class="fa fa-file-pdf"></i> Descargar PDF de Todas
            </a>
            @endcan

            @can('donations-incoming.historial')
            <a href="{{ route('donations-incoming.history') }}" class="btn btn-outline-warning btn-sm">
                <i class="fa fa-list-check"></i> Mis decisiones
            </a>
            @endcan
        </div>
    </div>

    <!-- Mensaje de éxito -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success m-3">
            <p class="mb-0">{{ $message }}</p>
        </div>
    @endif

    <!-- FILTROS -->
    @can('donations-incoming.filtrar')
    <div class="collapse mt-3" id="filtrosCollapse">
        <form action="{{ route('donations-incoming.index') }}" method="GET" class="w-100">
            <div class="row g-3">

                <!-- Búsqueda general -->
                <div class="col-md-3">
                    <div class="card card-body p-3 shadow-sm">
                        <h6 class="mb-2"><i class="fa fa-search"></i> Buscar</h6>
                        <input type="text" name="search" class="form-control" placeholder="Buscar donaciones..."
                               value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Recibido por -->
                <div class="col-md-3">
                    <div class="card card-body p-3 shadow-sm">
                        <h6 class="mb-2"><i class="fa fa-user"></i> Recibido por</h6>
                        <input type="text" name="received_by" class="form-control"
                               placeholder="Recibido por..."
                               value="{{ request('received_by') }}">
                    </div>
                </div>

                <!-- Tipo de donador -->
                <div class="col-md-3">
                    <div class="card card-body p-3 shadow-sm">
                        <h6 class="mb-2"><i class="fa fa-users"></i> Tipo de donador</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="filter[]" value="externo"
                                   {{ in_array('externo', (array) request('filter')) ? 'checked' : '' }}>
                            <label class="form-check-label">Donador Externo</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="filter[]" value="registrado"
                                   {{ in_array('registrado', (array) request('filter')) ? 'checked' : '' }}>
                            <label class="form-check-label">Usuario Registrado</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="show_inactive" value="1"
                                   {{ request('show_inactive') ? 'checked' : '' }}>
                            <label class="form-check-label">Mostrar Inactivos</label>
                        </div>
                    </div>
                </div>

                <!-- Fechas -->
                <div class="col-md-3">
                    <div class="card card-body p-3 shadow-sm">
                        <h6 class="mb-2"><i class="fa fa-calendar"></i> Fechas</h6>
                        <input type="date" name="start_date" class="form-control mb-2" value="{{ request('start_date') }}">
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                </div>

                <!-- Ordenamiento -->
                <div class="col-md-3">
                    <div class="card card-body p-3 shadow-sm">
                        <h6 class="mb-2"><i class="fa fa-sort"></i> Ordenar por</h6>
                        <select name="order_by" class="form-control">
                            <option value="">-- Ordenar por --</option>
                            <option value="month_asc" {{ request('order_by') == 'month_asc' ? 'selected' : '' }}>Mes (Asc)</option>
                            <option value="month_desc" {{ request('order_by') == 'month_desc' ? 'selected' : '' }}>Mes (Desc)</option>
                            <option value="estado" {{ request('order_by') == 'estado' ? 'selected' : '' }}>Estado</option>
                            <option value="items_asc" {{ request('order_by') == 'items_asc' ? 'selected' : '' }}>Cantidad de Ítems (Menor a Mayor)</option>
                            <option value="items_desc" {{ request('order_by') == 'items_desc' ? 'selected' : '' }}>Cantidad de Ítems (Mayor a Menor)</option>
                            <option value="top_users_asc" {{ request('order_by') == 'top_users_asc' ? 'selected' : '' }}>Usuarios con Menos Donaciones</option>
                            <option value="top_users_desc" {{ request('order_by') == 'top_users_desc' ? 'selected' : '' }}>Usuarios con Más Donaciones</option>
                        </select>
                    </div>
                </div>

                <!-- Botón aplicar / limpiar -->
                <div class="col-md-3 d-flex flex-column justify-content-center align-items-center">
                    <button type="submit" class="btn btn-primary mb-2"><i class="fa fa-search"></i> Aplicar</button>
                    <a href="{{ route('donations-incoming.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                </div>

            </div>
        </form>
    </div>
    @endcan

    <!-- TABLA -->
    @php
    function estadoColorClassById($id) {
        return match ($id) {
            1 => 'bg-warning text-dark',
            2 => 'bg-secondary text-white',
            3 => 'bg-success text-white',
            4 => 'bg-danger text-white',
            5 => 'bg-info text-dark',
            6 => 'bg-primary text-white',
            7 => 'bg-dark text-white',
            8 => 'bg-light text-dark border',
            default => 'bg-light text-dark',
        };
    }
    @endphp

    <div class="card-body bg-white mt-3">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Donante externo</th>
                        <th>Usuario registrado</th>
                        <th>Recibido por</th>
                        <th>Estado</th>
                        <th>Campaña</th>
                        <th>Fecha</th>
                        <th>Notas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($donations as $donation)
                        <tr>
                            <td>{{ ++$i }}</td>
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
                            <td>{{ $donation->notes }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @can('donations-incoming.ver')
                                    <a class="btn btn-outline-primary btn-sm" href="{{ route('donations-incoming.show', $donation->id) }}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    @endcan
                                    @can('donations-incoming.verpdf')
                                    <a class="btn btn-outline-info btn-sm" href="{{ route('donations-incoming.pdf', $donation->id) }}">
                                        <i class="fa fa-file-pdf"></i>
                                    </a>
                                    @endcan
                                    @can('donations-incoming.aceptar')
                                    <form action="{{ route('donations-incoming.accept', $donation->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success btn-sm" onclick="return confirm('¿Aceptar esta donación?')">
                                            <i class="fa fa-check"></i> Aceptar
                                        </button>
                                    </form>
                                    @endcan
                                    @can('donations-incoming.rechazar')
                                    <form action="{{ route('donations-incoming.reject', $donation->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Rechazar esta donación?')">
                                            <i class="fa fa-times"></i> Rechazar
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

    <!-- PAGINACIÓN -->
    <div class="mt-3">
        {!! $donations->withQueryString()->links() !!}
    </div>

</div>
@endsection
