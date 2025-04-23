@extends('adminlte::page')

@section('title', __('Create') . ' User')

@section('content_header')
    <h1>{{ __('Create') }} {{ __('User') }}</h1>
@endsection

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <span class="card-title">{{ __('Create') }} {{ __('User') }}</span>
        </div>
        <div class="card-body bg-white">
            <form method="POST" action="{{ route('users.store') }}" role="form" enctype="multipart/form-data">
                @csrf

                @include('user.form')

            </form>
        </div>
    </div>
@endsection
