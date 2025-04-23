@extends('adminlte::page')

@section('title', 'Create Donation Type')

@section('content_header')
    <h1>{{ __('Create Donation Type') }}</h1>
@stop

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create Donation Type') }}</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('donation-types.store') }}" role="form" enctype="multipart/form-data">
                            @csrf

                            @include('donation-type.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
