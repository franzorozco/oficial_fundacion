@extends('adminlte::page')

@section('title', __('Create Role'))

@section('content_header')
    <h1>{{ __('Create') }} Role</h1>
@endsection

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <span class="card-title">{{ __('Create') }} Role</span>
        </div>
        <div class="card-body bg-white">
            <form method="POST" action="{{ route('roles.store') }}" role="form" enctype="multipart/form-data">
                @csrf

                @include('role.form')

                <h2 class="h3">Permisos</h2>
                @error('permission')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                @enderror

                @foreach($permissions as $permission)
                    <div class="form-check">
                        <input class="form-check-input mr-1" type="checkbox" name="permission[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}">
                        
                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                            {{ $permission->description }}
                        </label>
                    </div>

                @endforeach
            </form>
        </div>
    </div>
@endsection

@section('css')
    {{-- <link rel="stylesheet" href="/css/custom.css"> --}}
@endsection

@section('js')
    {{-- <script>console.log('Create Role page loaded');</script> --}}
@endsection
