@extends('adminlte::page')

@section('title', __('Create Donation'))

@section('content_header')
    <h1>{{ __('Create Donation') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('New Donation') }}</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('donations.store') }}" role="form" enctype="multipart/form-data">
                @csrf

                @include('donation.form')

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('Save') }}
                    </button>
                    <a href="{{ route('donations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop
