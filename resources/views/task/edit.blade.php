@extends('adminlte::page')

@section('title', __('Update') . ' Task')

@section('content_header')
    <h1>{{ __('Update') }} Task</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="card-title">{{ __('Update') }} Task</span>
        </div>
        <div class="card-body bg-white">
            <form method="POST" action="{{ route('tasks.update', $task->id) }}" role="form" enctype="multipart/form-data">
                {{ method_field('PATCH') }}
                @csrf

                @include('task.form')

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
