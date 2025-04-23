@extends('layouts.app')

@section('template_title')
    {{ $transaction->name ?? __('Show') . " " . __('Transaction') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Transaction</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('transactions.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Account Id:</strong>
                                    {{ $transaction->account_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Type:</strong>
                                    {{ $transaction->type }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Amount:</strong>
                                    {{ $transaction->amount }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Description:</strong>
                                    {{ $transaction->description }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Related Campaign Id:</strong>
                                    {{ $transaction->related_campaign_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Related Payment Id:</strong>
                                    {{ $transaction->related_payment_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Transaction Date:</strong>
                                    {{ $transaction->transaction_date }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Created By:</strong>
                                    {{ $transaction->created_by }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
