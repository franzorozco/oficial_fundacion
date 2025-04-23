@extends('adminlte::page')

@section('title', __('Update') . ' User')

@section('content_header')
    <h1>{{ __('Update') }} {{ __('User') }}</h1>
@endsection

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <span class="card-title">{{ __('Update') }} {{ __('User') }}</span>
        </div>
        <div class="card-body bg-white">
            <form method="POST" action="{{ route('users.update', $user->id) }}" role="form" enctype="multipart/form-data">
                @method('PATCH')
                @csrf

                @include('user.form')

            </form>
        </div>
    </div>
@endsection
