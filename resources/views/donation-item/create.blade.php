@extends('adminlte::page')

@section('title', __('Create Donation Item'))

@section('content_header')
    <h1>{{ __('Create Donation Item') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('New Donation Item') }}</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('donation-items.store') }}" role="form" enctype="multipart/form-data">
                @csrf

                @include('donation-item.form')

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-plus-circle"></i> {{ __('Save') }}
                    </button>
                    <a href="{{ route('donation-items.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop
