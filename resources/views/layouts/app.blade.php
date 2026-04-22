<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NFSe Extractor</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased min-h-screen flex flex-col bg-sky-50 font-sans">
    <header class="bg-sky-900 border-b border-sky-800 sticky top-0 z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-4">
                    <div class="bg-white/10 p-2 rounded-lg backdrop-blur-sm border border-white/10">
                        <img src="{{ asset('images/logo_prefeitura.png') }}" alt="Logo Prefeitura" class="h-10 w-auto">
                    </div>
                    <div class="h-8 w-px bg-sky-700 hidden sm:block"></div>
                    <span class="text-white font-bold text-xl tracking-tight hidden sm:block">Separador de XML de Notas
                        Fiscais Salvador</span>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if (session('error'))
            <div
                class="mb-8 p-4 rounded-xl glass-panel border-red-200 bg-red-50 flex items-start animate-fade-in-down shadow-sm">
                <svg class="w-6 h-6 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-red-800 font-semibold">Erro ao processar</h3>
                    <p class="text-red-700 text-sm mt-1">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div
                class="mb-8 p-4 rounded-xl glass-panel border-emerald-200 bg-emerald-50 flex items-start animate-fade-in-down shadow-sm">
                <svg class="w-6 h-6 text-emerald-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-emerald-800 font-semibold">Sucesso</h3>
                    <p class="text-emerald-700 text-sm mt-1">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

</body>

</html>