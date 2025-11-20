@extends('adminlte::page')

@section('title', 'Donadores Externos')

@section('content_header')
    <h1>{{ __('Donadores Externos') }}</h1>
@endsection

@section('content')

@if ($message = Session::get('success'))
    <div class="alert alert-success m-3">
        <p class="mb-0">{{ $message }}</p>
    </div>
@endif

<div class="card">

    <!-- HEADER: TÍTULO + BOTONES -->
    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">

        <span id="card_title" class="h5 m-0">{{ __('Donadores Externos') }}</span>

        <div class="d-flex flex-wrap gap-2">
            @can('external-donors.crear')
            <a href="{{ route('external-donors.create') }}" class="btn btn-outline-success btn-sm">
                <i class="fa fa-plus"></i> Nuevo Donador
            </a>
            @endcan

            @can('external-donors.exportar_pdf')
            <a href="{{ route('external-donors.pdf', request()->query()) }}" class="btn btn-outline-danger btn-sm">
                <i class="fa fa-file-pdf"></i> Generar Reporte PDF
            </a>
            @endcan

            @can('external-donors.filtrar')
            <button class="btn btn-outline-secondary btn-sm" type="button" data-toggle="collapse"
                    data-target="#filtrosCollapse" aria-expanded="false" aria-controls="filtrosCollapse">
                <i class="fa fa-sliders-h"></i> Filtros
            </button>
            @endcan

            @can('external-donors.ver_eliminados')
            <a href="{{ route('external-donors.trashed') }}" class="btn btn-outline-dark btn-sm">
                <i class="fa fa-trash-restore"></i> Ver Eliminados
            </a>
            @endcan
        </div>
    </div>

    <!-- FILTROS COLAPSABLES -->
    @can('external-donors.filtrar')
    <div class="collapse mt-3" id="filtrosCollapse">
        <form method="GET" action="{{ route('external-donors.index') }}">
            <div class="row g-3">

                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o correo" value="{{ request('search') }}">
                </div>

                <div class="col-md-3">
                    <input type="text" name="paternal" class="form-control" placeholder="Apellido paterno" value="{{ request('paternal') }}">
                </div>

                <div class="col-md-3">
                    <input type="text" name="maternal" class="form-control" placeholder="Apellido materno" value="{{ request('maternal') }}">
                </div>

                <div class="col-md-3 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                    <a href="{{ route('external-donors.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-eraser"></i> Limpiar
                    </a>
                </div>

            </div>
        </form>
    </div>
    @endcan

    <!-- TABLA -->
    <div class="card-body bg-white mt-3">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
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
                                <div class="d-flex flex-wrap gap-1">
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

                                    @can('external-donors.eliminar')
                                    <form action="{{ route('external-donors.destroy', $externalDonor->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de que deseas eliminar este donador?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- PAGINACIÓN -->
        <div class="mt-3">
            {!! $externalDonors->withQueryString()->links() !!}
        </div>
    </div>

</div>
@endsection
