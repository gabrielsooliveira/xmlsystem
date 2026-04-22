@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto mt-4 animate-fade-in-down">

        <div class="mb-6">
            <div>
                <h1 class="text-3xl font-extrabold text-sky-900 mb-2">Notas Encontradas</h1>
                <p class="text-sky-600 font-medium">Selecione quais notas deseja extrair do arquivo: <span
                        class="text-sky-700 font-bold break-all bg-sky-100 px-2 py-0.5 rounded">{{ request()->file('xml_file')->getClientOriginalName() ?? 'XML' }}</span>
                </p>
            </div>

            <div class="flex gap-4 mt-5">
                <a href="{{ route('nfse.index') }}"
                    class="px-5 py-2.5 rounded-xl bg-white text-sky-600 hover:bg-sky-50 transition-all border border-sky-200 text-sm font-bold shadow-sm">
                    Cancelar
                </a>

                <button type="button" onclick="document.getElementById('downloadForm').submit()"
                    class="px-6 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white transition-all shadow-lg shadow-sky-200 border border-sky-500 text-sm font-bold flex items-center gap-2 group">
                    <svg class="w-5 h-5 group-hover:-translate-y-1 transition-transform" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Baixar Selecionadas (.zip)
                </button>
            </div>
        </div>

        <!-- The Box -->
        <div class="bg-white overflow-hidden rounded-3xl shadow-xl shadow-sky-200/40 border border-sky-100">

            <!-- Controls -->
            <div class="bg-sky-50/50 px-8 py-5 flex justify-between items-center border-b border-sky-100">
                <div class="flex items-center">
                    <input id="selectAll" type="checkbox"
                        class="w-6 h-6 rounded-lg border-sky-300 text-sky-600 focus:ring-sky-500/50 bg-white cursor-pointer transition-all"
                        onclick="toggleSelectAll(this)">
                    <label for="selectAll" class="ml-4 text-sm font-bold text-sky-900 cursor-pointer select-none">Selecionar
                        Todas</label>
                </div>
                <div class="text-sm font-bold">
                    <span class="text-sky-600 bg-sky-100 px-3 py-1 rounded-full" id="selectedCount">0</span>
                    <span class="text-sky-400 ml-1"> / {{ count($notasEncontradas) }}</span>
                </div>
            </div>

            <form id="downloadForm" action="{{ route('nfse.download') }}" method="POST">
                @csrf
                <input type="hidden" name="filename" value="{{ $filename }}">

                <div class="max-h-[60vh] overflow-y-auto custom-scrollbar p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($notasEncontradas as $nota)
                            <label
                                class="relative flex items-center p-5 cursor-pointer rounded-2xl border border-sky-50 hover:bg-sky-50/50 hover:border-sky-200 transition-all select-none group has-[:checked]:bg-sky-100/40 has-[:checked]:border-sky-300 block bg-slate-50/30">
                                <div class="flex items-center h-6">
                                    <input name="selected_indices[]" value="{{ $nota['index'] }}" type="checkbox"
                                        class="nota-checkbox w-6 h-6 rounded-lg border-sky-300 text-sky-600 focus:ring-sky-500/50 bg-white cursor-pointer transition-all"
                                        onchange="updateSelectedCount()">
                                </div>
                                <div class="ml-4 flex flex-col">
                                    <span class="font-bold text-sky-900 group-hover:text-sky-700 transition-colors">
                                        Nota: <span class="text-sky-600 font-black">{{ $nota['numero'] }}</span>
                                    </span>
                                    <span class="text-xs text-sky-400 mt-1 font-medium uppercase tracking-wider">
                                        Cód: <span class="font-mono text-sky-500">{{ $nota['codigo'] }}</span>
                                    </span>
                                </div>

                                <!-- Check icon indicator -->
                                <div
                                    class="absolute right-5 text-sky-500 opacity-0 transition-all scale-75 group-has-[:checked]:opacity-100 group-has-[:checked]:scale-100">
                                    <div class="bg-sky-100 p-1.5 rounded-full">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f0f9ff;
            /* sky-50 */
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #bae6fd;
            /* sky-200 */
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #7dd3fc;
            /* sky-300 */
        }
    </style>

    <script>
        function toggleSelectAll(source) {
            const checkboxes = document.querySelectorAll('.nota-checkbox');
            checkboxes.forEach(cb => cb.checked = source.checked);
            updateSelectedCount();
        }

        function updateSelectedCount() {
            const checkboxes = document.querySelectorAll('.nota-checkbox:checked');
            document.getElementById('selectedCount').innerText = checkboxes.length;

            // Auto check/uncheck the "selectAll" checkbox based on reality
            const total = document.querySelectorAll('.nota-checkbox').length;
            document.getElementById('selectAll').checked = (checkboxes.length === total && total > 0);
        }
    </script>
@endsection