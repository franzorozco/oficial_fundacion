<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Donaciones</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        h1, h2, h3 {
            margin: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 18px;
        }

        .header small {
            font-size: 11px;
            color: #666;
        }

        .audit-box {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 11px;
        }

        .audit-box table {
            width: 100%;
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
            color: #777;
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <h1>Sistema de Gestión de Donaciones</h1>
        <h2>Historial de Donaciones</h2>
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
                <td><strong>Total registros:</strong> {{ $reportData['total'] }}</td>
                <td><strong>Filtros:</strong> {{ json_encode($reportData['filters']) }}</td>
            </tr>
        </table>
    </div>

    {{-- TABLA --}}
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Donante Externo</th>
                <th>Usuario Registrado</th>
                <th>Decidido por</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Notas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($donations as $i => $d)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $d->externalDonor->names ?? '-' }}</td>
                    <td>{{ $d->user->name ?? '-' }}</td>
                    <td>{{ $d->receivedBy->name ?? '-' }}</td>
                    <td>{{ $d->status->name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($d->donation_date)->format('d/m/Y') }}</td>
                    <td>{{ Str::limit($d->notes, 50) ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;">No hay registros</td>
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