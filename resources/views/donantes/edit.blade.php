@extends('adminlte::page')

@section('title', __('Update') . ' User')
@push('css')

<style>
    .form-control {
        border-radius: 12px;
        border: 1px solid #ddd;
        box-shadow: none !important;
        padding: 10px 14px;
        font-size: 15px;
        background-color: #fafafa;
        transition: border-color 0.2s;
    }

    .form-control:focus {
        border-color: #4a90e2;
        box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
        background-color: #fff;
    }

    label.form-label {
        font-weight: 500;
        color: #333;
        margin-bottom: 6px;
    }

    .invalid-feedback {
        font-size: 13px;
        color: #e74c3c;
    }

    .btn-primary {
        background-color: #4a90e2;
        border-color: #4a90e2;
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 500;
    }

    .btn-primary:hover {
        background-color: #357ab7;
        border-color: #357ab7;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .row.padding-1 {
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
    }
</style>

@endpush


@section('content')
    <div class="card card-default">
        <div class="card-header">
            <span class="card-title">{{ __('Update') }} {{ __('User') }}</span>
        </div>
        <div class="card-body bg-white">
            <form method="POST" action="{{ route('donantes.update', $user->id) }}" role="form" enctype="multipart/form-data">
                @method('PATCH')
                @csrf

                @include('donantes.form')

                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>

        </div>
    </div>
@endsection
