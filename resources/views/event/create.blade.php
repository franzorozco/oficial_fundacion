@extends('adminlte::page')

@section('title', 'Create Event')

@section('content_header')
    <h1>{{ __('Create Event') }}</h1>
@stop

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Event</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('events.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('event.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
