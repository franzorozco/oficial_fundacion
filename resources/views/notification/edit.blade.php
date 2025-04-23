@extends('adminlte::page')

@section('title', 'Update Notification')

@section('content_header')
    <h1>{{ __('Update Notification') }}</h1>
@endsection

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <span class="card-title">{{ __('Update Notification') }}</span>
        </div>

        <div class="card-body bg-white">
            <form method="POST" action="{{ route('notifications.update', $notification->id) }}" role="form" enctype="multipart/form-data">
                @csrf
                {{ method_field('PATCH') }}
                @include('notification.form')
            </form>
        </div>
    </div>
@endsection
