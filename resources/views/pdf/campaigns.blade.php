<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte de Campañas</title>

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

.label {
    font-weight: bold;
    background: #f5f5f5;
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
<h1>Reporte de Campañas</h1>
<div>Total: {{ $reportData['total'] }}</div>
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

<!-- FILTROS (PRO 🔥) -->
@if(!empty(array_filter($reportData['filters'])))
<h2>Filtros Aplicados</h2>

<table class="table">
@foreach($reportData['filters'] as $key => $value)
<tr>
<td class="label">{{ ucfirst(str_replace('_',' ', $key)) }}</td>
<td>{{ is_array($value) ? implode(', ', $value) : ($value ?: '-') }}</td>
</tr>
@endforeach
</table>
@endif

<!-- TABLA DE CAMPAÑAS -->
<h2>Listado de Campañas</h2>

<table class="table">
<thead>
<tr>
    <th>#</th>
    <th>Nombre</th>
    <th>Creador</th>
    <th>Eventos</th>
    <th>Participantes</th>
    <th>Inicio</th>
    <th>Fin</th>
</tr>
</thead>

<tbody>
@foreach($campaigns as $i => $c)
<tr>
    <td class="center">{{ $i+1 }}</td>
    <td>{{ $c->name }}</td>
    <td>{{ $c->user->name ?? '-' }}</td>
    <td class="center">{{ $c->events_count }}</td>
    <td class="center">{{ $c->total_participantes ?? 0 }}</td>
    <td class="center">{{ $c->start_date }}</td>
    <td class="center">{{ $c->end_date }}</td>
</tr>
@endforeach
</tbody>
</table>

<!-- FOOTER -->
<hr>
<div class="small center">
Documento generado automáticamente | {{ $reportData['date'] }} {{ $reportData['time'] }}
</div>

</body>
</html>