@extends('layouts.app')

@section('template_title')
    {{ $campaignFinance->name ?? __('Show') . " " . __('Campaign Finance') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Campaign Finance</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('campaign-finances.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Campaign Id:</strong>
                                    {{ $campaignFinance->campaign_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Manager Id:</strong>
                                    {{ $campaignFinance->manager_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Income:</strong>
                                    {{ $campaignFinance->income }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Expenses:</strong>
                                    {{ $campaignFinance->expenses }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Net Balance:</strong>
                                    {{ $campaignFinance->net_balance }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
