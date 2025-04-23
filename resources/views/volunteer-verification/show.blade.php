@extends('layouts.app')

@section('template_title')
    {{ $volunteerVerification->name ?? __('Show') . " " . __('Volunteer Verification') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Volunteer Verification</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('volunteer-verifications.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>User Id:</strong>
                                    {{ $volunteerVerification->user_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>User Resp Id:</strong>
                                    {{ $volunteerVerification->user_resp_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Document Type:</strong>
                                    {{ $volunteerVerification->document_type }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Document Url:</strong>
                                    {{ $volunteerVerification->document_url }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Name Document:</strong>
                                    {{ $volunteerVerification->name_document }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Status:</strong>
                                    {{ $volunteerVerification->status }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Coment:</strong>
                                    {{ $volunteerVerification->coment }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
