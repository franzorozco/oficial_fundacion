@extends('adminlte::page')

@section('title', 'Donations')

@section('content_header')
    <h1>{{ __('Donaciones entrantes') }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                <span id="card_title" class="h5 m-0">
                    {{ __('Donaciones entrantes') }}
                </span>
                @can('donations-incoming.filtrar')
                <form action="{{ route('donations-incoming.index') }}" method="GET" class="d-flex flex-wrap gap-2" role="search">
                    <input type="text" name="search" class="form-control" placeholder="Buscar donaciones..." value="{{ request('search') }}">
                    <!-- Filtro por "Recibido por" -->
                     <h4>Recibido por:</h4>
                    <input type="text" name="received_by" class="form-control" placeholder="Recibido por..." value="{{ request('received_by') }}">

                    <!-- Filtros de tipo de donador -->
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="filter[]" value="externo" {{ in_array('externo', (array) request('filter')) ? 'checked' : '' }}>
                        <label class="form-check-label">Donador Externo</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="filter[]" value="registrado" {{ in_array('registrado', (array) request('filter')) ? 'checked' : '' }}>
                        <label class="form-check-label">Usuario Registrado</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="show_inactive" value="1" {{ request('show_inactive') ? 'checked' : '' }}>
                        <label class="form-check-label">Mostrar Inactivos</label>
                    </div>
                    <!-- Filtros por fechas -->
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" placeholder="Desde">
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" placeholder="Hasta">

                    <!-- Ordenamientos -->
                    <select name="order_by" class="form-select">
                        <option value="">-- Ordenar por --</option>
                        <option value="month_asc" {{ request('order_by') == 'month_asc' ? 'selected' : '' }}>Mes (Asc)</option>
                        <option value="month_desc" {{ request('order_by') == 'month_desc' ? 'selected' : '' }}>Mes (Desc)</option>
                        <option value="estado" {{ request('order_by') == 'estado' ? 'selected' : '' }}>Estado</option>
                        <option value="items_asc" {{ request('order_by') == 'items_asc' ? 'selected' : '' }}>Cantidad de Ítems (Menor a Mayor)</option>
                        <option value="items_desc" {{ request('order_by') == 'items_desc' ? 'selected' : '' }}>Cantidad de Ítems (Mayor a Menor)</option>
                        <option value="top_users_asc" {{ request('order_by') == 'top_users_asc' ? 'selected' : '' }}>Usuarios con Menos Donaciones</option>
                        <option value="top_users_desc" {{ request('order_by') == 'top_users_desc' ? 'selected' : '' }}>Usuarios con Más Donaciones</option>

                    </select>
                    

                    <button class="btn btn-outline-primary btn-sm" type="submit">
                        <i class="fa fa-search"></i> Buscar
                    </button>
                </form>
                @endcan

                <div class="d-flex flex-wrap gap-2">
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
                        @php
                            function estadoColorClassById($id) {
                                return match ($id) {
                                    1 => 'bg-warning text-dark',    // Reconsideración
                                    2 => 'bg-secondary text-white', // Pendiente de Revisión
                                    3 => 'bg-success text-white',   // Aceptada
                                    4 => 'bg-danger text-white',    // Rechazada
                                    5 => 'bg-info text-dark',       // En Tránsito
                                    6 => 'bg-primary text-white',   // Entregada
                                    7 => 'bg-dark text-white',      // Cancelada
                                    8 => 'bg-light text-dark border', // Archivada
                                    default => 'bg-light text-dark',
                                };
                            }
                        @endphp

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
                                        <!--
                                        <a class="btn btn-outline-success btn-sm" href="{{ route('donations-incoming.edit', $donation->id) }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        -->
                                        @can('donations-incoming.verpdf')
                                        <a class="btn btn-outline-info btn-sm" href="{{ route('donations-incoming.pdf', $donation->id) }}">
                                            <i class="fa fa-file-pdf"></i>
                                        </a>
                                        @endcan
                                        <!--
                                        <form action="{{ route('donations-incoming.destroy', $donation->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                        -->
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
    </div>

    <div class="mt-3">
        {!! $donations->withQueryString()->links() !!}
    </div>
@endsection
