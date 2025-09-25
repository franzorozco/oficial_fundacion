@extends('adminlte::page')

@section('title', 'Transacciones')

@section('content_header')
    <h1>{{ __('Transacciones') }}</h1>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                {{ __('Transacciones') }}
                            </span>
                            <!--
                            <div class="float-right">
                                <a href="{{ route('transactions.create') }}" class="btn btn-primary btn-sm float-right" data-placement="left">
                                    {{ __('Crear Nueva') }}
                                </a>
                            </div>
                            --> 
                        </div>
                    </div>

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        @can('transactions.filtrar')
                        <form method="GET" action="{{ route('transactions.index') }}" class="mb-3">
                            <div class="row g-2 align-items-end">
                                <!-- Fila 1 -->
                                <div class="col-md-4">
                                    <label for="search" class="form-label">Buscar</label>
                                    <input type="search" id="search" name="search" class="form-control" placeholder="Buscar..." value="{{ request('search') }}">
                                </div>

                                <div class="col-md-2">
                                    <label for="type" class="form-label">Tipo</label>
                                    <select id="type" name="type" class="form-select">
                                        <option value="">-- Tipo --</option>
                                        @foreach (['ingreso','gasto','transferencia','donacion','transferencia-gasto','transferencia-ingreso','reembolso','ajuste'] as $tipo)
                                            <option value="{{ $tipo }}" {{ request('type') == $tipo ? 'selected' : '' }}>
                                                {{ ucfirst($tipo) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="description" class="form-label">Descripción</label>
                                    <input type="text" id="description" name="description" class="form-control" placeholder="Descripción" value="{{ request('description') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="created_by" class="form-label">Creado Por</label>
                                    <input type="text" id="created_by" name="created_by" class="form-control" placeholder="Creado Por" value="{{ request('created_by') }}">
                                </div>

                                <!-- Fila 2 -->
                                <div class="col-md-2">
                                    <label for="amount_min" class="form-label">Monto mínimo</label>
                                    <input type="number" step="0.01" id="amount_min" name="amount_min" class="form-control" placeholder="Monto mínimo" value="{{ request('amount_min') }}">
                                </div>

                                <div class="col-md-2">
                                    <label for="amount_max" class="form-label">Monto máximo</label>
                                    <input type="number" step="0.01" id="amount_max" name="amount_max" class="form-control" placeholder="Monto máximo" value="{{ request('amount_max') }}">
                                </div>

                                <div class="col-md-2">
                                    <label for="date_from" class="form-label">Fecha desde</label>
                                    <input type="date" id="date_from" name="date_from" class="form-control" placeholder="Fecha desde" value="{{ request('date_from') }}">
                                </div>

                                <div class="col-md-2">
                                    <label for="date_to" class="form-label">Fecha hasta</label>
                                    <input type="date" id="date_to" name="date_to" class="form-control" placeholder="Fecha hasta" value="{{ request('date_to') }}">
                                </div>

                                <div class="col-md-2">
                                    <label for="time_from" class="form-label">Hora desde</label>
                                    <input type="time" id="time_from" name="time_from" class="form-control" placeholder="Hora desde" value="{{ request('time_from') }}">
                                </div>

                                <div class="col-md-2">
                                    <label for="time_to" class="form-label">Hora hasta</label>
                                    <input type="time" id="time_to" name="time_to" class="form-control" placeholder="Hora hasta" value="{{ request('time_to') }}">
                                </div>

                                <!-- Botones -->
                                <div class="col-md-2 mt-3">
                                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                                </div>

                                <div class="col-md-2 mt-3">
                                    <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary w-100">Limpiar</a>
                                </div>
                                
                            </div>
                        </form>
                        @endcan
                        @can('transactions.pdf')
                        <div class="col-md-2 mt-3">
                            <button 
                                type="submit" 
                                formmethod="GET" 
                                formaction="{{ route('transactions.pdf') }}" 
                                class="btn btn-danger w-100"
                            >
                                Exportar PDF
                            </button>
                        </div>
                        @endcan
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        <th>ID de Cuenta</th>
                                        <th>Tipo</th>
                                        <th>Monto</th>
                                        <th>Descripción</th>
                                        <th>Campaña Relacionada</th>
                                        <th>Evento Relacionado</th>
                                        <th>Ubicacion de Evento</th>
                                        <th>Fecha de Transacción</th>
                                        <th>Hora de Transacción</th>
                                        <th>Creado Por</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $transaction)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $transaction->financial_account->name ?? 'N/A' }} (ID: {{ $transaction->financial_account->id ?? 'N/A' }})</td>
                                            <td>{{ $transaction->type }}</td>
                                            <td>{{ $transaction->amount }}</td>
                                            <td>{{ $transaction->description }}</td>
                                            <td>{{ $transaction->campaign->name ?? 'N/A' }}</td>
                                            <td>{{ $transaction->event->name ?? 'N/A' }}</td>
                                            <td>{{ $transaction->event_location->location_name ?? 'N/A' }}</td>
                                            <td>{{ $transaction->transaction_date->format('Y/m/d') }}</td>
                                            <td>{{ $transaction->transaction_time->format('H:i:s') }}</td>
                                            <td>{{ $transaction->user?->name ?? 'N/A' }}</td>
                                            <td>
                                                <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST">
                                                    @can('transactions.ver')
                                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('transactions.show', $transaction->id) }}">
                                                        <i class="fa fa-fw fa-eye"></i> {{ __('Ver') }}
                                                    </a>
                                                    @endcan
                                                    @can('transactions.imprimir')
                                                    <a class="btn btn-sm btn-outline-success"  href="{{ route('transactions.downloadPDF', $transaction->id) }}" target="_blank">
                                                        <i class="fas fa-file-pdf"></i> Imprimir
                                                    </a>
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
                {!! $transactions->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
