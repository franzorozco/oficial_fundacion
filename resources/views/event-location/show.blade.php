@extends('layouts.app')

@section('template_title')
    {{ $eventLocation->name ?? __('Show') . " " . __('Event Location') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Event Location</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('event-locations.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Event Id:</strong>
                                    {{ $eventLocation->event_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Location Name:</strong>
                                    {{ $eventLocation->location_name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Address:</strong>
                                    {{ $eventLocation->address }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Latitud:</strong>
                                    {{ $eventLocation->latitud }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Longitud:</strong>
                                    {{ $eventLocation->longitud }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
