@extends('adminlte::page')

@section('title', __('Create Campaign Finance'))

@section('content_header')
    <h1>{{ __('Create Campaign Finance') }}</h1>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Create Campaign Finance') }}</h3>
                </div>

                <div class="card-body bg-white">
                    <form method="POST" action="{{ route('campaign-finances.store') }}" enctype="multipart/form-data">
                        @csrf

                        @include('campaign-finance.form')

                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
