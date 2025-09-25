@extends('adminlte::page')

@section('title', __('Create') . ' Task')

@section('content_header')
    <h1>{{ __('Create') }} Task</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="card-title">{{ __('Create') }} Task</span>
        </div>
        <div class="card-body bg-white">
            <form method="POST" action="{{ route('tasks.store') }}" role="form" enctype="multipart/form-data">
                @csrf

                @include('task.form')

            </form>
        </div>
    </div>
@endsection

{{-- Secciones opcionales para CSS y JS personalizados --}}
@section('css')
    {{-- Agrega aquí tu CSS personalizado si es necesario --}}
@endsection

@section('js')
    {{-- Agrega aquí tu JS personalizado si es necesario --}}
@endsection
