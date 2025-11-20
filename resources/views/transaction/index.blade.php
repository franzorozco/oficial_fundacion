@extends('adminlte::page')

@section('title', 'Transacciones')

@section('content_header')
    <h1>{{ __('Transacciones') }}</h1>
@endsection

@section('content')
@if ($message = Session::get('success'))
    <div class="alert alert-success m-3">
        <p class="mb-0">{{ $message }}</p>
    </div>
@endif

<div class="card">

    <!-- HEADER: TÍTULO + BOTONES -->
    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
        <span class="h5 m-0">{{ __('Transacciones') }}</span>

        <div class="d-flex flex-wrap gap-2">
            @can('transactions.pdf')
            <form method="GET" action="{{ route('transactions.pdf') }}">
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </button>
            </form>
            @endcan

            @can('transactions.filtrar')
            <button class="btn btn-outline-secondary btn-sm" type="button" data-toggle="collapse" data-target="#filtrosCollapse" aria-expanded="false">
                <i class="fa fa-sliders-h"></i> Filtros
            </button>
            @endcan
        </div>
    </div>

    <!-- FILTROS COLAPSABLES -->
    @can('transactions.filtrar')
    <div class="collapse mt-3" id="filtrosCollapse">
        <div class="card-body border">
            <form method="GET" action="{{ route('transactions.index') }}">
                <div class="row g-3">

                    <!-- Fila 1 -->
                    <div class="col-md-3">
                        <label class="form-label">Buscar</label>
                        <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Buscar...">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Tipo</label>
                        <select name="type" class="form-select">
                            <option value="">Todos</option>
                            @foreach (['ingreso','gasto','transferencia','donacion','transferencia-gasto','transferencia-ingreso','reembolso','ajuste'] as $tipo)
                                <option value="{{ $tipo }}" {{ request('type') == $tipo ? 'selected' : '' }}>{{ ucfirst($tipo) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Descripción</label>
                        <input type="text" name="description" class="form-control" placeholder="Descripción" value="{{ request('description') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Creado Por</label>
                        <input type="text" name="created_by" class="form-control" placeholder="Creado Por" value="{{ request('created_by') }}">
                    </div>

                    <!-- Fila 2 -->
                    <div class="col-md-2">
                        <label class="form-label">Monto mínimo</label>
                        <input type="number" step="0.01" name="amount_min" class="form-control" placeholder="Monto mínimo" value="{{ request('amount_min') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Monto máximo</label>
                        <input type="number" step="0.01" name="amount_max" class="form-control" placeholder="Monto máximo" value="{{ request('amount_max') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Fecha desde</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Fecha hasta</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Hora desde</label>
                        <input type="time" name="time_from" class="form-control" value="{{ request('time_from') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Hora hasta</label>
                        <input type="time" name="time_to" class="form-control" value="{{ request('time_to') }}">
                    </div>

                    <!-- Botones filtrar/limpiar -->
                    <div class="col-md-2 d-grid align-self-end">
                        <button type="submit" class="btn btn-outline-primary">Filtrar</button>
                    </div>
                    <div class="col-md-2 d-grid align-self-end">
                        <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                    </div>

                </div>
            </form>
        </div>
    </div>
    @endcan

    <!-- TABLA -->
    <div class="card-body bg-white mt-3">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID de Cuenta</th>
                        <th>Tipo</th>
                        <th>Monto</th>
                        <th>Descripción</th>
                        <th>Campaña Relacionada</th>
                        <th>Evento Relacionado</th>
                        <th>Ubicación de Evento</th>
                        <th>Fecha de Transacción</th>
                        <th>Hora de Transacción</th>
                        <th>Creado Por</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $transaction->financial_account->name ?? 'N/A' }} (ID: {{ $transaction->financial_account->id ?? 'N/A' }})</td>
                        <td>{{ ucfirst($transaction->type) }}</td>
                        <td>{{ $transaction->amount }}</td>
                        <td>{{ $transaction->description }}</td>
                        <td>{{ $transaction->campaign->name ?? 'N/A' }}</td>
                        <td>{{ $transaction->event->name ?? 'N/A' }}</td>
                        <td>{{ $transaction->event_location->location_name ?? 'N/A' }}</td>
                        <td>{{ $transaction->transaction_date->format('Y/m/d') }}</td>
                        <td>{{ $transaction->transaction_time->format('H:i:s') }}</td>
                        <td>{{ $transaction->user?->name ?? 'N/A' }}</td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @can('transactions.ver')
                                <a class="btn btn-outline-primary btn-sm" href="{{ route('transactions.show', $transaction->id) }}">
                                    <i class="fa fa-eye"></i> Ver
                                </a>
                                @endcan
                                @can('transactions.imprimir')
                                <a class="btn btn-outline-success btn-sm" href="{{ route('transactions.downloadPDF', $transaction->id) }}" target="_blank">
                                    <i class="fas fa-file-pdf"></i> Imprimir
                                </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- PAGINACIÓN -->
        <div class="mt-3">
            {!! $transactions->withQueryString()->links() !!}
        </div>
    </div>
</div>
@endsection
