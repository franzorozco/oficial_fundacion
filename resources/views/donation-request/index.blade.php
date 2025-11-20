@extends('adminlte::page')

@section('title', __('Solicitudes de Donación'))

@section('content_header')
    <h1>{{ __('Solicitudes de Donación') }}</h1>
@endsection

@section('content')
@if ($message = Session::get('success'))
    <div class="alert alert-success m-3">
        <p class="mb-0">{{ $message }}</p>
    </div>
@endif

<div class="card">

    <!-- HEADER: TÍTULO + BOTONES -->
    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">

        <span id="card_title" class="h5 m-0">{{ __('Solicitudes de Donación') }}</span>

        <div class="d-flex flex-wrap gap-2">
            @can('donation-requests.crear')
            <a href="{{ route('donation-requests.create') }}" class="btn btn-outline-success btn-sm">
                <i class="fas fa-plus"></i> Crear Nuevo
            </a>
            @endcan

            @can('donation-requests.exportar_pdf')
            <a href="{{ route('donation-requests.pdf', request()->only('search')) }}" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-file-pdf"></i> Exportar a PDF
            </a>
            @endcan

            @can('donation-requests.filtrar')
            <button class="btn btn-outline-secondary btn-sm" type="button" data-toggle="collapse" data-target="#filtrosCollapse" aria-expanded="false" aria-controls="filtrosCollapse">
                <i class="fa fa-sliders-h"></i> Filtros
            </button>
            @endcan

            @can('donation-requests.ver_eliminados')
            <a href="{{ route('donation-requests.trashed') }}" class="btn btn-outline-dark btn-sm">
                <i class="fa fa-trash"></i> Ver Eliminados
            </a>
            @endcan
        </div>
    </div>

    <!-- FILTROS COLAPSABLES -->
    @can('donation-requests.filtrar')
    <div class="collapse mt-3" id="filtrosCollapse">
        <form method="GET" action="{{ route('donation-requests.index') }}">
            <div class="row g-3">

                <!-- Búsqueda general -->
                <div class="col-md-3">
                    <label class="form-label">Buscar</label>
                    <input type="text" name="search" class="form-control" placeholder="Nombre, correo..." value="{{ request('search') }}">
                </div>

                <!-- Estado -->
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select name="state" class="form-select">
                        <option value="">-- Estado --</option>
                        <option value="pendiente" {{ request('state') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="en revision" {{ request('state') == 'en revision' ? 'selected' : '' }}>En Revisión</option>
                        <option value="aceptado" {{ request('state') == 'aceptado' ? 'selected' : '' }}>Aceptado</option>
                        <option value="rechazado" {{ request('state') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                        <option value="finalizado" {{ request('state') == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                    </select>
                </div>

                <!-- Rango de fechas -->
                <div class="col-md-3">
                    <label class="form-label">Desde</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Hasta</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>

                <!-- Usuario encargado -->
                <div class="col-md-3">
                    <label class="form-label">Usuario Encargado</label>
                    <select name="user_in_charge_id" class="form-select">
                        <option value="">-- Encargado --</option>
                        @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_in_charge_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Botones filtrar/limpiar -->
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                    <a href="{{ route('donation-requests.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-eraser"></i> Limpiar
                    </a>
                </div>

            </div>
        </form>
    </div>
    @endcan

    <!-- TABLA DE SOLICITUDES -->
    <div class="card-body bg-white mt-3">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Referencia</th>
                        <th>Usuario Solicitante</th>
                        <th>Usuario Encargado</th>
                        <th>Donación</th>
                        <th>Fecha Solicitud</th>
                        <th>Fecha Creación</th>
                        <th>Notas</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($donationRequests as $donationRequest)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $donationRequest->referencia }}</td>
                        <td>{{ $donationRequest->applicantUser->name ?? 'N/A' }}</td>
                        <td>{{ $donationRequest->userInCharge->name ?? 'N/A' }}</td>
                        <td>{{ $donationRequest->donation->referencia ?? 'N/A' }} - {{ $donationRequest->donation->name_donation ?? 'N/A' }}</td>
                        <td>{{ $donationRequest->request_date }}</td>
                        <td>{{ $donationRequest->created_at }}</td>
                        <td>{{ $donationRequest->notes }}</td>
                        <td>{{ $donationRequest->state }}</td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @can('donation-requests.ver')
                                <a class="btn btn-outline-primary btn-sm" href="{{ route('donation-requests.show', $donationRequest->id) }}">
                                    <i class="fa fa-eye"></i>
                                </a>
                                @endcan
                                @can('donation-requests.editar')
                                <a class="btn btn-outline-success btn-sm" href="{{ route('donation-requests.edit', $donationRequest->id) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                @endcan
                                @can('donation-requests.eliminar')
                                <form action="{{ route('donation-requests.destroy', $donationRequest->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Estás seguro de que deseas eliminarlo?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>

                            @if ($donationRequest->state === 'pendiente')
                            <div class="mt-2 d-flex gap-2">
                                @can('donation-requests.aceptar')
                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#acceptModal-{{ $donationRequest->id }}">Aceptar</button>
                                @endcan
                                @can('donation-requests.rechazar')
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#rejectModal-{{ $donationRequest->id }}">Rechazar</button>
                                @endcan
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- PAGINACIÓN -->
        <div class="mt-3">
            {!! $donationRequests->withQueryString()->links() !!}
        </div>
    </div>
</div>
@endsection
        