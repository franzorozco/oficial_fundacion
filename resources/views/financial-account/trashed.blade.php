@extends('adminlte::page')

@section('title', 'Cuentas Financieras Eliminadas')

@section('content_header')
    <h1>{{ __('Cuentas Financieras Eliminadas') }}</h1>
@endsection

@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-success m-4">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <span id="card_title">
                    {{ __('Cuentas Financieras Eliminadas') }}
                </span>

                <div class="float-right">
                    <a href="{{ route('financial-accounts.index') }}" class="btn btn-outline-primary btn-sm me-2">
                        <i class="fa fa-arrow-left"></i> {{ __('Volver a Cuentas Activas') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body bg-white">
            <!-- Barra de búsqueda y filtros -->
            <form method="GET" action="{{ route('financial-accounts.trashed') }}" class="mb-3">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Buscar por Nombre o Tipo">
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="min_balance" value="{{ request('min_balance') }}" class="form-control" placeholder="Saldo Mínimo">
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="max_balance" value="{{ request('max_balance') }}" class="form-control" placeholder="Saldo Máximo">
                    </div>
                    <div class="col-md-2 d-flex justify-content-end">
                        <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-filter"></i> {{ __('Aplicar Filtros') }}
                        </button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead">
                        <tr>
                            <th>No</th>
                            <th>{{ __('Nombre') }}</th>
                            <th>{{ __('Tipo') }}</th>
                            <th>{{ __('Saldo') }}</th>
                            <th>{{ __('Descripción') }}</th>
                            <th>{{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($financialAccounts as $financialAccount)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $financialAccount->name }}</td>
                                <td>{{ $financialAccount->type }}</td>
                                <td>{{ $financialAccount->balance }}</td>
                                <td>{{ $financialAccount->description }}</td>
                                <td>
                                    <form action="{{ route('financial-accounts.restore', $financialAccount->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-outline-success btn-sm">
                                            <i class="fa fa-undo"></i> {{ __('Restaurar') }}
                                        </button>
                                    </form>
                                    <form action="{{ route('financial-accounts.forceDelete', $financialAccount->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="event.preventDefault(); confirm('¿Estás seguro de eliminar permanentemente?') ? this.closest('form').submit() : false;">
                                            <i class="fa fa-trash"></i> {{ __('Eliminar Permanentemente') }}
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

    {!! $financialAccounts->withQueryString()->links() !!}
@endsection
