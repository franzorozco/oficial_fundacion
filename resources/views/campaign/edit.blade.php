@extends('adminlte::page')

@section('title', __('Update Campaign'))

@section('content_header')
    <h1>{{ __('Update Campaign') }}</h1>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Update Campaign') }}</h3>
                </div>

                <div class="card-body bg-white">
                    <form method="POST" action="{{ route('campaigns.update', $campaign->id) }}" enctype="multipart/form-data">
                        @method('PATCH')
                        @csrf

                        @include('campaign.form')

                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
