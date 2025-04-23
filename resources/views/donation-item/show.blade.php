@extends('layouts.app')

@section('template_title')
    {{ $donationItem->name ?? __('Show') . " " . __('Donation Item') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Donation Item</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('donation-items.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Donation Id:</strong>
                                    {{ $donationItem->donation_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Donation Type Id:</strong>
                                    {{ $donationItem->donation_type_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Item Name:</strong>
                                    {{ $donationItem->item_name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Quantity:</strong>
                                    {{ $donationItem->quantity }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Unit:</strong>
                                    {{ $donationItem->unit }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Description:</strong>
                                    {{ $donationItem->description }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
