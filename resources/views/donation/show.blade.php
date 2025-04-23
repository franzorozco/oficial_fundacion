@extends('layouts.app')

@section('template_title')
    {{ $donation->name ?? __('Show') . " " . __('Donation') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Donation</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('donations.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>External Donor Id:</strong>
                                    {{ $donation->external_donor_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>User Id:</strong>
                                    {{ $donation->user_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Received By Id:</strong>
                                    {{ $donation->received_by_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Status Id:</strong>
                                    {{ $donation->status_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>During Campaign Id:</strong>
                                    {{ $donation->during_campaign_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Donation Date:</strong>
                                    {{ $donation->donation_date }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Notes:</strong>
                                    {{ $donation->notes }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
