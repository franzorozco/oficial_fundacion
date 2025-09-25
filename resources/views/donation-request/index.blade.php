@extends('adminlte::page')

@section('title', __('Solicitudes de Donación'))

@section('content_header')
    <h1>{{ __('Solicitudes de Donación') }}</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">

                <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                    <div class="col-md-6 p-0 mb-2 mb-md-0">
                        @can('donation-requests.filtrar')
                        <form method="GET" action="{{ route('donation-requests.index') }}">
                            <div class="row g-2">
                                {{-- Búsqueda general --}}
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o correo" value="{{ request('search') }}">
                                </div>

                                {{-- Estado de la solicitud --}}
                                <div class="col-md-3">
                                    <select name="state" class="form-select">
                                        <option value="">-- Estado --</option>
                                        <option value="pendiente" {{ request('state') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="en revision" {{ request('state') == 'en revision' ? 'selected' : '' }}>En Revisión</option>
                                        <option value="aceptado" {{ request('state') == 'aceptado' ? 'selected' : '' }}>Aceptado</option>
                                        <option value="rechazado" {{ request('state') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                                        <option value="finalizado" {{ request('state') == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                                    </select>
                                </div>

                                {{-- Rango de fecha de solicitud --}}
                                <div class="col-md-3">
                                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" placeholder="Desde">
                                </div>
                                <div class="col-md-3">
                                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" placeholder="Hasta">
                                </div>

                                {{-- Usuario encargado --}}
                                <div class="col-md-3">
                                    <select name="user_in_charge_id" class="form-select">
                                        <option value="">-- Encargado --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ request('user_in_charge_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Botones --}}
                                <div class="col-md-3 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-outline-primary me-2">
                                        <i class="fas fa-filter"></i> {{ __('Filtrar') }}
                                    </button>
                                    <a href="{{ route('donation-requests.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-eraser"></i> {{ __('Limpiar') }}
                                    </a>
                                </div>
                            </div>
                        </form>
                        @endcan
                    </div>

                    <div class="col-md-auto p-0">
                        @can('donation-requests.crear')
                        <a href="{{ route('donation-requests.create') }}" class="btn btn-outline-success btn-sm me-2 mb-2">
                            <i class="fas fa-plus"></i> {{ __('Crear Nuevo') }}
                        </a>
                        @endcan
                        @can('donation-requests.exportar_pdf')
                        <a href="{{ route('donation-requests.pdf', request()->only('search')) }}" class="btn btn-outline-danger btn-sm mb-2">
                            <i class="fas fa-file-pdf"></i> {{ __('Exportar a PDF') }}
                        </a>
                        @endcan
                        @can('donation-requests.ver_eliminados')
                        <a href="{{ route('donation-requests.trashed') }}" class="btn btn-outline-dark btn-sm mb-2">
                            <i class="fas fa-trash"></i> {{ __('Ver Eliminados') }}
                        </a>
                        @endcan
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
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Referencia</th>
                                    <th>Usuario Solicitante</th>
                                    <th>Usuario Encargado</th>
                                    <th>Donación (referencia/nombre)</th>
                                    <th>Fecha de Solicitud</th>
                                    <th>Fecha de Creación</th>
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
                                            <form action="{{ route('donation-requests.destroy', $donationRequest->id) }}" method="POST" class="d-inline">
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
                                                @csrf
                                                @can('donation-requests.eliminar')
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="event.preventDefault(); confirm('¿Estás seguro de que deseas eliminarlo?') ? this.closest('form').submit() : false;">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                                @endcan
                                            </form>
                                            @if ($donationRequest->state === 'pendiente')
                                                <div class="mt-4 d-flex justify-content-center gap-3">
                                                    <!-- Botón para abrir modal de aceptación -->
                                                    @can('donation-requests.aceptar')
                                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#acceptModal-{{ $donationRequest->id }}">
                                                        Aceptar Solicitud
                                                    </button>
                                                    @endcan
                                                    <!-- Botón para abrir modal de rechazo -->
                                                    @can('donation-requests.rechazar')
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal-{{ $donationRequest->id }}">
                                                        Rechazar Solicitud
                                                    </button>
                                                    @endcan
                                                    
                                                    <!-- Modal Aceptar -->
                                                        <div class="modal fade" id="acceptModal-{{ $donationRequest->id }}" tabindex="-1" aria-labelledby="acceptModalLabel-{{ $donationRequest->id }}" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <form method="POST" action="{{ route('donation-requests.accept', $donationRequest->id) }}">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="acceptModalLabel-{{ $donationRequest->id }}">Confirmar Aceptación</h5>
                                                                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Cerrar"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p>¿Estás seguro de que deseas aceptar esta solicitud?</p>
                                                                            <div class="mb-3">
                                                                                <label class="form-label">Observaciones (opcional)</label>
                                                                                <textarea name="observations" class="form-control" rows="3"></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="submit" class="btn btn-success">Aceptar</button>
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>

                                                        <!-- Modal Rechazar -->
                                                        <div class="modal fade" id="rejectModal-{{ $donationRequest->id }}" tabindex="-1" aria-labelledby="rejectModalLabel-{{ $donationRequest->id }}" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <form method="POST" action="{{ route('donation-requests.reject', $donationRequest->id) }}">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="rejectModalLabel-{{ $donationRequest->id }}">Confirmar Rechazo</h5>
                                                                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Cerrar"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p>¿Estás seguro de que deseas rechazar esta solicitud?</p>
                                                                            <div class="mb-3">
                                                                                <label class="form-label">Observaciones (opcional)</label>
                                                                                <textarea name="observations" class="form-control" rows="3"></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="submit" class="btn btn-danger">Rechazar</button>
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {!! $donationRequests->withQueryString()->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@stop
 