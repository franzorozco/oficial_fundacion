@extends('adminlte::page')

@section('title', 'Donadores Externos Eliminados')

@section('content_header')
    <h1>{{ __('Donadores Externos Eliminados') }}</h1>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success m-4">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <a href="{{ route('external-donors.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
                <i class="fas fa-arrow-left"></i> {{ __('Volver a la lista') }}
            </a>
        </div>
        <div class="card-body bg-white">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nombres</th>
                            <th>Apellido Paterno</th>
                            <th>Apellido Materno</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($trashedDonors as $index => $donor)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $donor->names }}</td>
                                <td>{{ $donor->paternal_surname }}</td>
                                <td>{{ $donor->maternal_surname }}</td>
                                <td>{{ $donor->email }}</td>
                                <td>{{ $donor->phone }}</td>
                                <td>{{ $donor->address }}</td>
                                <td>
                                    <form action="{{ route('external-donors.restore', $donor->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button class="btn btn-outline-success btn-sm" onclick="return confirm('¿Restaurar este donador?')">
                                            <i class="fas fa-trash-restore"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('external-donors.forceDelete', $donor->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Eliminar permanentemente este donador?')">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {!! $trashedDonors->links() !!}
        </div>
    </div>
@endsection
