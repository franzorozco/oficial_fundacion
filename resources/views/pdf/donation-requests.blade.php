<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Solicitudes de Donación</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            font-size: 12px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f2f2f2;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            font-size: 11px;
        }

        tr:nth-child(even) {
            background: #fafafa;
        }

        .center {
            text-align: center;
        }

        .small {
            font-size: 10px;
            color: #666;
        }

        .box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<!-- HEADER -->
<table width="100%" style="margin-bottom: 10px;">
    <tr>
        <td style="width: 60%;">
            <div style="font-size: 18px; font-weight: bold;">
                Sistema de Gestión
            </div>
            <div style="font-size: 12px;">
                Reporte de Solicitudes de Donación
            </div>
        </td>

        <td style="width: 40%; text-align: right; font-size: 11px;">
            <div><strong>Fecha:</strong> {{ $reportData['date'] }}</div>
            <div><strong>Hora:</strong> {{ $reportData['time'] }}</div>
        </td>
    </tr>
</table>

<!-- AUDITORÍA -->
<div class="box">
    <strong>Información del Reporte</strong>

    <table width="100%" style="margin-top: 8px;">
        <tr>
            <td><strong>Generado por:</strong> {{ $reportData['generated_by'] }}</td>
            <td><strong>Correo:</strong> {{ $reportData['generated_email'] }}</td>
        </tr>
        <tr>
            <td><strong>IP:</strong> {{ $reportData['ip'] }}</td>
            <td><strong>Total registros:</strong> {{ $reportData['total'] }}</td>
        </tr>
        <tr>
            <td colspan="2">
                <strong>Navegador:</strong> {{ $reportData['user_agent'] }}
            </td>
        </tr>
    </table>
</div>

<!-- TÍTULO -->
<div class="title">Listado de Solicitudes de Donación</div>
<div class="subtitle">
    Generado el: {{ now()->format('d/m/Y H:i') }}
</div>

<!-- TABLA -->
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Referencia</th>
            <th>Solicitante</th>
            <th>Encargado</th>
            <th>Donación</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Notas</th>
        </tr>
    </thead>
    <tbody>
        @foreach($donationRequests as $i => $req)
            <tr>
                <td class="center">{{ $i + 1 }}</td>
                <td>{{ $req->referencia ?? '-' }}</td>
                <td>{{ $req->applicantUser->name ?? '-' }}</td>
                <td>{{ $req->userInCharge->name ?? '-' }}</td>
                <td>{{ $req->donation_id ?? '-' }}</td>
                <td class="center">
                    {{ \Carbon\Carbon::parse($req->request_date)->format('d/m/Y') ?? '-' }}
                </td>
                <td class="center">{{ ucfirst($req->state) }}</td>
                <td>{{ \Illuminate\Support\Str::limit($req->notes, 50) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<hr>

<div class="small" style="text-align: center;">
    Documento generado automáticamente |
    {{ $reportData['date'] }} {{ $reportData['time'] }}
</div>

</body>
</html>