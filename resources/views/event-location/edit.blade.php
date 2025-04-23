@extends('adminlte::page')

@section('title', 'Update Event Location')

@section('content_header')
    <h1>{{ __('Update Event Location') }}</h1>
@stop

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update Event Location') }}</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('event-locations.update', $eventLocation->id) }}" role="form" enctype="multipart/form-data">
                            @method('PATCH')
                            @csrf

                            @include('event-location.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
