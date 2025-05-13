@extends('adminlte::page')

@section('title', 'Verificaciones de Voluntarios')

@section('content_header')
    <h1>{{ __('Verificaciones de Voluntarios') }}</h1>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">

                    <!-- Encabezado de la tarjeta -->
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <span id="card_title">
                                {{ __('Verificaciones de Voluntarios') }}
                            </span>
                            <div>
                                <a href="{{ route('volunteer-verifications.create') }}" class="btn btn-outline-primary btn-sm me-2">
                                    <i class="fa fa-plus"></i> {{ __('Crear Nueva') }}
                                </a>
                                <a href="{{ route('volunteer-verifications.pdf') }}" class="btn btn-outline-success btn-sm">
                                    <i class="fa fa-file-pdf"></i> {{ __('Generar PDF') }}
                                </a>
                                <a href="{{ route('volunteer-verifications.trashed') }}" class="btn btn-outline-dark btn-sm">
                                    <i class="fas fa-trash-alt"></i> {{ __('Ver Eliminados') }}
                                </a>

                            </div>
                        </div>
                    </div>

                    <!-- Mensaje de éxito -->
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <!-- Formulario de búsqueda -->
                    <form method="GET" action="{{ route('volunteer-verifications.index') }}" class="p-3 pt-0">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Buscar..." value="{{ request()->input('search') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-search"></i> {{ __('Buscar') }}
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('volunteer-verifications.index') }}" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-eraser"></i> {{ __('Limpiar') }}
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Cuerpo de la tarjeta con tabla -->
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
                                    @foreach ($volunteerVerifications as $volunteerVerification)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $volunteerVerification->user ? $volunteerVerification->user->name : 'N/A' }}</td>
                                            <td>{{ $volunteerVerification->userResp ? $volunteerVerification->userResp->name : 'N/A' }}</td>
                                            <td>{{ $volunteerVerification->document_type }}</td>
                                            <td>{{ $volunteerVerification->document_url }}</td>
                                            <td>{{ $volunteerVerification->name_document }}</td>
                                            <td>{{ $volunteerVerification->status }}</td>
                                            <td>{{ $volunteerVerification->coment }}</td>
                                            <td>
                                                <form action="{{ route('volunteer-verifications.destroy', $volunteerVerification->id) }}" method="POST" class="d-inline">
                                                    <a class="btn btn-outline-primary btn-sm" href="{{ route('volunteer-verifications.show', $volunteerVerification->id) }}">
                                                        <i class="fa fa-eye"></i> {{ __('Ver') }}
                                                    </a>
                                                    <a class="btn btn-outline-success btn-sm" href="{{ route('volunteer-verifications.edit', $volunteerVerification->id) }}">
                                                        <i class="fa fa-edit"></i> {{ __('Editar') }}
                                                    </a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="event.preventDefault(); confirm('¿Estás seguro de eliminar?') ? this.closest('form').submit() : false;">
                                                        <i class="fa fa-trash"></i> {{ __('Eliminar') }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Paginación -->
                    <div class="card-footer">
                        {!! $volunteerVerifications->withQueryString()->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 
