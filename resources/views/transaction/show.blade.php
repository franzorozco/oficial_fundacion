@extends('adminlte::page')

@section('title', 'Comprobante de Transacción')

@section('content_header')
    <h1 class="text-center">Comprobante de Transacción</h1>
@endsection

@section('content')
    <div class="card shadow-sm border border-dark">
        <div class="card-body p-4">
            {{-- Encabezado del recibo --}}
            <div class="text-center mb-4">
                <h4 class="font-weight-bold">FUNDACIÓN UNIFRANZ</h4>
                <p class="mb-0">Comprobante de Transacción</p>
                <small>{{ $transaction->transaction_date->format('d/m/Y H:i:s') }}</small>
            </div>

            <hr>

            {{-- Detalles principales --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>ID Transacción:</strong> {{ $transaction->id }}</p>
                    <p><strong>Fecha:</strong> {{ $transaction->transaction_date->format('d/m/Y') }}</p>
                    <p><strong>Hora:</strong> {{ $transaction->transaction_time->format('H:i:s') }}</p>
                    <p><strong>Tipo de Transacción:</strong> 
                        <span class="badge badge-{{ $transaction->type == 'ingreso' ? 'success' : 'danger' }}">
                            {{ ucfirst($transaction->type) }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>Monto:</strong> Bs {{ number_format($transaction->amount, 2, ',', '.') }}</p>
                    <p><strong>Descripción:</strong> {{ $transaction->description }}</p>
                    <p><strong>Cuenta Financiera:</strong> {{ $transaction->financial_account->name ?? $transaction->account_id }}</p>
                    <p><strong>Saldo Actual Cuenta:</strong> 
                        Bs {{ number_format($transaction->financial_account->balance ?? 0, 2, ',', '.') }}
                    </p>
                </div>
            </div>

            <hr>

            {{-- Información adicional --}}
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Campaña Relacionada:</strong> 
                        {{ $transaction->campaign->title ?? 'N/A' }}
                    </p>
                    <p><strong>Evento Relacionado:</strong> 
                        {{ $transaction->event->name ?? 'N/A' }}
                    </p>
                    <p><strong>Ubicación de Evento:</strong> 
                        {{ $transaction->event_location->name ?? 'N/A' }}
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>Registrado por:</strong> 
                        {{ $transaction->user->name ?? 'Usuario desconocido' }}
                    </p>
                    <p><strong>Fecha de Registro:</strong> 
                        {{ $transaction->created_at ? $transaction->created_at->format('d/m/Y H:i:s') : 'N/A' }}
                    </p>
                </div>
            </div>

            <hr>

            {{-- Pie de recibo --}}
            <div class="text-center mt-4">
                <small class="text-muted">Este comprobante fue generado automáticamente por el sistema.</small>
            </div>
        </div>
    </div>

    <div class="text-center mt-3">
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver al listado
        </a>

        <a href="{{ route('transactions.downloadPDF', $transaction->id) }}" class="btn btn-success ml-2" target="_blank">
            <i class="fas fa-file-pdf"></i> Imprimir / Descargar PDF
        </a>

    </div>

@endsection

@section('css')
    <style>
        .card {
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
@endsection
