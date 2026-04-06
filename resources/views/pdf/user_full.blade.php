<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte de Usuario</title>

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
<h1>Reporte de Usuario</h1>
<div>ID Usuario: {{ $user->id }}</div>
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

<!-- DATOS USUARIO -->
<h2>Información General</h2>

<table class="table">
<tr><td class="label">Nombre</td><td>{{ $user->name }}</td></tr>
<tr><td class="label">Correo</td><td>{{ $user->email }}</td></tr>
<tr><td class="label">Teléfono</td><td>{{ $user->phone ?? '-' }}</td></tr>
<tr><td class="label">Dirección</td><td>{{ $user->address ?? '-' }}</td></tr>
<tr><td class="label">Estado correo</td><td>{{ $user->email_verified_at ? 'Verificado' : 'No verificado' }}</td></tr>
<tr><td class="label">Roles</td><td>{{ $user->roles->pluck('name')->implode(', ') }}</td></tr>
<tr><td class="label">Fecha registro</td><td>{{ $user->created_at?->format('d/m/Y') }}</td></tr>
</table>

<!-- PERFIL -->
<h2>Perfil</h2>

@if($user->profile)
<table class="table">
<tr><td class="label">Documento</td><td>{{ $user->profile->document_number ?? '-' }}</td></tr>
<tr><td class="label">Fecha nacimiento</td><td>{{ $user->profile->birthdate?->format('d/m/Y') ?? '-' }}</td></tr>
<tr><td class="label">Ubicación</td><td>{{ $user->profile->location ?? '-' }}</td></tr>
<tr><td class="label">Idiomas</td><td>{{ $user->profile->languages_spoken ?? '-' }}</td></tr>
<tr><td class="label">Experiencia</td><td>{{ ucfirst($user->profile->experience_level) }}</td></tr>
<tr><td class="label">Condición física</td><td>{{ ucfirst($user->profile->physical_condition) }}</td></tr>
<tr><td class="label">Disponibilidad</td><td>{{ $user->profile->availability_days ?? '-' }}</td></tr>
<tr><td class="label">Horas</td><td>{{ $user->profile->availability_hours ?? '-' }}</td></tr>
<tr><td class="label">Transporte</td><td>{{ $user->profile->transport_available ?? '-' }}</td></tr>
<tr><td class="label">Biografía</td><td>{{ $user->profile->bio ?? '-' }}</td></tr>
</table>
@else
<p>No tiene perfil registrado.</p>
@endif

<!-- EVENTOS -->
<h2>Eventos</h2>

@if($user->eventParticipants->count())
<table class="table">
<tr>
<th>Evento</th>
<th>Ubicación</th>
<th>Estado</th>
<th>Fecha</th>
</tr>

@foreach($user->eventParticipants as $event)
<tr>
<td>{{ $event->event->name ?? '-' }}</td>
<td>{{ $event->eventLocation->name ?? '-' }}</td>
<td>{{ ucfirst($event->status) }}</td>
<td>{{ $event->registration_date?->format('d/m/Y') }}</td>
</tr>
@endforeach
</table>
@else
<p>No participa en eventos.</p>
@endif

<!-- VERIFICACIONES -->
<h2>Verificaciones</h2>

@if($user->volunteerVerifications->count())
<table class="table">
<tr>
<th>Tipo</th>
<th>Documento</th>
<th>Estado</th>
<th>Comentario</th>
</tr>

@foreach($user->volunteerVerifications as $v)
<tr>
<td>{{ $v->document_type }}</td>
<td>{{ $v->name_document }}</td>
<td>{{ ucfirst($v->status) }}</td>
<td>{{ $v->coment ?? '-' }}</td>
</tr>
@endforeach
</table>
@else
<p>No tiene verificaciones.</p>
@endif

<!-- FOOTER -->
<hr>
<div class="small center">
Documento generado automáticamente | {{ $reportData['date'] }} {{ $reportData['time'] }}
</div>

</body>
</html>