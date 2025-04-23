@extends('adminlte::page')

@section('title', __('Create') . ' Financial Account')

@section('content_header')
    <h1>{{ __('Create') }} Financial Account</h1>
@endsection

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <span class="card-title">{{ __('Create') }} Financial Account</span>
        </div>
        <div class="card-body bg-white">
            <form method="POST" action="{{ route('financial-accounts.store') }}" role="form" enctype="multipart/form-data">
                @csrf

                @include('financial-account.form')

            </form>
        </div>
    </div>
@endsection
