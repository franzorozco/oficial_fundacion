<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte de Donantes</title>

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

{{-- HEADER --}}
<div class="header">
    <h2>Sistema de Gestión de Donaciones</h2>
    <h3>Listado de Donantes</h3>
    <small>Generado el {{ $reportData['date'] }} a las {{ $reportData['time'] }}</small>
</div>

{{-- AUDITORÍA --}}
<div class="audit-box">
    <table>
        <tr>
            <td><strong>Generado por:</strong> {{ $reportData['generated_by'] }}</td>
            <td><strong>Email:</strong> {{ $reportData['generated_email'] }}</td>
        </tr>
        <tr>
            <td><strong>IP:</strong> {{ $reportData['ip'] }}</td>
            <td><strong>Navegador:</strong> {{ $reportData['user_agent'] }}</td>
        </tr>
        <tr>
            <td><strong>Total:</strong> {{ $reportData['total'] }}</td>
            <td><strong>Filtros:</strong> {{ json_encode($reportData['filters']) }}</td>
        </tr>
    </table>
</div>

{{-- TABLA --}}
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Dirección</th>
            <th>Rol</th>
            <th>Estado</th>
            <th>Registro</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $i => $user)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->phone ?? '-' }}</td>
            <td>{{ $user->address ?? '-' }}</td>
            <td>
                {{ $user->roles->pluck('name')->join(', ') }}
            </td>
            <td>
                {{ $user->deleted_at ? 'Eliminado' : 'Activo' }}
            </td>
            <td>
                {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" style="text-align:center;">No hay registros</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- FOOTER --}}
<div class="footer">
    Documento generado automáticamente | {{ now()->format('d/m/Y H:i:s') }}
</div>

</body>
</html>