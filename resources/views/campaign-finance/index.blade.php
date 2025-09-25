@extends('adminlte::page')

@section('title', __('Finanzas de la Campaña'))

@section('content_header')
    <h1 class="mb-3">{{ __('Finanzas de la Campaña') }}</h1>
@stop

@section('content')
    {{-- Mensaje de éxito --}}
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    {{-- Acciones --}}

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div class="w-100">
        @can('campaign-finances.buscar')
            <form method="GET" action="{{ route('campaign-finances.index') }}" class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Buscar campaña o gerente..." value="{{ request('search') }}">
                </div>

                <div class="col-md-2">
                    <input type="number" step="0.01" name="income_min" class="form-control" placeholder="Ingreso mín." value="{{ request('income_min') }}">
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="income_max" class="form-control" placeholder="Ingreso máx." value="{{ request('income_max') }}">
                </div>

                <div class="col-md-2">
                    <input type="number" step="0.01" name="expenses_min" class="form-control" placeholder="Gasto mín." value="{{ request('expenses_min') }}">
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="expenses_max" class="form-control" placeholder="Gasto máx." value="{{ request('expenses_max') }}">
                </div>

                <div class="col-md-2">
                    <input type="number" step="0.01" name="balance_min" class="form-control" placeholder="Balance mín." value="{{ request('balance_min') }}">
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="balance_max" class="form-control" placeholder="Balance máx." value="{{ request('balance_max') }}">
                </div>

                <div class="col-md-2">

                    
                    <button type="submit" class="btn btn-secondary w-100">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>

            </form>
        @endcan
        </div>
 
        <div class="d-flex gap-2">
            @can('campaign-finances.crear')
            <a href="{{ route('campaign-finances.create') }}" class="btn btn-sm btn-outline-primary ">
                <i class="fas fa-plus"></i> {{ __('Crear Nuevo') }}
            </a>
            @endcan 
            @can('campaign-finances.exportar-pdf')
            <a href="{{ route('campaign-finances.export-pdf', request()->query()) }}" class="btn btn-sm  btn-outline-danger ">
                <i class="fas fa-file-pdf"></i> {{ __('Exportar a PDF') }}
            </a>
            @endcan
            @can('campaign-finances.vereliminados')
            <a href="{{ route('campaign-finances.trashed') }}" class="btn btn-sm btn-outline-danger ">
                <i class="fas fa-trash-alt"></i> {{ __('Ver Finanzas Eliminadas') }}
            </a>
            @endcan 
        </div>
    </div>

    {{-- Tabla de Finanzas --}}
    <div class="card shadow-sm">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>{{ __('Campaña') }}</th>
                        <th>{{ __('Gerente') }}</th>
                        <th>{{ __('Ingresos') }}</th>
                        <th>{{ __('Gastos') }}</th>
                        <th>{{ __('Balance Neto') }}</th>
                        <th class="text-end">{{ __('Acciones') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($campaignFinances as $campaignFinance)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $campaignFinance->campaign->name }}</td>
                            <td>{{ $campaignFinance->user->name }}</td>
                            <td>${{ number_format($campaignFinance->income, 2) }}</td>
                            <td>${{ number_format($campaignFinance->expenses, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $campaignFinance->net_balance >= 0 ? 'success' : 'danger' }}">
                                    ${{ number_format($campaignFinance->net_balance, 2) }}
                                </span>
                            </td>
                            <td class="text-end">
                                @can('campaign-finances.ver')
                                <a href="{{ route('campaign-finances.show', $campaignFinance->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> {{ __('Ver') }}
                                </a>
                                @endcan
                                @can('campaign-finances.editar')
                                <a href="{{ route('campaign-finances.edit', $campaignFinance->id) }}" class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-edit"></i> {{ __('Editar') }}
                                </a>
                                @endcan
                                {{-- Eliminar --}}
                                @can('campaign-finances.eliminar')
                                <form action="{{ route('campaign-finances.destroy', $campaignFinance->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este registro?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash-alt"></i> {{ __('Eliminar') }}
                                    </button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">{{ __('No se encontraron registros') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Paginación --}}
    <div class="mt-4 d-flex justify-content-center">
        {!! $campaignFinances->withQueryString()->links() !!}
    </div>
@stop
