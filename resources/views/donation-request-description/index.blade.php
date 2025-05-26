@extends('adminlte::page')

@section('title', 'Descripciones de Solicitudes de Donación')

@section('content_header')
    <h1>Descripciones de Solicitudes de Donación</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span id="card_title">
                        Descripciones de Solicitudes de Donación
                    </span>
                    <div class="d-flex align-items-center">
                        <form method="GET" action="{{ route('donation-request-descriptions.index') }}" class="me-2">
                            <div class="input-group">
                                <input 
                                    type="text" 
                                    name="search" 
                                    class="form-control" 
                                    placeholder="Buscar por nombre, motivo, dirección..." 
                                    value="{{ request()->search }}"
                                >
                                <button class="btn btn-outline-primary" type="submit">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </form>
                        <a href="{{ route('donation-request-descriptions.create') }}" class="btn btn-outline-success">
                            <i class="fas fa-plus"></i> Nuevo Registro
                        </a>
                        <a href="{{ route('donation-request-descriptions.deleted') }}" class="btn btn-outline-danger ms-2">
                            <i class="fas fa-trash-alt"></i> Ver Eliminados
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
                                    <th>#</th>
                                    <th>Id solicitud</th>
                                    <th>Nombre del Receptor</th>
                                    <th>Dirección del Receptor</th>
                                    <th>Contacto del Receptor</th>
                                    <th>Tipo de Receptor</th>
                                    <th>Motivo</th>
                                    <th>Latitud</th>
                                    <th>Longitud</th>
                                    <th>Instrucciones Extras</th>
                                    <th>Documentos de Soporte</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($donationRequestDescriptions as $donationRequestDescription)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $donationRequestDescription->donation_request_id }}</td>
                                        <td>{{ $donationRequestDescription->recipient_name }}</td>
                                        <td>{{ $donationRequestDescription->recipient_address }}</td>
                                        <td>{{ $donationRequestDescription->recipient_contact }}</td>
                                        <td>{{ $donationRequestDescription->tipo_beneficiario }}</td>
                                        <td>{{ $donationRequestDescription->reason }}</td>
                                        <td>{{ $donationRequestDescription->latitude }}</td>
                                        <td>{{ $donationRequestDescription->longitude }}</td>
                                        <td>{{ $donationRequestDescription->extra_instructions }}</td>
                                        <td>{{ $donationRequestDescription->supporting_documents }}</td>
                                        <td class="d-flex">
                                            <a class="btn btn-outline-primary btn-sm me-1" href="{{ route('donation-request-descriptions.show', $donationRequestDescription->id) }}">
                                                <i class="fa fa-eye"></i> Ver
                                            </a>
                                            <a class="btn btn-outline-success btn-sm me-1" href="{{ route('donation-request-descriptions.edit', $donationRequestDescription->id) }}">
                                                <i class="fa fa-edit"></i> Editar
                                            </a>
                                            <form action="{{ route('donation-request-descriptions.destroy', $donationRequestDescription->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este registro?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="fa fa-trash"></i> Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
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
