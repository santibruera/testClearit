<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
   @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles {{-- Importa los estilos de Livewire --}}
 
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-center text-gray-900 mb-6">Iniciar Sesión</h2>

        {{-- Aquí se inserta el componente Livewire --}}
        @livewire('auth.login')
    </div>

    @livewireScripts {{-- Importa los scripts de Livewire --}}
</body>
</html>