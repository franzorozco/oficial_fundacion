<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte de Campaña</title>

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
    width: 30%;
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
<h1>Reporte de Campaña</h1>
<div>ID Campaña: {{ $campaign->id }}</div>
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

<!-- DATOS CAMPAÑA -->
<h2>Información General</h2>

<table class="table">
<tr><td class="label">Nombre</td><td>{{ $campaign->name }}</td></tr>
<tr><td class="label">Responsable</td><td>{{ $campaign->user->name ?? '-' }}</td></tr>
<tr><td class="label">Descripción</td><td>{{ $campaign->description }}</td></tr>
<tr><td class="label">Fecha inicio</td><td>{{ $campaign->start_date }}</td></tr>
<tr><td class="label">Fecha fin</td><td>{{ $campaign->end_date }}</td></tr>
<tr><td class="label">Cantidad eventos</td><td>{{ $campaign->events->count() }}</td></tr>
</table>

<!-- EVENTOS -->
<h2>Eventos</h2>

@if($campaign->events->count())
<table class="table">
<tr>
<th>Evento</th>
<th>Fecha</th>
<th>Organizador</th>
<th>Ubicaciones</th>
<th>Participantes</th>
</tr>

@foreach($campaign->events as $event)
<tr>
<td>{{ $event->name }}</td>
<td>{{ $event->event_date }}</td>
<td>{{ $event->user->name ?? '-' }}</td>

<td>
@foreach($event->eventLocations as $loc)
- {{ $loc->location_name }} <br>
@endforeach
</td>

<td>
@foreach($event->eventParticipants as $p)
- {{ $p->user->name ?? 'N/A' }} ({{ $p->status }}) <br>
@endforeach
</td>

</tr>
@endforeach
</table>
@else
<p>No tiene eventos registrados.</p>
@endif

<!-- FOOTER -->
<hr>
<div class="small center">
Documento generado automáticamente | {{ $reportData['date'] }} {{ $reportData['time'] }}
</div>

</body>
</html>