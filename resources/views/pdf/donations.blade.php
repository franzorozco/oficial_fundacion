<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">

<style>
body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 12px;
    color: #333;
}

h1 {
    font-size: 18px;
    margin-bottom: 5px;
}

h2 {
    font-size: 14px;
    margin-top: 20px;
    border-bottom: 1px solid #ddd;
    padding-bottom: 3px;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.table td, .table th {
    border: 1px solid #ddd;
    padding: 6px;
    font-size: 11px;
}

.box {
    border: 1px solid #ddd;
    padding: 10px;
    margin-bottom: 15px;
}

.small {
    font-size: 10px;
    color: #666;
}

.center {
    text-align: center;
}
</style>
</head>

<body>

<!-- HEADER -->
<table width="100%">
<tr>
<td>
<h1>Reporte de Donaciones</h1>
<div>Total registros: {{ $reportData['total'] }}</div>
</td>

<td style="text-align:right;">
<div><strong>Fecha:</strong> {{ $reportData['date'] }}</div>
<div><strong>Hora:</strong> {{ $reportData['time'] }}</div>
</td>
</tr>
</table>

<!-- AUDITORÍA -->
<div class="box">
<strong>Información del reporte</strong>

<table class="table">
<tr>
<td><strong>Generado por</strong></td>
<td>{{ $reportData['generated_by'] }}</td>
</tr>
<tr>
<td><strong>Correo</strong></td>
<td>{{ $reportData['generated_email'] }}</td>
</tr>
<tr>
<td><strong>IP</strong></td>
<td>{{ $reportData['ip'] }}</td>
</tr>
<tr>
<td><strong>Dispositivo</strong></td>
<td>{{ $reportData['user_agent'] }}</td>
</tr>
</table>
</div>

<!-- TABLA -->
<h2>Listado de Donaciones</h2>

<table class="table">
<tr>
<th>#</th>
<th>Donante</th>
<th>Usuario</th>
<th>Recibido por</th>
<th>Estado</th>
<th>Campaña</th>
<th>Fecha</th>
</tr>

@foreach($donations as $i => $d)
<tr>
<td>{{ $i+1 }}</td>
<td>{{ $d->externalDonor->names ?? '-' }}</td>
<td>{{ $d->user->name ?? '-' }}</td>
<td>{{ $d->receivedBy->name ?? '-' }}</td>
<td>{{ $d->status->name ?? '-' }}</td>
<td>{{ $d->campaign->name ?? '-' }}</td>
<td>{{ $d->donation_date }}</td>
</tr>
@endforeach

</table>

<!-- FOOTER -->
<hr>
<div class="small center">
Documento generado automáticamente | {{ $reportData['date'] }} {{ $reportData['time'] }}
</div>

</body>
</html>