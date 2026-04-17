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

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0f172a;
            color: #f8fafc;
            background-image: radial-gradient(circle at top right, rgba(79, 70, 229, 0.15), transparent 40%), radial-gradient(circle at bottom left, rgba(56, 189, 248, 0.1), transparent 40%);
            background-attachment: fixed;
        }

        .animate-fade-in-down {
            animation: fadeInDown 0.4s ease-out forwards;
        }

        .glass-panel {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="antialiased min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="glass-panel sticky top-0 z-50 border-b border-slate-700/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex-shrink-0 flex items-center space-x-3">
                    <!-- Logo / Icon -->
                    <div
                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-cyan-400 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <span
                        class="font-bold text-xl tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 to-cyan-300">
                        NFSe Extractor
                    </span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Flash Messages -->
        @if (session('error'))
            <div
                class="mb-8 p-4 rounded-xl glass-panel border-red-500/30 bg-red-500/10 flex items-start animate-fade-in-down">
                <svg class="w-6 h-6 text-red-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-red-400 font-medium">Erro ao processar</h3>
                    <p class="text-red-200/80 text-sm mt-1">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div
                class="mb-8 p-4 rounded-xl glass-panel border-green-500/30 bg-green-500/10 flex items-start animate-fade-in-down">
                <svg class="w-6 h-6 text-green-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-green-400 font-medium">Sucesso</h3>
                    <p class="text-green-200/80 text-sm mt-1">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto py-6 text-center text-slate-500 text-sm border-t border-slate-800/50">
        <p>&copy; {{ date('Y') }} XML System. Todos os direitos reservados.</p>
    </footer>
</body>

</html>