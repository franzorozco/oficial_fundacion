@extends('layouts.app')

@section('template_title')
    {{ $campaign->name ?? __('Show') . " " . __('Campaign') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Campaign</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('campaigns.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Creator Id:</strong>
                                    {{ $campaign->creator_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Name:</strong>
                                    {{ $campaign->name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Description:</strong>
                                    {{ $campaign->description }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Start Date:</strong>
                                    {{ $campaign->start_date }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>End Date:</strong>
                                    {{ $campaign->end_date }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Start Hour:</strong>
                                    {{ $campaign->start_hour }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>End Hour:</strong>
                                    {{ $campaign->end_hour }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
