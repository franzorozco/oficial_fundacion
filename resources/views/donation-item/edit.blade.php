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
            </form>
        </div>
    </div>
@stop
