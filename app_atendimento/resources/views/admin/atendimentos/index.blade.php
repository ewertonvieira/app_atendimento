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
                                                    <a href="{{ route('admin.atendimentos.show', $atendimento->id) }}" class="text-blue-500 hover:underline">Ver</a>
                                                    <a href="{{ route('admin.atendimentos.edit', $atendimento->id) }}" class="text-yellow-500 hover:underline">Editar</a>
                                                    <form action="{{ route('admin.atendimentos.destroy', $atendimento->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja deletar este atendimento?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-500 hover:underline">Excluir</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
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