@extends('adminlte::page')

@section('title', __('Update Role'))

@section('content_header')
    <h1>{{ __('Update') }} Role</h1>
@endsection

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <span class="card-title">{{ __('Update') }} Role</span>
        </div>
        <div class="card-body bg-white">
            <form method="POST" action="{{ route('roles.update', $role->id) }}" role="form" enctype="multipart/form-data">
                @method('PATCH')
                @csrf

                @include('role.form')

            </form>
        </div>
    </div>
@endsection

@section('css')
    {{-- <link rel="stylesheet" href="/css/custom.css"> --}}
@endsection

@section('js')
    {{-- <script>console.log('Edit Role page loaded');</script> --}}
@endsection
