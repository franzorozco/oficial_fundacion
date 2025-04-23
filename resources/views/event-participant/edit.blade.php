@extends('adminlte::page')

@section('title', 'Update Event Participant')

@section('content_header')
    <h1>{{ __('Update Event Participant') }}</h1>
@stop

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Event Participant</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('event-participants.update', $eventParticipant->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('event-participant.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
