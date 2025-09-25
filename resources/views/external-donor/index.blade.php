@extends('adminlte::page')

@section('title', 'Donadores Externos')

@section('content_header')
    <h1>{{ __('Donadores Externos') }}</h1>
@endsection

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success m-4">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <div>
                    @can('external-donors.crear')
                    <a href="{{ route('external-donors.create') }}" class="btn btn-outline-success btn-sm me-2 mb-2">
                        <i class="fa fa-plus"></i> {{ __('Nuevo Donador') }}
                    </a>
                    @endcan
                    @can('external-donors.exportar_pdf')
                    <a href="{{ route('external-donors.pdf', request()->query()) }}" class="btn btn-outline-danger btn-sm mb-2">
                        <i class="fa fa-file-pdf"></i> {{ __('Generar Reporte PDF') }}
                    </a>
                    @endcan
                </div>
            </div>
            @can('external-donors.filtrar')
            <form method="GET" action="{{ route('external-donors.index') }}">
                <div class="row g-2">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o correo" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="paternal" class="form-control" placeholder="Apellido paterno" value="{{ request('paternal') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="maternal" class="form-control" placeholder="Apellido materno" value="{{ request('maternal') }}">
                    </div>
                    <div class="col-md-3 d-flex justify-content-end">
                        <button type="submit" class="btn btn-outline-primary me-2">
                            <i class="fas fa-filter"></i> {{ __('Filtrar') }}
                        </button>
                        <a href="{{ route('external-donors.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-eraser"></i> {{ __('Limpiar') }}
                        </a>
                        @can('external-donors.ver_eliminados')
                        <a href="{{ route('external-donors.trashed') }}" class="btn btn-outline-dark">
                            <i class="fa fa-trash-restore"></i> {{ __('Ver Eliminados') }}
                        </a>
                        @endcan

                    </div>
                </div>
            </form>
            @endcan
            @can('external-donors.ver_eliminados')
            <a href="{{ route('external-donors.trashed') }}" class="btn btn-outline-dark">
                <i class="fa fa-trash-restore"></i> {{ __('Ver Eliminados') }}
            </a>
            @endcan
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
                            <th>Correo Electrónico</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($externalDonors as $externalDonor)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $externalDonor->names }}</td>
                                <td>{{ $externalDonor->paternal_surname }}</td>
                                <td>{{ $externalDonor->maternal_surname }}</td>
                                <td>{{ $externalDonor->email }}</td>
                                <td>{{ $externalDonor->phone }}</td>
                                <td>{{ $externalDonor->address }}</td>
                                <td>
                                    <form action="{{ route('external-donors.destroy', $externalDonor->id) }}" method="POST" class="d-inline">
                                        @can('external-donors.ver')
                                        <a class="btn btn-outline-primary btn-sm" href="{{ route('external-donors.show', $externalDonor->id) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @endcan
                                        @can('external-donors.editar')
                                        <a class="btn btn-outline-success btn-sm" href="{{ route('external-donors.edit', $externalDonor->id) }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @endcan
                                        @csrf
                                        @method('DELETE')
                                        @can('external-donors.eliminar')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="event.preventDefault(); confirm('¿Estás seguro de que deseas eliminar este donador?') ? this.closest('form').submit() : false;">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        @endcan
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table> 
            </div>
            {!! $externalDonors->withQueryString()->links() !!}
        </div>
    </div>
@endsection
