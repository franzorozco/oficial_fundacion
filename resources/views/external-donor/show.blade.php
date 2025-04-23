@extends('layouts.app')

@section('template_title')
    {{ $externalDonor->name ?? __('Show') . " " . __('External Donor') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} External Donor</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('external-donors.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Names:</strong>
                                    {{ $externalDonor->names }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Paternal Surname:</strong>
                                    {{ $externalDonor->paternal_surname }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Maternal Surname:</strong>
                                    {{ $externalDonor->maternal_surname }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Email:</strong>
                                    {{ $externalDonor->email }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Phone:</strong>
                                    {{ $externalDonor->phone }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Address:</strong>
                                    {{ $externalDonor->address }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
