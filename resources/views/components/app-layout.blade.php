<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Dashboard' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="flex h-screen bg-gray-100 text-gray-900">



    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Bar -->
        <header class="bg-white shadow px-6 py-4 flex items-center justify-between">
            @isset($header)
                {{ $header }}
            @else
                <h1 class="text-lg font-semibold">{{ $title ?? 'Dashboard' }}</h1>
            @endisset
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-6">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>
</html>
