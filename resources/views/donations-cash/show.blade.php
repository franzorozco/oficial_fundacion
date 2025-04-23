@extends('layouts.app')

@section('template_title')
    {{ $donationsCash->name ?? __('Show') . " " . __('Donations Cash') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Donations Cash</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('donations-cashes.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Donor Id:</strong>
                                    {{ $donationsCash->donor_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>External Donor Id:</strong>
                                    {{ $donationsCash->external_donor_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Amount:</strong>
                                    {{ $donationsCash->amount }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Method:</strong>
                                    {{ $donationsCash->method }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Donation Date:</strong>
                                    {{ $donationsCash->donation_date }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Campaign Id:</strong>
                                    {{ $donationsCash->campaign_id }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
