<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .title { text-align: center; font-weight: bold; font-size: 18px; margin-bottom: 10px; }
        .section { margin-bottom: 10px; }
        .line { border-bottom: 1px solid #000; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="title">FUNDACIÓN UNIFRANZ</div>
    <div class="section">
        <strong>Comprobante de Transacción</strong><br>
        <small>{{ $transaction->transaction_date->format('d/m/Y H:i:s') }}</small>
    </div>

    <div class="line"></div>

    <div class="section">
        <strong>ID Transacción:</strong> {{ $transaction->id }}<br>
        <strong>Fecha:</strong> {{ $transaction->transaction_date->format('d/m/Y') }}<br>
        <strong>Hora:</strong> {{ $transaction->transaction_time->format('H:i:s') }}<br>
        <strong>Tipo:</strong> {{ ucfirst($transaction->type) }}<br>
        <strong>Monto:</strong> Bs {{ number_format($transaction->amount, 2, ',', '.') }}<br>
        <strong>Descripción:</strong> {{ $transaction->description }}<br>
        <strong>Cuenta Financiera:</strong> {{ $transaction->financial_account->name ?? 'N/A' }}<br>
        <strong>Saldo Actual:</strong> Bs {{ number_format($transaction->financial_account->balance ?? 0, 2, ',', '.') }}<br>
    </div>

    <div class="line"></div>

    <div class="section">
        <strong>Campaña:</strong> {{ $transaction->campaign->title ?? 'N/A' }}<br>
        <strong>Evento:</strong> {{ $transaction->event->name ?? 'N/A' }}<br>
        <strong>Ubicación:</strong> {{ $transaction->event_location->name ?? 'N/A' }}<br>
        <strong>Registrado por:</strong> {{ $transaction->user->name ?? 'Usuario desconocido' }}<br>
        <strong>Fecha de Registro:</strong> {{ $transaction->created_at ? $transaction->created_at->format('d/m/Y H:i:s') : 'N/A' }}<br>
    </div>

    <div class="line"></div>

    <div class="section" style="text-align: center;">
        <small>Este comprobante fue generado automáticamente por el sistema.</small>
    </div>
</body>
</html>
