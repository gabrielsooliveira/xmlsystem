@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-12 animate-fade-in-down">
    
    <div class="text-center mb-10">
        <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl mb-4 text-transparent bg-clip-text bg-gradient-to-r from-indigo-300 to-cyan-300">
            Separe suas Notas Fiscais
        </h1>
        <p class="text-lg text-slate-400">
            Faça upload do seu arquivo XML contendo a lista de notas (ListaNfse). Nós vamos extrair cada nota individualmente para você baixar sob demanda.
        </p>
    </div>

    <!-- Upload Card -->
    <div class="glass-panel rounded-2xl shadow-2xl shadow-indigo-500/10 p-8 sm:p-12 relative overflow-hidden group">
        
        <!-- Glow Effect behind card -->
        <div class="absolute -top-24 -left-24 w-48 h-48 bg-indigo-500/30 rounded-full blur-3xl opacity-50 group-hover:opacity-70 transition duration-700"></div>
        <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-cyan-500/20 rounded-full blur-3xl opacity-50 group-hover:opacity-70 transition duration-700"></div>

        <form action="{{ route('nfse.process') }}" method="POST" enctype="multipart/form-data" class="relative z-10">
            @csrf
            
            <div class="mb-8">
                <label class="block text-sm font-medium text-slate-300 mb-2">Arquivo XML</label>
                
                <div class="mt-1 flex justify-center px-6 pt-10 pb-12 border-2 border-slate-600 border-dashed rounded-xl group-hover:border-indigo-400 hover:bg-slate-800/50 transition duration-300 cursor-pointer relative" onclick="document.getElementById('xml_file').click()">
                    
                    <div class="space-y-2 text-center pointer-events-none">
                        <svg class="mx-auto h-16 w-16 text-indigo-400 opacity-80 group-hover:scale-110 transition duration-300 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 48 48" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 14V10a2 2 0 012-2h12l8 8v24a2 2 0 01-2 2H16a2 2 0 01-2-2v-4M22 26l4-4m0 0l4 4m-4-4v12" />
                        </svg>
                        <div class="flex text-sm text-slate-400 justify-center">
                            <span class="relative font-medium text-indigo-400 hover:text-indigo-300 focus-within:outline-none cursor-pointer">
                                <span>Faça upload do arquivo</span>
                            </span>
                            <p class="pl-1">ou arraste para cá</p>
                        </div>
                        <p class="text-xs text-slate-500">
                            Apenas arquivos .XML
                        </p>
                    </div>
                    
                    <input id="xml_file" name="xml_file" type="file" class="sr-only" accept="text/xml,application/xml" required onchange="updateFileName(this)">
                </div>

                <div id="file-name-display" class="mt-4 text-center text-sm font-medium text-cyan-300 hidden">
                    <!-- File name appears here -->
                </div>
                
                @error('xml_file')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-center">
                <button type="submit" class="w-full sm:w-auto relative group inline-flex items-center justify-center px-8 py-3.5 text-base font-bold text-white transition-all duration-200 bg-indigo-600 border border-transparent rounded-xl hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 focus:ring-offset-slate-900 overflow-hidden">
                    <span class="absolute inset-0 w-full h-full -mt-1 rounded-lg opacity-30 bg-gradient-to-b from-transparent via-transparent to-black"></span>
                    <span class="relative flex items-center gap-2">
                        Extrair Notas Fiscais
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateFileName(input) {
        const display = document.getElementById('file-name-display');
        if (input.files && input.files[0]) {
            display.textContent = 'Selecionado: ' + input.files[0].name;
            display.classList.remove('hidden');
        } else {
            display.classList.add('hidden');
        }
    }

    // Add drag and drop visual effects
    const dropZone = document.querySelector('.border-dashed');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults (e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('border-indigo-400', 'bg-slate-800/50');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-indigo-400', 'bg-slate-800/50');
    }

    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        document.getElementById('xml_file').files = files;
        updateFileName(document.getElementById('xml_file'));
    }
</script>
@endsection
