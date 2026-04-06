<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">

<style>
body { font-family: DejaVu Sans; font-size: 12px; color:#333; }

h1 { font-size:18px; margin-bottom:5px; }
h2 { font-size:14px; margin-top:20px; border-bottom:1px solid #ddd; }

.table { width:100%; border-collapse:collapse; margin-top:10px; }
.table td, .table th { border:1px solid #ddd; padding:6px; font-size:11px; }

.label { font-weight:bold; width:30%; background:#f5f5f5; }
.box { border:1px solid #ddd; padding:10px; margin-bottom:15px; }

.small { font-size:10px; color:#666; }
.center { text-align:center; }
</style>
</head>

<body>

<!-- HEADER -->
<table width="100%">
<tr>
<td>
<h1>Reporte de Donación</h1>
<div>ID Donación: {{ $donation->id }}</div>
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
<tr><td>Generado por</td><td>{{ $reportData['generated_by'] }}</td></tr>
<tr><td>Correo</td><td>{{ $reportData['generated_email'] }}</td></tr>
<tr><td>IP</td><td>{{ $reportData['ip'] }}</td></tr>
<tr><td>Dispositivo</td><td>{{ $reportData['user_agent'] }}</td></tr>
</table>
</div>

<!-- DATOS -->
<h2>Información General</h2>

<table class="table">
<tr><td class="label">Donante</td><td>{{ $donation->externalDonor->names ?? $donation->user->name ?? '-' }}</td></tr>
<tr><td class="label">Recibido por</td><td>{{ $donation->receivedBy->name ?? '-' }}</td></tr>
<tr><td class="label">Campaña</td><td>{{ $donation->campaign->name ?? '-' }}</td></tr>
<tr><td class="label">Fecha</td><td>{{ $donation->donation_date }}</td></tr>
<tr><td class="label">Estado</td><td>{{ $donation->status->name ?? '-' }}</td></tr>
<tr><td class="label">Notas</td><td>{{ $donation->notes ?? '-' }}</td></tr>
</table>

<!-- ITEMS -->
<h2>Items Donados</h2>

@if($donation->items->count())
<table class="table">
<tr>
<th>Item</th>
<th>Cantidad</th>
<th>Unidad</th>
<th>Descripción</th>
</tr>

@foreach($donation->items as $item)
<tr>
<td>{{ $item->item_name }}</td>
<td>{{ $item->quantity }}</td>
<td>{{ $item->unit }}</td>
<td>{{ $item->description }}</td>
</tr>
@endforeach

</table>
@else
<p>No hay items registrados.</p>
@endif

<hr>
<div class="small center">
Documento generado automáticamente | {{ $reportData['date'] }} {{ $reportData['time'] }}
</div>

</body>
</html>