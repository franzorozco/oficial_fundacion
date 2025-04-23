@extends('adminlte::page')

@section('title', 'Update Volunteer Verification')

@section('content_header')
    <h1>{{ __('Update') }} Volunteer Verification</h1>
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Volunteer Verification</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('volunteer-verifications.update', $volunteerVerification->id) }}" role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('volunteer-verification.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
