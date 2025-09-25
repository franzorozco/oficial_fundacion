@extends('adminlte::page')

@section('title', 'Cuentas Financieras')

@section('content_header')
    <h1>{{ __('Cuentas Financieras') }}</h1>
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
                    {{ __('Cuentas Financieras') }}
                </span>

                <div class="float-right">
                    @can('financial-accounts.crear')
                    <a href="{{ route('financial-accounts.create') }}" class="btn btn-outline-primary btn-sm me-2">
                        <i class="fa fa-plus"></i> {{ __('Crear Nueva') }}
                    </a>
                    @endcan
                    @can('financial-accounts.pdf')
                    <a href="{{ route('financial-accounts.pdf', request()->query()) }}" class="btn btn-outline-info btn-sm me-2">
                        <i class="fa fa-file-pdf"></i> {{ __('Generar PDF') }}
                    </a>
                    @endcan
                    @can('financial-accounts.trashed')
                    <a href="{{ route('financial-accounts.trashed') }}" class="btn btn-outline-dark btn-sm">
                        <i class="fa fa-trash-alt"></i> {{ __('Ver Cuentas Eliminadas') }}
                    </a>
                    @endcan
                    @can('financial-accounts.transferir')
                    <button class="btn btn-outline-warning btn-sm me-2" data-toggle="modal" data-target="#transferModal">
                        <i class="fas fa-exchange-alt"></i> {{ __('Transferir entre Cuentas') }}
                    </button>
                    @endcan
                </div>

            </div>
        </div>
 
        <div class="card-body bg-white">
            @can('financial-accounts.filtrar')
            <form method="GET" action="{{ route('financial-accounts.index') }}" class="mb-3">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Buscar por Nombre o Tipo">
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="min_balance" value="{{ request('min_balance') }}" class="form-control" placeholder="Saldo Mínimo" step="0.01">
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="max_balance" value="{{ request('max_balance') }}" class="form-control" placeholder="Saldo Máximo" step="0.01">
                    </div>
                    <div class="col-md-2 d-flex justify-content-end gap-1">
                        <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-filter"></i> {{ __('Aplicar Filtros') }}
                        </button>
                        <a href="{{ route('financial-accounts.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times"></i> Limpiar
                        </a>
                    </div>
                </div>
            </form>
            @endcan

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
                                    <form action="{{ route('financial-accounts.destroy', $financialAccount->id) }}" method="POST" class="d-inline">
                                        @can('financial-accounts.ver')
                                        <a class="btn btn-outline-primary btn-sm" href="{{ route('financial-accounts.show', $financialAccount->id) }}">
                                            <i class="fa fa-eye"></i> {{ __('Ver') }}
                                        </a>
                                        @endcan
                                        @can('financial-accounts.editar')
                                        <a class="btn btn-outline-success btn-sm" href="{{ route('financial-accounts.edit', $financialAccount->id) }}">
                                            <i class="fa fa-edit"></i> {{ __('Editar') }}
                                        </a>
                                        @endcan
                                        @can('financial-accounts.imprimir')
                                        <a class="btn btn-success btn-sm" href="{{ route('financial-accounts.print', $financialAccount->id) }}" target="_blank">
                                            <i class="fa fa-print"></i> Imprimir PDF
                                        </a>
                                        @endcan
                                        @can('financial-accounts.eliminar')
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="event.preventDefault(); confirm('¿Estás seguro de eliminar?') ? this.closest('form').submit() : false;">
                                            <i class="fa fa-trash"></i> {{ __('Eliminar') }}
                                        </button>
                                        @endcan
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table> 
            </div>
        </div>
    </div>

    <!-- Modal de Transferencia -->
    <div class="modal fade" id="transferModal" tabindex="-1" aria-labelledby="transferModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('financial-accounts.transfer') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="transferModalLabel">Transferencia entre Cuentas</h5>
                        <button type="button" class="btn-close" data-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="from_account" class="form-label">Cuenta Origen</label>
                            <select name="from_account" class="form-select" required>
                                <option value="">Seleccione</option>
                                @foreach($financialAccounts as $acc)
                                    <option value="{{ $acc->id }}">{{ $acc->name }} - Saldo: {{ $acc->balance }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="to_account" class="form-label">Cuenta Destino</label>
                            <select name="to_account" class="form-select" required>
                                <option value="">Seleccione</option>
                                @foreach($financialAccounts as $acc)
                                    <option value="{{ $acc->id }}">{{ $acc->name }} - Saldo: {{ $acc->balance }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Monto a transferir</label>
                            <input type="number" name="amount" class="form-control" min="0.01" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea name="description" class="form-control" rows="2" required>Transferencia entre cuentas</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Transferir</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {!! $financialAccounts->withQueryString()->links() !!}
@endsection
