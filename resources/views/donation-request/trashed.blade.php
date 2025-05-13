@extends('adminlte::page')

@section('title', __('Solicitudes Eliminadas'))

@section('content_header')
    <h1>{{ __('Solicitudes de Donación Eliminadas') }}</h1>
@stop

@section('content')
<div class="container-fluid">
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif

    <div class="mb-3">
        <a href="{{ route('donation-requests.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('Volver a la lista') }}
        </a>
    </div>

    <div class="card">
        <div class="card-body bg-white table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Solicitante</th>
                        <th>Encargado</th>
                        <th>Donación</th>
                        <th>Fecha de Solicitud</th>
                        <th>Notas</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($donationRequests as $donationRequest)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ optional($donationRequest->user)->name ?? 'N/A' }}</td>
                            <td>{{ optional($donationRequest->userInCharge)->name ?? 'N/A' }}</td>
                            <td>{{ optional($donationRequest->donation)->id ?? 'N/A' }}</td>
                            <td>{{ $donationRequest->request_date }}</td>
                            <td>{{ $donationRequest->notes }}</td>
                            <td>{{ $donationRequest->state }}</td>
                            <td>
                                <form action="{{ route('donation-requests.restore', $donationRequest->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-outline-success btn-sm" onclick="return confirm('¿Restaurar esta solicitud?')">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </form>

                                <form action="{{ route('donation-requests.force-delete', $donationRequest->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Eliminar permanentemente esta solicitud?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center">No hay solicitudes eliminadas.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {!! $donationRequests->links() !!}
        </div>
    </div>
</div>
@stop
