@extends('adminlte::page')

@section('title', 'Descripciones Eliminadas')

@section('content_header')
    <h1>Descripciones Eliminadas</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            @if ($message = Session::get('success'))
                <div class="alert alert-success m-4">
                    <p>{{ $message }}</p>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <a href="{{ route('donation-request-descriptions.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Volver a la lista activa
                    </a>
                </div>

                <div class="card-body bg-white">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ID Solicitud</th>
                                    <th>Nombre del Receptor</th>
                                    <th>Motivo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($donationRequestDescriptions as $description)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $description->donation_request_id }}</td>
                                        <td>{{ $description->recipient_name }}</td>
                                        <td>{{ $description->reason }}</td>
                                        <td class="d-flex">
                                            <form action="{{ route('donation-request-descriptions.restore', $description->id) }}" method="POST" class="me-1">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success btn-sm" onclick="return confirm('¿Restaurar este registro?')">
                                                    <i class="fa fa-undo"></i> Restaurar
                                                </button>
                                            </form>
                                            <form action="{{ route('donation-request-descriptions.force-delete', $description->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Eliminar permanentemente este registro?')">
                                                    <i class="fa fa-trash"></i> Eliminar Definitivamente
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No hay registros eliminados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    {!! $donationRequestDescriptions->withQueryString()->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop
