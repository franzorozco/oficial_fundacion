@extends('adminlte::page')

@section('title', 'Roles Eliminados')

@section('content_header')
    <h1>Roles Eliminados</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span id="card_title">
                    {{ __('Roles Eliminados') }}
                </span>

                <div class="float-right d-flex gap-2">
                    <a href="{{ route('roles.index') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-arrow-left"></i> Volver a Roles Activos
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
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = ($roles->currentPage() - 1) * $roles->perPage(); @endphp
                        @forelse ($roles as $role)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->guard_name }}</td>
                                <td>
                                    <form action="{{ route('roles.restore', $role->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT') <!-- Esto cambia el método real a PUT -->
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('¿Seguro que deseas restaurar este rol?');">
                                            <i class="fa fa-fw fa-undo"></i> Restaurar
                                        </button>
                                    </form>

                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar permanentemente este rol?');">
                                            <i class="fa fa-fw fa-trash"></i> Eliminar Permanentemente
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No hay roles eliminados.</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    {!! $roles->withQueryString()->links() !!}
@endsection

@section('css')
    {{-- <link rel="stylesheet" href="/css/custom.css"> --}}
@endsection

@section('js')
    {{-- <script>console.log('Page loaded');</script> --}}
@endsection
