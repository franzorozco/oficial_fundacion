@extends('layouts.app')

@section('template_title')
    {{ $donationRequestDescription->name ?? __('Show') . " " . __('Donation Request Description') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Donation Request Description</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('donation-request-descriptions.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Donation Request Id:</strong>
                                    {{ $donationRequestDescription->donation_request_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Recipient Name:</strong>
                                    {{ $donationRequestDescription->recipient_name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Recipient Address:</strong>
                                    {{ $donationRequestDescription->recipient_address }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Recipient Contact:</strong>
                                    {{ $donationRequestDescription->recipient_contact }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Recipient Type:</strong>
                                    {{ $donationRequestDescription->recipient_type }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Reason:</strong>
                                    {{ $donationRequestDescription->reason }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Latitude:</strong>
                                    {{ $donationRequestDescription->latitude }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Longitude:</strong>
                                    {{ $donationRequestDescription->longitude }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Extra Instructions:</strong>
                                    {{ $donationRequestDescription->extra_instructions }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Supporting Documents:</strong>
                                    {{ $donationRequestDescription->supporting_documents }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
