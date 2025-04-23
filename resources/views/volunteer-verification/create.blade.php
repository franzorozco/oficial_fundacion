@extends('adminlte::page')

@section('title', 'Create Volunteer Verification')

@section('content_header')
    <h1>{{ __('Create') }} Volunteer Verification</h1>
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Volunteer Verification</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('volunteer-verifications.store') }}" role="form" enctype="multipart/form-data">
                            @csrf

                            @include('volunteer-verification.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
