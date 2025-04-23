@extends('adminlte::page')

@section('title', 'Update Donation Type')

@section('content_header')
    <h1>{{ __('Update Donation Type') }}</h1>
@stop

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update Donation Type') }}</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('donation-types.update', $donationType->id) }}" role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('donation-type.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
