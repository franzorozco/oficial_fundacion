@extends('adminlte::page')

@section('title', __('Update Donation Item'))

@section('content_header')
    <h1>{{ __('Update Donation Item') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Edit Donation Item') }}</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('donation-items.update', $donationItem->id) }}" role="form" enctype="multipart/form-data">
                {{ method_field('PATCH') }}
                @csrf

                @include('donation-item.form')

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('Save Changes') }}
                    </button>
                    <a href="{{ route('donation-items.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop
