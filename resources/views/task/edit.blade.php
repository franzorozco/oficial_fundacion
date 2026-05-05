@extends('adminlte::page')

@section('title', 'Editar Tarea')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mb-0">Editar Tarea</h1>
        <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
@endsection

@section('content')
    <div class="card shadow-sm border-0">
        
        {{-- HEADER --}}
        <div class="card-header bg-white border-bottom">
            <h3 class="card-title mb-0">
                <i class="fas fa-edit text-primary"></i> 
                Actualizar Tarea
            </h3>
        </div>

        {{-- BODY --}}
        <div class="card-body">
            <form method="POST" action="{{ route('tasks.update', $task->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                {{-- FORMULARIO --}}
                <div class="row">
                    <div class="col-12">
                        @include('task.form')
                    </div>
                </div>

                {{-- BOTONES --}}
                <div class="d-flex justify-content-end mt-4 gap-2">
                    <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">
                        Cancelar
                    </a>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Actualizar Tarea
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('css')
<style>
    .card {
        border-radius: 10px;
    }

    .card-header {
        font-weight: 600;
        font-size: 16px;
    }

    .btn {
        border-radius: 6px;
    }

    .form-control, .form-select {
        border-radius: 6px;
    }
</style>
@endsection

@section('js')
@endsection