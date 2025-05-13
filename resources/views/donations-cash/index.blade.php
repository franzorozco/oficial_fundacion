@extends('adminlte::page')

@section('title', 'Donaciones en Efectivo')

@section('content_header')
    <h1>Donaciones en Efectivo</h1>
@endsection

@section('content')
<div class="container-fluid">

    {{-- Mensaje de éxito --}}
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    {{-- Encabezado de acciones --}}
    <div class="card mb-3">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            
            {{-- Formulario de búsqueda --}}
            <form method="GET" action="{{ route('donations-cashes.index') }}" class="flex-grow-1">
                <div class="input-group">
                    <input 
                        type="text" 
                        name="search" 
                        class="form-control" 
                        placeholder="Buscar por donante, método, campaña..." 
                        value="{{ request('search') }}"
                    >
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </form>

            {{-- Botones de acción --}}
            <div class="d-flex gap-2">
                <a href="{{ route('donations-cashes.create') }}" class="btn btn-outline-primary">
                    <i class="fas fa-plus"></i> Crear Nuevo
                </a>
                <a href="{{ route('donations-cashes.pdf', request()->query()) }}" class="btn btn-outline-danger">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </a>
                <a href="{{ route('donations-cashes.trashed') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-trash-restore"></i> Donaciones Eliminadas
                </a>

            </div>
        </div>

        {{-- Tabla de donaciones --}}
        <div class="card-body bg-white">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Donante</th>
                            <th>Donante Externo</th>
                            <th>Monto</th>
                            <th>Método</th>
                            <th>Fecha</th>
                            <th>Campaña</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($donationsCashes as $donationsCash)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $donationsCash->user->name ?? 'N/A' }}</td>
                                <td>{{ $donationsCash->external_donor->names ?? 'N/A' }}</td>
                                <td>{{ $donationsCash->amount }}</td>
                                <td>{{ $donationsCash->method }}</td>
                                <td>{{ $donationsCash->donation_date }}</td>
                                <td>{{ $donationsCash->campaign->name ?? 'N/A' }}</td>
                                <td>
                                    <form action="{{ route('donations-cashes.destroy', $donationsCash->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este registro?');">
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('donations-cashes.show', $donationsCash->id) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a class="btn btn-sm btn-outline-success" href="{{ route('donations-cashes.edit', $donationsCash->id) }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- Paginación --}}
                <div class="mt-3">
                    {!! $donationsCashes->withQueryString()->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
