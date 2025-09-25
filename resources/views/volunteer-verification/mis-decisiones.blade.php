@extends('adminlte::page')

@section('title', 'Mis Decisiones de Verificación')

@section('content_header')
    <h1>Mis Decisiones de Verificación</h1>
@stop

@section('content')
    <div class="card">

        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <span id="card_title">
                    {{ __('Mis Decisiones de Verificación') }}
                </span>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success m-4">
                <p>{{ $message }}</p>
            </div>
        @endif

        <form method="GET" action="{{ route('volunteer-verifications.mis-decisiones') }}" class="p-3 pt-0">
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
                    <a href="{{ route('volunteer-verifications.mis-decisiones') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-eraser"></i> {{ __('Limpiar') }}
                    </a>
                </div>
            </div>
        </form>

        <div class="card-body bg-white">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Usuario</th>
                            <th>Responsable</th>
                            <th>Tipo Documento</th>
                            <th>URL</th>
                            <th>Nombre Documento</th>
                            <th>Estado</th>
                            <th>Comentario</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($volunteerVerifications as $volunteerVerification)
                            <tr>
                                <td>{{ $loop->iteration + $volunteerVerifications->firstItem() - 1 }}</td>
                                <td>{{ $volunteerVerification->user->name ?? 'N/A' }}</td>
                                <td>{{ $volunteerVerification->userResp->name ?? 'N/A' }}</td>
                                <td>{{ $volunteerVerification->document_type }}</td>
                                <td><a href="{{ $volunteerVerification->document_url }}" target="_blank">Ver Documento</a></td>
                                <td>{{ $volunteerVerification->name_document }}</td>
                                <td>{{ $volunteerVerification->status }}</td>
                                <td>{{ $volunteerVerification->coment }}</td>
                                <td>
                                    @if(in_array($volunteerVerification->status, ['aprobado', 'rechazado']))
                                        <form action="{{ route('volunteer-verifications.reconsiderar', $volunteerVerification->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('¿Estás seguro de que deseas reconsiderar esta decisión?');">
                                                <i class="fas fa-undo"></i> Reconsiderar
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            {!! $volunteerVerifications->withQueryString()->links() !!}
        </div>
    </div>
@stop
