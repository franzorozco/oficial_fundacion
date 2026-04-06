<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Detalle de Donación</title>

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

.section {
    margin-bottom: 15px;
}

.section-title {
    font-weight: bold;
    margin-bottom: 5px;
    background: #f2f2f2;
    padding: 5px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

td {
    border: 1px solid #ddd;
    padding: 6px;
}

th {
    border: 1px solid #ddd;
    padding: 6px;
    background: #f2f2f2;
}

tr:nth-child(even) {
    background: #fafafa;
}

.footer {
    margin-top: 15px;
    text-align: center;
    font-size: 10px;
    color: #777;
}
</style>
</head>

<body>

<div class="header">
    <h2>Sistema de Gestión de Donaciones</h2>
    <h3>Detalle de Donación #{{ $donation->id }}</h3>
</div>

{{-- AUDITORÍA --}}
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
            <td><strong>IP:</strong> {{ $reportData['ip'] }}</td>
            <td><strong>Navegador:</strong> {{ $reportData['user_agent'] }}</td>
        </tr>
    </table>
</div>

{{-- INFORMACIÓN GENERAL --}}
<div class="section">
    <div class="section-title">Información General</div>
    <table>
        <tr>
            <td><strong>Donante externo</strong></td>
            <td>{{ $donation->externalDonor->names ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Usuario registrado</strong></td>
            <td>{{ $donation->user->name ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Recibido por</strong></td>
            <td>{{ $donation->receivedBy->name ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Campaña</strong></td>
            <td>{{ $donation->campaign->name ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Estado</strong></td>
            <td>{{ $donation->status->name ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Fecha</strong></td>
            <td>{{ \Carbon\Carbon::parse($donation->donation_date)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td><strong>Notas</strong></td>
            <td>{{ $donation->notes ?? '-' }}</td>
        </tr>
    </table>
</div>

{{-- ITEMS --}}
<div class="section">
    <div class="section-title">Ítems Donados</div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Unidad</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($donation->items as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->unit }}</td>
                    <td>{{ $item->description ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;">Sin ítems</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="footer">
    Documento generado automáticamente | {{ now()->format('d/m/Y H:i:s') }}
</div>

</body>
</html>