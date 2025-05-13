@extends('adminlte::page')

@section('title', __('Finanzas Eliminadas'))

@section('content_header')
    <h1 class="mb-3">{{ __('Finanzas Eliminadas') }}</h1>
@stop

@section('content')
    {{-- Mensaje de éxito --}}
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    {{-- Tabla de Finanzas Eliminadas --}}
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
                                <form action="{{ route('campaign-finances.restore', $campaignFinance->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-undo"></i> {{ __('Restaurar') }}
                                    </button>
                                </form>
                                <form action="{{ route('campaign-finances.destroy-permanently', $campaignFinance->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este registro permanentemente?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash-alt"></i> {{ __('Eliminar Definitivamente') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">{{ __('No se encontraron registros eliminados') }}</td>
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
