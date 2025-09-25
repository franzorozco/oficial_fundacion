@extends('adminlte::page')

@section('title', __('Create') . ' Task Assignment')

@section('content_header')
    <h1>{{ __('Create') }} Task Assignment</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="card-title">{{ __('Create') }} Task Assignment</span>
        </div>
        <div class="card-body bg-white">
            <form method="POST" action="{{ route('task-assignments.store') }}" role="form" enctype="multipart/form-data">
                @csrf

                @include('task-assignment.form')

            </form>
        </div>
    </div>
@endsection

@section('css')
    {{-- CSS personalizado opcional --}}
@endsection

@section('js')
    {{-- JS personalizado opcional --}}
@endsection
