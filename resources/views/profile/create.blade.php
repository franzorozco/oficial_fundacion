@extends('adminlte::page')

@section('title', 'Create Profile')

@section('content_header')
    <h1>{{ __('Create Profile') }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="card-title">{{ __('Create Profile') }}</span>
        </div>
        <div class="card-body bg-white">
            <form method="POST" action="{{ route('profiles.store') }}" role="form" enctype="multipart/form-data">
                @csrf

                @include('profile.form')

            </form>
        </div>
    </div>
@endsection
