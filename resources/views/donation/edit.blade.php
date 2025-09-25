@extends('adminlte::page')

@section('title', __('Update Donation'))

@section('content_header')
    <h1>{{ __('Update Donation') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Edit Donation') }}</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('donations.update', $donation->id) }}" role="form" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                @include('donation.form')

                <div class="col-md-12 mt20 mt-2">
                    <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                </div>
            </form>
        </div>
    </div>
@stop
