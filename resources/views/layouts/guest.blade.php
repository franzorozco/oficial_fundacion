<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background: linear-gradient(to right, #39b5eb, #cf1d86, #db7fb5);
            background-size: 200% 200%;
            animation: gradientShift 10s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-container {
            backdrop-filter: blur(15px);
            background: rgba(255, 255, 255, 0.15);
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.25);
            padding: 2rem;
            color: white;
            transition: all 0.3s ease-in-out;
        }

        .login-container:hover {
            transform: scale(1.01);
            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.3);
        }

        .brand-logo {
            filter: drop-shadow(0 0 5px rgba(0, 0, 0, 0.2));
        }

        input {
            background-color: white !important;
            color: black !important;
            border: 1px solid #ccc;
            padding: 0.5rem;
            border-radius: 0.375rem;
            width: 100%;
            box-sizing: border-box;
        }

        label {
            color: white !important;
        }

        input::placeholder {
            color: #555 !important;
        }

        /* Estilo para reducir el tamaño de la imagen */
        .custom-logo {
            max-width: 50%;
            height: auto;
            display: block;
            margin: 0 auto;
        }
    </style>

</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col justify-center items-center px-4">
        <div>
            <a href="/">
                <x-application-logo class="w-20 h-20 text-white brand-logo" />
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 login-container">
            <img src="https://upload.wikimedia.org/wikipedia/commons/f/f2/LOGO-FUNDACION-UNIFRANZ-2017.png" alt="Logo Fundación UNIFRANZ" class="custom-logo" {{ $attributes }}>

            {{ $slot }}
            
        </div>

    </div>
</body>
</html>
