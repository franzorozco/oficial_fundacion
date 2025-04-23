@extends('adminlte::page')

@section('title', 'Create Notification')

@section('content_header')
    <h1>{{ __('Create Notification') }}</h1>
@endsection

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <span class="card-title">{{ __('Create Notification') }}</span>
        </div>

        <div class="card-body bg-white">
            <form method="POST" action="{{ route('notifications.store') }}" role="form" enctype="multipart/form-data">
                @csrf
                @include('notification.form')
            </form>
        </div>
    </div>
@endsection
