@extends('adminlte::page')

@section('title', __('Show') . ' ' . __('Financial Account'))

@section('content_header')
    <h1>{{ __('Show') }} Financial Account</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="card-title">{{ __('Show') }} Financial Account</span>
            <a class="btn btn-primary btn-sm" href="{{ route('financial-accounts.index') }}">
                {{ __('Back') }}
            </a>
            <a class="btn btn-success btn-sm" href="{{ route('financial-accounts.print', $financialAccount->id) }}" target="_blank">
                Imprimir PDF
            </a>

        </div>

        <div class="card-body bg-white">
            <div class="form-group mb-2">
                <strong>Name:</strong>
                {{ $financialAccount->name }}
            </div>
            <div class="form-group mb-2">
                <strong>Type:</strong>
                {{ $financialAccount->type }}
            </div>
            <div class="form-group mb-2">
                <strong>Balance:</strong>
                {{ $financialAccount->balance }}
            </div>
            <div class="form-group mb-2">
                <strong>Description:</strong>
                {{ $financialAccount->description }}
            </div>
        </div>
    </div>
    <!-- Transacciones -->
    <div class="card mt-4">
        <div class="card-header">
            <strong>Historial de Transacciones</strong>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Monto</th>
                        <th>Descripción</th>
                        <th>Campaña</th>
                        <th>Evento</th>
                        <th>Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($financialAccount->transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->transaction_date }}</td>
                            <td>{{ ucfirst($transaction->type) }}</td>
                            <td>{{ number_format($transaction->amount, 2) }}</td>
                            <td>{{ $transaction->description }}</td>
                            <td>{{ optional($transaction->campaign)->name }}</td>
                            <td>{{ optional($transaction->event)->name }}</td>
                            <td>{{ optional($transaction->user)->name }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7">No hay transacciones registradas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Finanzas por Campaña -->
    <div class="card mt-4">
        <div class="card-header">
            <strong>Finanzas por Campaña Asociadas</strong>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Campaña</th>
                        <th>Manager</th>
                        <th>Descripción</th>
                        <th>Ingresos</th>
                        <th>Gastos</th>
                        <th>Balance Neto</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($financialAccount->campaignFinances as $finance)
                        <tr>
                            <td>{{ optional($finance->campaign)->name }}</td>
                            <td>{{ optional($finance->user)->name }}</td>
                            <td>{{ $transaction->description }}</td>
                            <td>{{ number_format($finance->income, 2) }}</td>
                            <td>{{ number_format($finance->expenses, 2) }}</td>
                            <td>{{ number_format($finance->net_balance, 2) }}</td>
                            <td>{{ $finance->created_at }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6">No hay finanzas de campaña asociadas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection