@extends('adminlte::page')

@section('title', $user->name ?? __('Show') . ' ' . __('User'))

@section('content_header')
    <h1>{{ __('Show') }} {{ __('User') }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="card-title">{{ __('Show') }} {{ __('User') }}</span>
            <a class="btn btn-primary btn-sm" href="{{ route('users.index') }}">
                {{ __('Back') }}
            </a>
        </div>

        <div class="card-body bg-white">
            <div class="form-group mb-2 mb20">
                <strong>{{ __('Name') }}:</strong>
                {{ $user->name }}
            </div>
            <div class="form-group mb-2 mb20">
                <strong>{{ __('Email') }}:</strong>
                {{ $user->email }}
            </div>
            <div class="form-group mb-2 mb20">
                <strong>{{ __('Phone') }}:</strong>
                {{ $user->phone }}
            </div>
            <div class="form-group mb-2 mb20">
                <strong>{{ __('Address') }}:</strong>
                {{ $user->address }}
            </div>
        </div>
    </div>
@endsection
