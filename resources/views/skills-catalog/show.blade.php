@extends('adminlte::page')

@section('title', 'Detalle de Habilidad')

@section('content_header')
    <h1>Detalle de Habilidad</h1>
@endsection

@section('content')
<div class="card shadow-sm">
    
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fa fa-eye text-primary"></i> Información de la Habilidad
        </h5>

        <a class="btn btn-outline-secondary btn-sm" href="{{ route('skills-catalogs.index') }}">
            <i class="fa fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card-body">
        
        <div class="row">
            
            <div class="col-md-6 mb-3">
                <div class="border rounded p-3 bg-light">
                    <label class="text-muted mb-1">Nombre</label>
                    <h5 class="mb-0">{{ $skillsCatalog->name }}</h5>
                </div>
            </div>

            <div class="col-md-12">
                <div class="border rounded p-3 bg-light">
                    <label class="text-muted mb-1">Descripción</label>
                    <p class="mb-0">
                        {{ $skillsCatalog->description ?? 'Sin descripción registrada' }}
                    </p>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection