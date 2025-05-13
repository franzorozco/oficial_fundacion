@extends('adminlte::page')

@section('title', 'Verificaciones Eliminadas')

@section('content_header')
    <h1>{{ __('Verificaciones de Voluntarios Eliminadas') }}</h1>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">

                <!-- Encabezado -->
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span id="card_title">{{ __('Verificaciones Eliminadas') }}</span>
                    <a href="{{ route('volunteer-verifications.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> {{ __('Volver') }}
                    </a>
                </div>

                <!-- Mensaje de éxito -->
                @if ($message = Session::get('success'))
                    <div class="alert alert-success m-4">
                        <p>{{ $message }}</p>
                    </div>
                @endif

                <!-- Tabla -->
                <div class="card-body bg-white">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nombre del Usuario</th>
                                    <th>Nombre del Responsable</th>
                                    <th>Tipo de Documento</th>
                                    <th>URL del Documento</th>
                                    <th>Nombre del Documento</th>
                                    <th>Estado</th>
                                    <th>Comentario</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($volunteerVerifications as $verification)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $verification->user?->name ?? 'N/A' }}</td>
                                        <td>{{ $verification->userResp?->name ?? 'N/A' }}</td>
                                        <td>{{ $verification->document_type }}</td>
                                        <td>{{ $verification->document_url }}</td>
                                        <td>{{ $verification->name_document }}</td>
                                        <td>{{ $verification->status }}</td>
                                        <td>{{ $verification->coment }}</td>
                                        <td>
                                            <form action="{{ route('volunteer-verifications.restore', $verification->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-outline-success btn-sm">
                                                    <i class="fas fa-trash-restore"></i> Restaurar
                                                </button>
                                            </form>
                                            <form action="{{ route('volunteer-verifications.forceDelete', $verification->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar permanentemente?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="fas fa-times-circle"></i> Eliminar Definitivamente
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {!! $volunteerVerifications->links() !!}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
