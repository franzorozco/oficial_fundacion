<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Detalle de Cuenta Financiera</title>
<style>
body { font-family: DejaVu Sans, sans-serif; font-size:12px; color:#333; }
.title { text-align:center; font-size:18px; font-weight:bold; margin-bottom:5px; }
.subtitle { text-align:center; font-size:12px; margin-bottom:15px; }
table { width:100%; border-collapse:collapse; margin-bottom:10px; }
th, td { border:1px solid #ddd; padding:6px; font-size:11px; }
thead { background:#f2f2f2; }
.center { text-align:center; }
.small { font-size:10px; color:#666; }
</style>
</head>
<body>

<div class="title">Detalle de Cuenta Financiera</div>
<div class="subtitle">Cuenta: {{ $account->name }}</div>

<table>
<tr><td><strong>Tipo:</strong> {{ $account->type }}</td>
<td><strong>Balance:</strong> ${{ number_format($account->balance,2) }}</td></tr>
<tr><td colspan="2"><strong>Descripción:</strong> {{ $account->description }}</td></tr>
</table>

<h4>Historial de Transacciones</h4>
<table>
<thead>
<tr>
<th>Fecha</th>
<th>Tipo</th>
<th>Monto</th>
<th>Descripción</th>
</tr>
</thead>
<tbody>
@foreach($account->transactions as $t)
<tr>
<td>{{ $t->transaction_date }}</td>
<td>{{ ucfirst($t->type) }}</td>
<td>${{ number_format($t->amount,2) }}</td>
<td>{{ $t->description }}</td>
</tr>
@endforeach
</tbody>
</table>

<h4>Finanzas por Campaña</h4>
<table>
<thead>
<tr>
<th>Campaña</th>
<th>Manager</th>
<th>Ingresos</th>
<th>Gastos</th>
<th>Balance Neto</th>
</tr>
</thead>
<tbody>
@foreach($account->campaignFinances as $f)
<tr>
<td>{{ optional($f->campaign)->name }}</td>
<td>{{ optional($f->user)->name }}</td>
<td>${{ number_format($f->income,2) }}</td>
<td>${{ number_format($f->expenses,2) }}</td>
<td>${{ number_format($f->net_balance,2) }}</td>
</tr>
@endforeach
</tbody>
</table>

<div class="small" style="text-align:center; margin-top:10px;">
Documento generado automáticamente
</div>

</body>
</html>