@extends('adminlte::page')

@section('title', __('Update') . ' Task Assignment')

@section('content_header')
    <h1>{{ __('Update') }} Task Assignment</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="card-title">{{ __('Update') }} Task Assignment</span>
        </div>
        <div class="card-body bg-white">
            <form method="POST" action="{{ route('task-assignments.update', $taskAssignment->id) }}" role="form" enctype="multipart/form-data">
                @method('PATCH')
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
