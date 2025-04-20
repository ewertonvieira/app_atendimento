<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Meus Atendimentos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                {{-- Verificar se há atendimentos --}}
                @if ($atendimentos->isEmpty())
                    <p class="text-gray-500 text-center">Nenhum atendimento encontrado.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($atendimentos as $atendimento)
                            @if ($atendimento->cliente_id === Auth::id())
                                <div class="bg-white shadow-lg rounded-lg p-4 border border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $atendimento->descricao ?? 'Atendimento' }}</h3>
                                    <p class="text-sm text-gray-600">Data: {{ $atendimento->data_disponivel }}</p>
                                    <p class="text-sm text-gray-600">Hora: {{ $atendimento->hora_disponivel }}</p>
                                    <p class="text-sm text-gray-600">Status: <span class="font-semibold">{{ ucfirst($atendimento->status) }}</span></p>
                                    <p class="text-sm text-gray-600">Cliente: {{ $atendimento->cliente->name }}</p>
                                    <p class="text-sm text-gray-600">Endereço: {{ $atendimento->cliente->rua }}, {{ $atendimento->cliente->bairro }}, {{ $atendimento->cliente->cep }}</p>

                                    <div class="mt-4 flex justify-between">
                                        {{-- Botão Visualizar --}}
                                        <button onclick="openModal('viewModal{{ $atendimento->id }}')" class="px-4 py-2 bg-blue-500 text-white rounded-md shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                                            Visualizar
                                        </button>

                                        {{-- Botão Editar --}}
                                        <button onclick="openModal('editModal{{ $atendimento->id }}')" class="px-4 py-2 bg-yellow-500 text-white rounded-md shadow-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2">
                                            Editar
                                        </button>

                                        {{-- Botão Deletar --}}
                                        <form action="{{ route('atendimentos.destroy', $atendimento->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja deletar este atendimento?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md shadow-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2">
                                                Deletar
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                {{-- Modal Visualizar --}}
                                <div id="viewModal{{ $atendimento->id }}" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
                                    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Detalhes do Atendimento</h3>
                                        <p><strong>Descrição:</strong> {{ $atendimento->descricao }}</p>
                                        <p><strong>Data:</strong> {{ $atendimento->data_disponivel }}</p>
                                        <p><strong>Hora:</strong> {{ $atendimento->hora_disponivel }}</p>
                                        <p><strong>Status:</strong> <span class="font-semibold">{{ ucfirst($atendimento->status) }}</span></p>
                                        <p><strong>Cliente:</strong> {{ $atendimento->cliente->name }}</p>
                                        <p><strong>Email:</strong> {{ $atendimento->cliente->email }}</p>
                                        <p><strong>Telefone:</strong> {{ $atendimento->cliente->phone_number }}</p>
                                        <p><strong>CPF:</strong> {{ $atendimento->cliente->cpf }}</p>
                                        <p><strong>Endereço:</strong> {{ $atendimento->cliente->rua }}, {{ $atendimento->cliente->bairro }}, {{ $atendimento->cliente->cep }}, {{ $atendimento->cliente->estado }}</p>
                                        @if ($atendimento->foto)
                                            <p><strong>Foto:</strong></p>
                                            <div class="flex items-center">
                                                <img src="{{ asset('storage/' . $atendimento->foto) }}" alt="Foto do Atendimento" class="w-32 h-32 object-cover rounded-md border border-gray-300">
                                            </div>
                                        @else
                                            <p><strong>Foto:</strong> Nenhuma foto disponível.</p>
                                        @endif

                                        <div class="mt-4 flex justify-end">
                                            <button onclick="closeModal('viewModal{{ $atendimento->id }}')" class="px-4 py-2 bg-gray-500 text-white rounded-md shadow-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                                                Fechar
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Modal Editar --}}
                                <div id="editModal{{ $atendimento->id }}" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
                                    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Editar Atendimento</h3>
                                        <form action="{{ route('atendimentos.update', $atendimento->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            {{-- Status (Somente Leitura) --}}
                                            <div class="mb-4">
                                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                                <input type="text" id="status" value="{{ ucfirst($atendimento->status) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" readonly>
                                            </div>

                                            {{-- Descrição --}}
                                            <div class="mb-4">
                                                <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição</label>
                                                <input type="text" name="descricao" id="descricao" value="{{ $atendimento->descricao }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                            </div>

                                            {{-- Data Disponível --}}
                                            <div class="mb-4">
                                                <label for="data_disponivel" class="block text-sm font-medium text-gray-700">Data</label>
                                                <input type="date" name="data_disponivel" id="data_disponivel" value="{{ $atendimento->data_disponivel }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                            </div>

                                            {{-- Hora Disponível --}}
                                            <div class="mb-4">
                                                <label for="hora_disponivel" class="block text-sm font-medium text-gray-700">Hora</label>
                                                <input type="time" name="hora_disponivel" id="hora_disponivel" value="{{ $atendimento->hora_disponivel }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                            </div>

                                            {{-- Foto --}}
                                            <div class="mb-4">
                                                <label for="foto" class="block text-sm font-medium text-gray-700">Foto (opcional)</label>
                                                <input type="file" name="foto" id="foto{{ $atendimento->id }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" onchange="previewImage(event, 'preview{{ $atendimento->id }}')">
                                                @if ($atendimento->foto)
                                                    <p class="text-sm text-gray-500 mt-2">Foto atual:</p>
                                                    <div class="flex items-center">
                                                        <img src="{{ asset('storage/' . $atendimento->foto) }}" alt="Foto do Atendimento" class="w-32 h-32 object-cover rounded-md border border-gray-300">
                                                    </div>
                                                @endif
                                                <img id="preview{{ $atendimento->id }}" src="#" alt="Pré-visualização da Foto" class="mt-4 hidden w-32 h-32 object-cover rounded-md border border-gray-300">
                                            </div>

                                            <div class="mt-4 flex justify-end">
                                                <button type="button" onclick="closeModal('editModal{{ $atendimento->id }}')" class="px-4 py-2 bg-gray-500 text-white rounded-md shadow-md hover:bg-gray-600">
                                                    Cancelar
                                                </button>
                                                <button type="submit" class="ml-2 px-4 py-2 bg-indigo-500 text-white rounded-md shadow-md hover:bg-indigo-600">
                                                    Salvar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    function previewImage(event, previewId) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById(previewId);
            output.src = reader.result;
            output.classList.remove('hidden');
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
