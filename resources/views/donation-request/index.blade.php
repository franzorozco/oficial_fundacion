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
                        <form method="GET" action="{{ route('donation-requests.index') }}">
                            <div class="input-group">
                                <input 
                                    type="text" 
                                    name="search" 
                                    class="form-control" 
                                    placeholder="{{ __('Buscar por notas, estado, ID de usuario...') }}" 
                                    value="{{ request()->search }}"
                                >
                                <button class="btn btn-outline-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-auto p-0">
                        <a href="{{ route('donation-requests.create') }}" class="btn btn-outline-success btn-sm me-2 mb-2">
                            <i class="fas fa-plus"></i> {{ __('Crear Nuevo') }}
                        </a>
                        <a href="{{ route('donation-requests.pdf', request()->only('search')) }}" class="btn btn-outline-danger btn-sm mb-2">
                            <i class="fas fa-file-pdf"></i> {{ __('Exportar a PDF') }}
                        </a>
                        <a href="{{ route('donation-requests.trashed') }}" class="btn btn-outline-dark btn-sm mb-2">
                            <i class="fas fa-trash"></i> {{ __('Ver Eliminados') }}
                        </a>

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
                                    <th>Usuario Solicitante</th>
                                    <th>Usuario Encargado</th>
                                    <th>Donación</th>
                                    <th>Fecha de Solicitud</th>
                                    <th>Notas</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($donationRequests as $donationRequest)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ optional($donationRequest->user)->name ?? 'N/A' }}</td>
                                        <td>{{ optional($donationRequest->user)->name ?? 'N/A' }}</td>
                                        <td>{{ optional($donationRequest->donation)->id ?? 'N/A' }}</td>
                                        <td>{{ $donationRequest->request_date }}</td>
                                        <td>{{ $donationRequest->notes }}</td>
                                        <td>{{ $donationRequest->state }}</td>
                                        <td>
                                            <form action="{{ route('donation-requests.destroy', $donationRequest->id) }}" method="POST" class="d-inline">
                                                <a class="btn btn-outline-primary btn-sm" href="{{ route('donation-requests.show', $donationRequest->id) }}">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a class="btn btn-outline-success btn-sm" href="{{ route('donation-requests.edit', $donationRequest->id) }}">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="event.preventDefault(); confirm('¿Estás seguro de que deseas eliminarlo?') ? this.closest('form').submit() : false;">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
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
 