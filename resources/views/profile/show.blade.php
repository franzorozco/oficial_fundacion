@extends('layouts.app')

@section('template_title')
    {{ $profile->name ?? __('Show') . " " . __('Profile') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Profile</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('profiles.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>User Id:</strong>
                                    {{ $profile->user_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Bio:</strong>
                                    {{ $profile->bio }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Document Number:</strong>
                                    {{ $profile->document_number }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Photo:</strong>
                                    {{ $profile->photo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Birthdate:</strong>
                                    {{ $profile->birthdate }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Skills:</strong>
                                    {{ $profile->skills }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Interests:</strong>
                                    {{ $profile->interests }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Availability Days:</strong>
                                    {{ $profile->availability_days }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Availability Hours:</strong>
                                    {{ $profile->availability_hours }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Location:</strong>
                                    {{ $profile->location }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Transport Available:</strong>
                                    {{ $profile->transport_available }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Experience Level:</strong>
                                    {{ $profile->experience_level }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Physical Condition:</strong>
                                    {{ $profile->physical_condition }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Preferred Tasks:</strong>
                                    {{ $profile->preferred_tasks }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Languages Spoken:</strong>
                                    {{ $profile->languages_spoken }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
