<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Usuarios</title>

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

        th {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 11px;
            text-align: left;
        }

        td {
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

    <!-- 🔥 HEADER -->
    <table width="100%" style="margin-bottom: 10px;">
        <tr>
            <td style="width: 60%;">
                <div style="font-size: 18px; font-weight: bold;">
                    Sistema de Gestión
                </div>
                <div style="font-size: 12px;">
                    Reporte de Usuarios
                </div>
            </td>

            <td style="width: 40%; text-align: right; font-size: 11px;">
                <div><strong>Fecha:</strong> {{ $reportData['date'] }}</div>
                <div><strong>Hora:</strong> {{ $reportData['time'] }}</div>
            </td>
        </tr>
    </table>

    <!-- 🔥 BLOQUE DE AUDITORÍA -->
    <div class="box">
        <strong>Información del Reporte</strong>

        <table width="100%" style="margin-top: 8px;">
            <tr>
                <td><strong>Generado por:</strong> {{ $reportData['generated_by'] }}</td>
                <td><strong>Correo:</strong> {{ $reportData['generated_email'] }}</td>
            </tr>
            <tr>
                <td><strong>Dirección IP:</strong> {{ $reportData['ip'] }}</td>
                <td><strong>Total de usuarios:</strong> {{ $reportData['total_users'] }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    <strong>Navegador / Dispositivo:</strong> {{ $reportData['user_agent'] }}
                </td>
            </tr>
        </table>
    </div>

    <!-- 🔥 TÍTULO -->
    <div class="title">Listado de Usuarios</div>
    <div class="subtitle">
        Generado el: {{ now()->format('d/m/Y H:i') }}
    </div>

    <!-- 🔥 TABLA -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Ciudad</th>
                <th>Documento</th>
                <th>Rol</th>
                <th>Registro</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $i => $user)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone ?? '-' }}</td>
                    <td>{{ $user->address ?? '-' }}</td>
                    <td>{{ $user->profile->document_number ?? '-' }}</td>
                    <td>
                        {{ $user->roles->pluck('name')->implode(', ') ?: 'Sin rol' }}
                    </td>
                    <td class="center">
                        {{ $user->created_at?->format('d/m/Y') ?? '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- 🔥 FOOTER -->
    <hr>

    <div class="small" style="text-align: center;">
        Documento generado automáticamente por el sistema |
        {{ $reportData['date'] }} {{ $reportData['time'] }}
    </div>

</body>
</html>