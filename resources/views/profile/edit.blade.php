@extends('adminlte::page')

@section('title', 'Update Profile')

@section('content_header')
    <h1>{{ __('Update Profile') }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="card-title">{{ __('Update Profile') }}</span>
        </div>
        <div class="card-body bg-white">
            <form method="POST" action="{{ route('profiles.update', $profile->id) }}" role="form" enctype="multipart/form-data">
                @method('PATCH')
                @csrf

                @include('profile.form')

            </form>
        </div>
    </div>
@endsection
