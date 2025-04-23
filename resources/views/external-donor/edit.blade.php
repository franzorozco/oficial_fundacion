@extends('adminlte::page')

@section('title', 'Update External Donor')

@section('content_header')
    <h1>{{ __('Update External Donor') }}</h1>
@stop

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update External Donor') }}</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('external-donors.update', $externalDonor->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('external-donor.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
