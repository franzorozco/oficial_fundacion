<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>FUNDACION UNNIFRANZ</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
        @endif
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] m-0 p-0 overflow-x-hidden">

    <header class="w-full fixed top-0 left-0 z-50 bg-white shadow-md">
        <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">
            
            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                 <!--<img src="/ruta/al/logo.png" alt="Fundación UNIFRANZ" class="h-10">-->
                <span class="text-[#00B2E3] font-bold text-xl">FUNDACIÓN <span class="text-[#EB2C91]">UNIFRANZ</span></span>
            </a>

            <!-- Menú de Navegación -->
            @if (Route::has('login'))
            <nav class="flex items-center gap-4 text-sm font-medium">
                <a href="{{ url('/') }}" class="text-[#1b1b18] hover:text-[#00B2E3] transition">Inicio</a>
                <a href="{{ url('/about') }}" class="text-[#1b1b18] hover:text-[#00B2E3] transition">Sobre Nosotros</a>
                <a href="{{ url('/contact') }}" class="text-[#1b1b18] hover:text-[#00B2E3] transition">Contacto</a>
                <a href="{{ url('/help') }}" class="text-[#1b1b18] hover:text-[#00B2E3] transition">Ayuda</a>

                @auth
                    <a href="{{ url('/dashboard') }}" class="px-3 py-1 border border-[#00B2E3] text-[#00B2E3] rounded hover:bg-[#00B2E3] hover:text-white transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-1 border border-[#00B2E3] text-[#00B2E3] rounded hover:bg-[#00B2E3] hover:text-white transition">Iniciar Sesión</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-3 py-1 border border-[#EB2C91] text-[#EB2C91] rounded hover:bg-[#EB2C91] hover:text-white transition">Registrarse</a>
                    @endif
                @endauth
            </nav>
            @endif
        </div>
    </header>
    
    @yield('contentprin')

    <!--
        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
    -->
    </body>
</html>