@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-6 animate-fade-in-down">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-8 space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Notas Encontradas</h1>
            <p class="text-slate-400">Selecione quais notas deseja extrair do arquivo <span class="text-indigo-300 font-medium break-all">{{ request()->file('xml_file')->getClientOriginalName() ?? 'XML' }}</span></p>
        </div>
        
        <div class="flex gap-3">
            <a href="{{ route('nfse.index') }}" class="px-4 py-2 rounded-lg bg-slate-800 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors border border-slate-600 text-sm font-medium">
                Cancelar
            </a>
            <!-- Trigger button outside form -->
            <button type="button" onclick="document.getElementById('downloadForm').submit()" class="px-5 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white transition-all shadow-lg shadow-indigo-500/20 border border-indigo-400/50 text-sm font-bold flex items-center gap-2 group">
                <svg class="w-4 h-4 group-hover:-translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Baixar Selecionadas (.zip)
            </button>
        </div>
    </div>

    <!-- The Box -->
    <div class="glass-panel overflow-hidden rounded-2xl shadow-xl border border-slate-700/60">
        
        <!-- Controls -->
        <div class="bg-slate-800/80 px-6 py-4 flex justify-between items-center border-b border-slate-700/60">
            <div class="flex items-center">
                <input id="selectAll" type="checkbox" class="w-5 h-5 rounded border-slate-500 text-indigo-500 focus:ring-indigo-500/50 bg-slate-900 cursor-pointer" onclick="toggleSelectAll(this)">
                <label for="selectAll" class="ml-3 text-sm font-medium text-slate-300 cursor-pointer select-none">Selecionar Todas</label>
            </div>
            <div class="text-sm font-medium">
                <span class="text-indigo-400 pl-2 rounded-md" id="selectedCount">0</span>
                <span class="text-slate-500"> / {{ count($notasEncontradas) }}</span>
            </div>
        </div>

        <form id="downloadForm" action="{{ route('nfse.download') }}" method="POST">
            @csrf
            <input type="hidden" name="filename" value="{{ $filename }}">
            
            <div class="max-h-[60vh] overflow-y-auto custom-scrollbar p-2">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($notasEncontradas as $nota)
                        <label class="relative flex items-center p-4 cursor-pointer rounded-xl border border-slate-700/50 hover:bg-slate-800/40 hover:border-indigo-500/30 transition-all select-none group has-[:checked]:bg-indigo-500/10 has-[:checked]:border-indigo-500/50 block">
                            <div class="flex items-center h-5">
                                <input name="selected_indices[]" value="{{ $nota['index'] }}" type="checkbox" class="nota-checkbox w-5 h-5 rounded border-slate-500 text-indigo-500 focus:ring-indigo-500/50 bg-slate-900 cursor-pointer" onchange="updateSelectedCount()">
                            </div>
                            <div class="ml-4 flex flex-col">
                                <span class="font-medium text-slate-200 group-hover:text-white">
                                    Nota:  <span class="text-cyan-300">{{ $nota['numero'] }}</span>
                                </span>
                                <span class="text-xs text-slate-500 mt-0.5">
                                    Cod. Verificação: <span class="font-mono text-slate-400">{{ $nota['codigo'] }}</span>
                                </span>
                            </div>
                            
                            <!-- Check icon indicator -->
                            <div class="absolute right-4 text-indigo-400 opacity-0 transition-opacity group-has-[:checked]:opacity-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
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
  width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: rgba(30, 41, 59, 0.5); 
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(79, 70, 229, 0.5); 
  border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: rgba(79, 70, 229, 0.8); 
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
