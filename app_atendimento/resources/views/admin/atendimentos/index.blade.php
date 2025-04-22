<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lista de Atendimentos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                {{-- Verificar se há atendimentos --}}
                @if ($atendimentos->isEmpty())
                    <p class="text-gray-500 text-center">Nenhum atendimento encontrado.</p>
                @else
                    <form id="delete-multiple-form" action="{{ route('admin.atendimentos.bulk-delete') }}" method="POST">
                        @csrf
                        @method('DELETE')

                        {{-- Botão de exclusão múltipla --}}
                        <div class="mb-4">
                            <button id="delete-selected-btn" type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 hidden" onclick="return confirm('Tem certeza que deseja excluir os itens selecionados?')">
                                Excluir Selecionados
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse border border-gray-200">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-600">
                                            <input type="checkbox" id="select-all" class="form-checkbox h-4 w-4 text-indigo-600">
                                        </th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-600">ID</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-600">Cliente</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-600">Técnico</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-600">Descrição</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-600">Data Disponível</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-600">Hora Disponível</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-600">Status</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-600">Data Agendada</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-600">Valor Comissão</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-600">Prioridade</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-600">Feedback</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-600">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($atendimentos as $atendimento)
                                        <tr class="bg-white hover:bg-gray-50">
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-600">
                                                <input type="checkbox" name="ids[]" value="{{ $atendimento->id }}" class="form-checkbox h-4 w-4 text-indigo-600 item-checkbox">
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-600">{{ $atendimento->id }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-600">{{ $atendimento->cliente->name ?? 'N/A' }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-600">{{ $atendimento->tecnico->name ?? 'N/A' }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-600">{{ $atendimento->descricao }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-600">{{ $atendimento->data_disponivel }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-600">{{ $atendimento->hora_disponivel }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-600">{{ ucfirst($atendimento->status) }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-600">{{ $atendimento->data_agendada ?? 'N/A' }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-600">R$ {{ number_format($atendimento->valor_comissao, 2, ',', '.') ?? 'N/A' }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-600">{{ $atendimento->prioridade ?? 'N/A' }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-600">{{ $atendimento->feedback_cliente ?? 'N/A' }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-600">
                                                <div class="flex flex-col space-y-1">
                                                    <button type="button" onclick="openModal('viewModal{{ $atendimento->id }}')" class="text-blue-500 hover:underline">Ver</button>
                                                    <button type="button" onclick="openModal('editModal{{ $atendimento->id }}')" class="text-yellow-500 hover:underline">Editar</button>
                                                    <form action="{{ route('admin.atendimentos.destroy', $atendimento->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja deletar este atendimento?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-500 hover:underline">Excluir</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>

                                        {{-- Modal de Visualização --}}
                                        <div id="viewModal{{ $atendimento->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto">
                                            <div class="flex items-center justify-center min-h-screen">
                                                <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-2xl">
                                                    <h2 class="text-xl font-semibold mb-4">Detalhes do Atendimento</h2>
                                                    <p><strong>ID:</strong> {{ $atendimento->id }}</p>
                                                    <p><strong>Cliente:</strong> {{ $atendimento->cliente->name ?? 'N/A' }}</p>
                                                    <p><strong>Técnico:</strong> {{ $atendimento->tecnico->name ?? 'N/A' }}</p>
                                                    <p><strong>Descrição:</strong> {{ $atendimento->descricao }}</p>
                                                    <p><strong>Data Disponível:</strong> {{ $atendimento->data_disponivel }}</p>
                                                    <p><strong>Hora Disponível:</strong> {{ $atendimento->hora_disponivel }}</p>
                                                    <p><strong>Status:</strong> {{ ucfirst($atendimento->status) }}</p>
                                                    <p><strong>Data Agendada:</strong> {{ $atendimento->data_agendada ?? 'N/A' }}</p>
                                                    <p><strong>Valor Comissão:</strong> R$ {{ number_format($atendimento->valor_comissao, 2, ',', '.') ?? 'N/A' }}</p>
                                                    <p><strong>Prioridade:</strong> {{ $atendimento->prioridade ?? 'N/A' }}</p>
                                                    <p><strong>Feedback:</strong> {{ $atendimento->feedback_cliente ?? 'N/A' }}</p>
                                                    <button type="button" onclick="closeModal('viewModal{{ $atendimento->id }}')" class="mt-4 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Fechar</button>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Modal de Edição --}}
                                        <div id="editModal{{ $atendimento->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto">
                                            <div class="flex items-center justify-center min-h-screen">
                                                <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-2xl">
                                                    <h2 class="text-xl font-semibold mb-4">Editar Atendimento</h2>
                                                    <form action="{{ route('admin.atendimentos.update', $atendimento->id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')

                                                        {{-- Cliente --}}
                                                        <div class="mb-4">
                                                            <label for="cliente_id" class="block text-sm font-medium text-gray-700">Cliente</label>
                                                            <select name="cliente_id" id="cliente_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                                @foreach ($clientes as $cliente)
                                                                    <option value="{{ $cliente->id }}" {{ $atendimento->cliente_id == $cliente->id ? 'selected' : '' }}>
                                                                        {{ $cliente->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        {{-- Técnico --}}
                                                        <div class="mb-4">
                                                            <label for="tecnico_id" class="block text-sm font-medium text-gray-700">Técnico</label>
                                                            <select name="tecnico_id" id="tecnico_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                                @foreach ($tecnicos as $tecnico)
                                                                    <option value="{{ $tecnico->id }}" {{ $atendimento->tecnico_id == $tecnico->id ? 'selected' : '' }}>
                                                                        {{ $tecnico->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        {{-- Descrição --}}
                                                        <div class="mb-4">
                                                            <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição</label>
                                                            <textarea name="descricao" id="descricao" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ $atendimento->descricao }}</textarea>
                                                        </div>

                                                        {{-- Data Disponível --}}
                                                        <div class="mb-4">
                                                            <label for="data_disponivel" class="block text-sm font-medium text-gray-700">Data Disponível</label>
                                                            <input type="date" name="data_disponivel" id="data_disponivel" value="{{ $atendimento->data_disponivel }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                        </div>

                                                        {{-- Hora Disponível --}}
                                                        <div class="mb-4">
                                                            <label for="hora_disponivel" class="block text-sm font-medium text-gray-700">Hora Disponível</label>
                                                            <input type="time" name="hora_disponivel" id="hora_disponivel" value="{{ $atendimento->hora_disponivel }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                        </div>

                                                        {{-- Status --}}
                                                        <div class="mb-4">
                                                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                                            <input type="text" name="status" id="status" value="{{ $atendimento->status }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                        </div>

                                                        {{-- Data Agendada --}}
                                                        <div class="mb-4">
                                                            <label for="data_agendada" class="block text-sm font-medium text-gray-700">Data Agendada</label>
                                                            <input type="date" name="data_agendada" id="data_agendada" value="{{ $atendimento->data_agendada }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                        </div>

                                                        {{-- Valor Comissão --}}
                                                        <div class="mb-4">
                                                            <label for="valor_comissao" class="block text-sm font-medium text-gray-700">Valor Comissão</label>
                                                            <input type="number" step="0.01" name="valor_comissao" id="valor_comissao" value="{{ $atendimento->valor_comissao }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                        </div>

                                                        {{-- Prioridade --}}
                                                        <div class="mb-4">
                                                            <label for="prioridade" class="block text-sm font-medium text-gray-700">Prioridade</label>
                                                            <input type="text" name="prioridade" id="prioridade" value="{{ $atendimento->prioridade }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                        </div>

                                                        {{-- Feedback Cliente --}}
                                                        <div class="mb-4">
                                                            <label for="feedback_cliente" class="block text-sm font-medium text-gray-700">Feedback Cliente</label>
                                                            <textarea name="feedback_cliente" id="feedback_cliente" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ $atendimento->feedback_cliente }}</textarea>
                                                        </div>

                                                        {{-- Foto --}}
                                                        <div class="mb-4">
                                                            <label for="foto" class="block text-sm font-medium text-gray-700">Foto</label>
                                                            <input type="file" name="foto" id="foto" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                            @if ($atendimento->foto)
                                                                <img src="{{ asset('storage/' . $atendimento->foto) }}" alt="Foto do Atendimento" class="mt-2 h-20 w-20 object-cover rounded-md">
                                                            @endif
                                                        </div>

                                                        {{-- Botões --}}
                                                        <div class="flex justify-end space-x-4">
                                                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Salvar</button>
                                                            <button type="button" onclick="closeModal('editModal{{ $atendimento->id }}')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancelar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Selecionar/Deselecionar todos os checkboxes
        const selectAllCheckbox = document.getElementById('select-all');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const deleteButton = document.getElementById('delete-selected-btn');

        function toggleDeleteButton() {
            const anyChecked = Array.from(itemCheckboxes).some(checkbox => checkbox.checked);
            deleteButton.classList.toggle('hidden', !anyChecked);
        }

        selectAllCheckbox.addEventListener('change', function() {
            itemCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
            toggleDeleteButton();
        });

        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', toggleDeleteButton);
        });
    </script>
</x-app-layout>