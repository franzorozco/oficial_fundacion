<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Transacciones</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; font-size: 10px; }
        th { background: #f2f2f2; text-align: left; }
        tr:nth-child(even) { background: #fafafa; }
        .center { text-align: center; }
        .title { font-size: 16px; font-weight: bold; text-align: center; }
        .subtitle { font-size: 12px; text-align: center; margin-bottom: 15px; }
        .box { border: 1px solid #ddd; padding: 8px; margin-bottom: 10px; border-radius: 5px; font-size: 10px; }
    </style>
</head>
<body>

<div class="title">Reporte de Transacciones</div>
<div class="subtitle">Generado el {{ $reportData['date'] }} a las {{ $reportData['time'] }}</div>

<div class="box">
    <strong>Información del Reporte:</strong>
    <table width="100%">
        <tr>
            <td><strong>Generado por:</strong> {{ $reportData['generated_by'] }}</td>
            <td><strong>Email:</strong> {{ $reportData['generated_email'] }}</td>
        </tr>
        <tr>
            <td><strong>IP:</strong> {{ $reportData['ip'] }}</td>
            <td><strong>Total registros:</strong> {{ $reportData['total'] }}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>Navegador:</strong> {{ $reportData['user_agent'] }}</td>
        </tr>
    </table>
</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Cuenta (ID)</th>
            <th>Tipo</th>
            <th>Monto</th>
            <th>Descripción</th>
            <th>Campaña</th>
            <th>Evento</th>
            <th>Ubicación Evento</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Creado por</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $i => $t)
        <tr>
            <td class="center">{{ $i + 1 }}</td>
            <td>{{ $t->financial_account->name ?? 'N/A' }} ({{ $t->financial_account->id ?? 'N/A' }})</td>
            <td>{{ ucfirst($t->type) }}</td>
            <td>{{ number_format($t->amount, 2) }}</td>
            <td>{{ Str::limit($t->description, 35) }}</td>
            <td>{{ $t->campaign->name ?? 'N/A' }}</td>
            <td>{{ $t->event->name ?? 'N/A' }}</td>
            <td>{{ $t->event_location->location_name ?? 'N/A' }}</td>
            <td>{{ $t->transaction_date->format('Y/m/d') }}</td>
            <td>{{ $t->transaction_time->format('H:i') }}</td>
            <td>{{ $t->user->name ?? 'N/A' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div style="text-align:center; font-size: 10px; margin-top:10px;">
    Documento generado automáticamente | {{ $reportData['date'] }} {{ $reportData['time'] }}
</div>

</body>
</html>