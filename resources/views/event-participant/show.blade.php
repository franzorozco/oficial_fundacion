@extends('layouts.app')

@section('template_title')
    {{ $eventParticipant->name ?? __('Show') . " " . __('Event Participant') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Event Participant</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('event-participants.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Event Id:</strong>
                                    {{ $eventParticipant->event_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>User Id:</strong>
                                    {{ $eventParticipant->user_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Registration Date:</strong>
                                    {{ $eventParticipant->registration_date }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Observations:</strong>
                                    {{ $eventParticipant->observations }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Status:</strong>
                                    {{ $eventParticipant->status }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
