@extends('adminlte::page')

@section('title', __('Update Donation Request'))

@section('content_header')
    <h1>{{ __('Update Donation Request') }}</h1>
@stop

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update Donation Request') }}</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('donation-requests.update', $donationRequest->id) }}" role="form" enctype="multipart/form-data">
                            @csrf
                            {{ method_field('PATCH') }}

                            @include('donation-request.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
