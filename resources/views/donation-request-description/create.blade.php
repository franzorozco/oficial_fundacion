@extends('adminlte::page')

@section('title', 'Create Donation Request Description')

@section('content_header')
    <h1>{{ __('Create') }} Donation Request Description</h1>
@stop

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Donation Request Description</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('donation-request-descriptions.store') }}" role="form" enctype="multipart/form-data">
                            @csrf

                            @include('donation-request-description.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
