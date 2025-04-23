@extends('adminlte::page')

@section('title', 'Create External Donor')

@section('content_header')
    <h1>{{ __('Create External Donor') }}</h1>
@stop

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create External Donor') }}</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('external-donors.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('external-donor.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
