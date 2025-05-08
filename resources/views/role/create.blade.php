@extends('adminlte::page')

@section('title', __('Create Role'))

@section('content_header')
    <h1>{{ __('Create') }} Role</h1>
@endsection

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <span class="card-title">{{ __('Create') }} Role</span>
        </div>
        <div class="card-body bg-white">
            <form method="POST" action="{{ route('roles.store') }}" role="form" enctype="multipart/form-data">
                @csrf

                @include('role.form')
  
                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>
@endsection

@section('css')
    <style>
    .permissions-row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -0.5rem; /* Compensa el gutter */
    }
    .permissions-row > .col-sm-6 {
        padding: 0 0.5rem;
        /* flex: 1 1 50%; ya hace col-sm-6 */
    }
    .permissions-row > .col-md-4 {
        /* flex: 1 1 33.333%; ya hace col-md-4 */
    }

    .custom-checkbox {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f9f9f9;
        transition: border-color 0.3s, background-color 0.3s;
        min-height: 60px;
        word-break: break-word;    /* Qiebra palabras largas */
        white-space: normal;       /* Permite envolver líneas */
    }

    .custom-checkbox:hover {
        border-color: #007bff;
        background-color: #e9f0ff;
    }

    .custom-checkbox .form-check-input {
        margin: 0;
        flex-shrink: 0;
    }

    .custom-checkbox .form-check-label {
        margin: 0 0 0 0.75rem;
        flex: 1;                   /* Que la etiqueta ocupe el resto */
        font-size: 0.94rem;
        color: #333;
        cursor: pointer;
        white-space: normal;
    }

    .custom-checkbox input[type="checkbox"]:checked + .form-check-label {
        color: #007bff;
        font-weight: 600;
    }

    /* Ajuste final al botón */
    button[type="submit"] {
        margin-top: 1.5rem;
        float: right;
    }
    </style>
@endsection


@section('js')
    {{-- <script>console.log('Create Role page loaded');</script> --}}
@endsection
