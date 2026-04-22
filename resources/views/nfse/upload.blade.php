@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto mt-4 animate-fade-in-down">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold tracking-tight text-sky-900 sm:text-5xl mb-4">
                Separe suas Notas Fiscais
            </h1>
            <p class="text-lg text-sky-600 max-w-2xl mx-auto">
                Faça upload do seu arquivo XML contendo a lista de notas (ListaNfse). Nós vamos extrair cada nota
                individualmente para você baixar sob demanda.
            </p>
        </div>

        <!-- Upload Card -->
        <div
            class="bg-white rounded-3xl shadow-xl shadow-sky-200/50 p-8 sm:p-12 relative overflow-hidden group border border-sky-100">

            <div
                class="absolute -top-24 -left-24 w-48 h-20 bg-sky-50 rounded-full blur-3xl opacity-50 group-hover:opacity-100 transition duration-700">
            </div>

            <form action="{{ route('nfse.process') }}" method="POST" enctype="multipart/form-data" class="relative z-10">
                @csrf

                <div class="mb-10">
                    <label class="block text-sm font-semibold text-sky-900 mb-3">Arquivo XML da Lista de Notas</label>

                    <div class="mt-1 flex justify-center px-6 pt-10 pb-12 border-2 border-sky-200 border-dashed rounded-2xl hover:border-sky-400 hover:bg-sky-50/50 transition-all duration-300 cursor-pointer relative"
                        onclick="document.getElementById('xml_file').click()">

                        <div class="space-y-4 text-center pointer-events-none">
                            <div
                                class="bg-sky-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto group-hover:scale-110 transition duration-300">
                                <svg class="h-8 w-8 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 48 48"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 14V10a2 2 0 012-2h12l8 8v24a2 2 0 01-2 2H16a2 2 0 01-2-2v-4M22 26l4-4m0 0l4 4m-4-4v12" />
                                </svg>
                            </div>
                            <div class="flex text-sm text-sky-700 justify-center">
                                <span
                                    class="relative font-bold text-sky-600 hover:text-sky-700 focus-within:outline-none cursor-pointer">
                                    <span>Faça upload do arquivo</span>
                                </span>
                                <p class="pl-1 text-sky-500">ou arraste para cá</p>
                            </div>
                            <p class="text-xs text-sky-400 font-medium uppercase tracking-wider">
                                Apenas arquivos .XML
                            </p>
                        </div>

                        <input id="xml_file" name="xml_file" type="file" class="sr-only" accept="text/xml,application/xml"
                            required onchange="updateFileName(this)">
                    </div>

                    <div id="file-name-display"
                        class="mt-6 p-3 bg-sky-50 rounded-lg text-center text-sm font-bold text-sky-700 border border-sky-100 hidden">
                        <!-- File name appears here -->
                    </div>

                    @error('xml_file')
                        <p class="mt-3 text-sm text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-center">
                    <button type="submit"
                        class="w-full sm:w-auto relative group inline-flex items-center justify-center px-10 py-4 text-lg font-bold text-white transition-all duration-300 bg-sky-600 rounded-2xl hover:bg-sky-700 hover:shadow-lg hover:shadow-sky-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-600 overflow-hidden">
                        <span class="relative flex items-center gap-3">
                            Extrair Notas Fiscais
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
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

        function preventDefaults(e) {
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
            dropZone.classList.add('border-sky-400', 'bg-blue-800/50');
        }

        function unhighlight(e) {
            dropZone.classList.remove('border-sky-400', 'bg-blue-800/50');
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