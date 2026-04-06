<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Error del sistema</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #4e73df, #1cc88a);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
        }

        .container {
            max-width: 500px;
        }

        h1 {
            font-size: 80px;
            margin: 0;
        }

        p {
            font-size: 18px;
            margin: 20px 0;
        }

        .btn {
            padding: 12px 25px;
            background: white;
            color: #4e73df;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn:hover {
            background: #f1f1f1;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>⚠️</h1>
        <h2>Oops... algo salió mal</h2>

        <p>
            Estamos teniendo problemas para conectarnos al sistema.<br>
            Intenta nuevamente en unos minutos.
        </p>

        <a href="/" class="btn">Volver al inicio</a>
    </div>
</body>
</html>