@extends('adminlte::page')

@section('title', 'Roles')

@section('content_header')
    <h1>Roles</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span id="card_title">
                    {{ __('Roles') }}
                </span>

                <div class="float-right d-flex gap-2">
                    <a href="{{ route('roles.trashed') }}" class="btn btn-outline-danger btn-sm">
                        <i class="fa fa-trash"></i> Ver Eliminados
                    </a>
                    <a href="{{ route('roles.create') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fa fa-plus"></i> Crear Nuevo
                    </a>
                </div>

            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success m-4">
                <p>{{ $message }}</p>
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="alert alert-danger m-4">
                <p>{{ $message }}</p>
            </div>
        @endif

        <div class="card-body bg-white">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Guard Name</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->guard_name }}</td>
                                <td>
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST">
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('roles.show', $role->id) }}">
                                            <i class="fa fa-fw fa-eye"></i> {{ __('Show') }}
                                        </a>
                                        <a class="btn btn-sm btn-outline-success" href="{{ route('roles.edit', $role->id) }}">
                                            <i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}
                                        </a>

                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;">
                                            <i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {!! $roles->withQueryString()->links() !!}
@endsection

{{-- Opcional: Sección para CSS personalizado --}}
@section('css')
    {{-- <link rel="stylesheet" href="/css/custom.css"> --}}
@endsection

{{-- Opcional: Sección para JS personalizado --}}
@section('js')
    {{-- <script>console.log('Page loaded');</script> --}}
@endsection
