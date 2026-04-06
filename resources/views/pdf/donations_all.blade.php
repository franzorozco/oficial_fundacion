<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Listado de Donaciones</title>

<style>
body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 12px;
    color: #333;
}

.header {
    text-align: center;
    margin-bottom: 10px;
}

.audit-box {
    border: 1px solid #ccc;
    padding: 10px;
    margin-bottom: 15px;
    font-size: 11px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #ddd;
    padding: 6px;
    font-size: 10px;
}

thead {
    background: #f2f2f2;
}

tr:nth-child(even) {
    background: #fafafa;
}

.footer {
    margin-top: 15px;
    text-align: center;
    font-size: 10px;
}
</style>
</head>

<body>

<div class="header">
    <h2>Sistema de Gestión de Donaciones</h2>
    <h3>Listado General de Donaciones</h3>
</div>

<div class="audit-box">
    <table>
        <tr>
            <td><strong>Generado por:</strong> {{ $reportData['generated_by'] }}</td>
            <td><strong>Email:</strong> {{ $reportData['generated_email'] }}</td>
        </tr>
        <tr>
            <td><strong>Fecha:</strong> {{ $reportData['date'] }}</td>
            <td><strong>Hora:</strong> {{ $reportData['time'] }}</td>
        </tr>
        <tr>
            <td><strong>Total:</strong> {{ $reportData['total'] }}</td>
            <td><strong>IP:</strong> {{ $reportData['ip'] }}</td>
        </tr>
    </table>
</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Donante</th>
            <th>Usuario</th>
            <th>Recibido por</th>
            <th>Estado</th>
            <th>Campaña</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        @foreach($donations as $i => $d)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $d->externalDonor->names ?? '-' }}</td>
            <td>{{ $d->user->name ?? '-' }}</td>
            <td>{{ $d->receivedBy->name ?? '-' }}</td>
            <td>{{ $d->status->name ?? '-' }}</td>
            <td>{{ $d->campaign->name ?? '-' }}</td>
            <td>{{ \Carbon\Carbon::parse($d->donation_date)->format('d/m/Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    Documento generado automáticamente | {{ now()->format('d/m/Y H:i:s') }}
</div>

</body>
</html>