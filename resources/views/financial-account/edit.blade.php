@extends('adminlte::page')

@section('title', __('Update') . ' Financial Account')

@section('content_header')
    <h1>{{ __('Update') }} Financial Account</h1>
@endsection

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <span class="card-title">{{ __('Update') }} Financial Account</span>
        </div>
        <div class="card-body bg-white">
            <form method="POST" action="{{ route('financial-accounts.update', $financialAccount->id) }}" role="form" enctype="multipart/form-data">
                {{ method_field('PATCH') }}
                @csrf

                @include('financial-account.form')

            </form>
        </div>
    </div>
@endsection
