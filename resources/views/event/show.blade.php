@extends('layouts.app')

@section('template_title')
    {{ $event->name ?? __('Show') . " " . __('Event') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Event</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('events.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Campaign Id:</strong>
                                    {{ $event->campaign_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Creator Id:</strong>
                                    {{ $event->creator_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Name:</strong>
                                    {{ $event->name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Description:</strong>
                                    {{ $event->description }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Event Date:</strong>
                                    {{ $event->event_date }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
