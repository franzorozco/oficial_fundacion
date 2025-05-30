@extends('adminlte::page')

@section('title', 'Create Event Location')

@section('content_header')
    <h1>{{ __('Create Event Location') }}</h1>
@stop

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create Event Location') }}</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('event-locations.store') }}" role="form" enctype="multipart/form-data">
                            @csrf

                            @include('event-location.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
