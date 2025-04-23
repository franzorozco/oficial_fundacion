@extends('layouts.app')

@section('template_title')
    {{ $donationRequest->name ?? __('Show') . " " . __('Donation Request') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Donation Request</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('donation-requests.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Applicant User  Id:</strong>
                                    {{ $donationRequest->applicant_user__id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>User In Charge Id:</strong>
                                    {{ $donationRequest->user_in_charge_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Donation Id:</strong>
                                    {{ $donationRequest->donation_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Request Date:</strong>
                                    {{ $donationRequest->request_date }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Notes:</strong>
                                    {{ $donationRequest->notes }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>State:</strong>
                                    {{ $donationRequest->state }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
