@extends('adminlte::page')

@section('title', __('Finanzas de la Campaña'))

@section('content_header')
    <h1>{{ __('Finanzas de la Campaña') }}</h1>
@stop

@section('content')
{{-- Mensaje de éxito --}}
@if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
@endif

{{-- HEADER: BOTONES + FILTROS --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-3">

    <!-- Botones principales -->
    <div class="d-flex flex-wrap gap-2">
        @can('campaign-finances.crear')
        <a href="{{ route('campaign-finances.create') }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-plus"></i> {{ __('Crear Nuevo') }}
        </a>
        @endcan
        @can('campaign-finances.exportar-pdf')
        <a href="{{ route('campaign-finances.export-pdf', request()->query()) }}" class="btn btn-outline-danger btn-sm">
            <i class="fas fa-file-pdf"></i> {{ __('Exportar a PDF') }}
        </a>
        @endcan
        @can('campaign-finances.vereliminados')
        <a href="{{ route('campaign-finances.trashed') }}" class="btn btn-outline-dark btn-sm">
            <i class="fas fa-trash-alt"></i> {{ __('Ver Finanzas Eliminadas') }}
        </a>
        @endcan

        <!-- Botón filtros -->
        @can('campaign-finances.buscar')
        <button class="btn btn-outline-secondary btn-sm" type="button" data-toggle="collapse"
                data-target="#filtrosCollapse" aria-expanded="false" aria-controls="filtrosCollapse">
            <i class="fa fa-sliders-h"></i> Filtros
        </button>
        @endcan
    </div>

</div>

{{-- FILTROS COLAPSABLES --}}
<div class="collapse mb-3" id="filtrosCollapse">
    @can('campaign-finances.buscar')
    <form method="GET" action="{{ route('campaign-finances.index') }}" class="w-100">
        <div class="row g-3">

            <!-- Buscar por Campaña o Gerente -->
            <div class="col-md-4">
                <div class="card card-body p-3 shadow-sm">
                    <h6 class="mb-2"><i class="fa fa-search"></i> Campaña / Gerente</h6>
                    <input type="text" name="search" class="form-control"
                           placeholder="Buscar campaña o gerente..."
                           value="{{ request('search') }}">
                </div>
            </div>

            <!-- Ingresos -->
            <div class="col-md-4">
                <div class="card card-body p-3 shadow-sm">
                    <h6 class="mb-2"><i class="fa fa-dollar-sign"></i> Ingresos</h6>
                    <input type="number" step="0.01" name="income_min" class="form-control mb-2" placeholder="Mín." value="{{ request('income_min') }}">
                    <input type="number" step="0.01" name="income_max" class="form-control" placeholder="Máx." value="{{ request('income_max') }}">
                </div>
            </div>

            <!-- Gastos -->
            <div class="col-md-4">
                <div class="card card-body p-3 shadow-sm">
                    <h6 class="mb-2"><i class="fa fa-money-bill"></i> Gastos</h6>
                    <input type="number" step="0.01" name="expenses_min" class="form-control mb-2" placeholder="Mín." value="{{ request('expenses_min') }}">
                    <input type="number" step="0.01" name="expenses_max" class="form-control" placeholder="Máx." value="{{ request('expenses_max') }}">
                </div>
            </div>

            <!-- Balance -->
            <div class="col-md-4">
                <div class="card card-body p-3 shadow-sm">
                    <h6 class="mb-2"><i class="fa fa-balance-scale"></i> Balance Neto</h6>
                    <input type="number" step="0.01" name="balance_min" class="form-control mb-2" placeholder="Mín." value="{{ request('balance_min') }}">
                    <input type="number" step="0.01" name="balance_max" class="form-control" placeholder="Máx." value="{{ request('balance_max') }}">
                </div>
            </div>

            <!-- Botones aplicar / limpiar -->
            <div class="col-md-2">
                <div class="card card-body p-3 shadow-sm h-100 d-flex flex-column justify-content-center align-items-center">
                    <button type="submit" class="btn btn-primary mb-2 w-100">
                        <i class="fa fa-search"></i> Aplicar
                    </button>
                    <a href="{{ route('campaign-finances.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fa fa-times"></i> Limpiar
                    </a>
                </div>
            </div>

        </div>
    </form>
    @endcan
</div>

{{-- TABLA DE FINANZAS --}}
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
                    <td>{{ $campaignFinance->campaign->name ?? '—' }}</td>
                    <td>{{ $campaignFinance->user->name ?? '—' }}</td>
                    <td>${{ number_format($campaignFinance->income, 2) }}</td>
                    <td>${{ number_format($campaignFinance->expenses, 2) }}</td>
                    <td>
                        <span class="badge bg-{{ $campaignFinance->net_balance >= 0 ? 'success' : 'danger' }}">
                            ${{ number_format($campaignFinance->net_balance, 2) }}
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex flex-wrap gap-1 justify-content-end">
                            @can('campaign-finances.ver')
                            <a href="{{ route('campaign-finances.show', $campaignFinance->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                            @endcan
                            @can('campaign-finances.editar')
                            <a href="{{ route('campaign-finances.edit', $campaignFinance->id) }}" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endcan
                            @can('campaign-finances.eliminar')
                            <form action="{{ route('campaign-finances.destroy', $campaignFinance->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este registro?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                            @endcan
                        </div>
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
