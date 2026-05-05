@extends('adminlte::page')

@section('title', 'Crear Habilidad')

@section('content_header')
    <h1>Crear Habilidad</h1>
@endsection

@section('content')
<div class="card shadow-sm">

    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fa fa-plus text-primary"></i> Nueva Habilidad
        </h5>

        <a class="btn btn-outline-secondary btn-sm" href="{{ route('skills-catalogs.index') }}">
            <i class="fa fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card-body">

        <form method="POST"
              action="{{ route('skills-catalogs.store') }}"
              enctype="multipart/form-data">

            @csrf

            <div class="row">

                <!-- Nombre -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           placeholder="Ej: Trabajo en equipo">

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Descripción -->
                <div class="col-md-12 mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="description"
                              class="form-control @error('description') is-invalid @enderror"
                              rows="4"
                              placeholder="Describe la habilidad...">{{ old('description') }}</textarea>

                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('skills-catalogs.index') }}" class="btn btn-outline-secondary">
                    Cancelar
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Guardar
                </button>
            </div>

        </form>

    </div>
</div>
@endsection