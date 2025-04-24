<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Atendimento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                
            <form action="{{ route('admin.atendimentos.update', $atendimento->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Cliente (Somente Leitura) -->
                        <div>
                            <label for="cliente_name" class="block text-sm font-medium text-gray-700">Cliente</label>
                            <input type="text" id="cliente_name" value="{{ $atendimento->cliente->name }}" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm bg-gray-100" readonly>
                        </div>

                        <!-- Técnico -->
                        <div>
                            <label for="tecnico_id" class="block text-sm font-medium text-gray-700">Técnico</label>
                            <select id="tecnico_id" name="tecnico_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" onchange="updateStatus()">
                                <option value="">Selecione um técnico</option>
                                @foreach ($tecnicos as $tecnico)
                                    <option value="{{ $tecnico->id }}" {{ $atendimento->tecnico_id == $tecnico->id ? 'selected' : '' }}>
                                        {{ $tecnico->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Descrição -->
                        <div class="col-span-2">
                            <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição</label>
                            <textarea id="descricao" name="descricao" rows="3" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">{{ $atendimento->descricao }}</textarea>
                        </div>

                        <!-- Data Disponível -->
                        <div>
                            <label for="data_disponivel" class="block text-sm font-medium text-gray-700">Data Disponível</label>
                            <input type="date" id="data_disponivel" name="data_disponivel" value="{{ $atendimento->data_disponivel }}" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                        </div>

                        <!-- Hora Disponível -->
                        <div>
                            <label for="hora_disponivel" class="block text-sm font-medium text-gray-700">Hora Disponível</label>
                            <input type="time" id="hora_disponivel" name="hora_disponivel" value="{{ $atendimento->hora_disponivel }}" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <input type="text" id="status" name="status" value="{{ $atendimento->status }}" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" readonly>
                        </div>

                        <!-- Foto -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                            <!-- Pré-visualização da Foto -->
                            @if ($atendimento->foto)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Foto Atual</label>
                                    <img src="{{ asset('storage/' . $atendimento->foto) }}" alt="Foto do Atendimento" class="w-32 h-32 object-cover border-2 border-blue-500 rounded-md">
                                </div>
                            @endif

                            <!-- Campo de Upload -->
                            <div>
                                <label for="foto" class="block text-sm font-medium text-gray-700">Nova Foto</label>
                                <input type="file" id="foto" name="foto" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>

                        <!-- Feedback do Cliente -->
                        <div class="col-span-2" id="feedback-container" style="display: none;">
                            <label for="feedback_cliente" class="block text-sm font-medium text-gray-700">Feedback do Cliente</label>
                            <textarea id="feedback_cliente" name="feedback_cliente" rows="3" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">{{ $atendimento->feedback_cliente }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <!-- Botão Voltar -->
                        <a href="{{ route('admin.atendimentos.index') }}" 
                           class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                            Voltar
                        </a>

                        <!-- Botão Salvar -->
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateStatus() {
            const tecnicoSelect = document.getElementById('tecnico_id');
            const statusInput = document.getElementById('status');
            const feedbackContainer = document.getElementById('feedback-container');

            if (tecnicoSelect.value) {
                // Se um técnico for selecionado, o status será "Agendado"
                statusInput.value = 'agendado';
                statusInput.classList.add('bg-yellow-100', 'border-yellow-500'); // Adiciona destaque ao campo
            } else {
                // Se nenhum técnico for selecionado, o status será "Pendente"
                statusInput.value = 'pendente';
                statusInput.classList.remove('bg-yellow-100', 'border-yellow-500'); // Remove o destaque
            }

            // Mostrar ou ocultar o campo de feedback com base no status
            if (statusInput.value === 'Concluído') {
                feedbackContainer.style.display = 'block';
            } else {
                feedbackContainer.style.display = 'none';
            }
        }

        // Inicializar a visibilidade do campo de feedback ao carregar a página
        document.addEventListener('DOMContentLoaded', () => {
            updateStatus();
        });
    </script>
</x-app-layout>