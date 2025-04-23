@extends('adminlte::page')

@section('title', __('Create Campaign'))

@section('content_header')
    <h1>{{ __('Create Campaign') }}</h1>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Create Campaign') }}</h3>
                </div>

                <div class="card-body bg-white">
                    <form method="POST" action="{{ route('campaigns.store') }}" enctype="multipart/form-data">
                        @csrf

                        @include('campaign.form')

                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
