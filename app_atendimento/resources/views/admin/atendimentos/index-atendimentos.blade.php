<!-- filepath: c:\Users\E. Vieira\Documents\PHP_Repositorio\app_atendimento\resources\views\user\index-atendimentos.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Meus Atendimentos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if ($atendimentos->isEmpty())
                    <p class="text-gray-500 text-center">Nenhum atendimento encontrado.</p>
                @else
                    <table class="table-auto w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-4 py-2"><input type="checkbox" id="select-all"></th>
                                <th class="border border-gray-300 px-4 py-2">Cliente</th>
                                <th class="border border-gray-300 px-4 py-2">Técnico</th>
                                <th class="border border-gray-300 px-4 py-2">Descrição</th>
                                <th class="border border-gray-300 px-4 py-2">Data Disponível</th>
                                <th class="border border-gray-300 px-4 py-2">Hora Disponível</th>
                                <th class="border border-gray-300 px-4 py-2">Status</th>
                                <th class="border border-gray-300 px-4 py-2">Foto</th>
                                <th class="border border-gray-300 px-4 py-2">Feedback do Cliente</th>
                                <th class="border border-gray-300 px-4 py-2">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($atendimentos as $atendimento)
                                <tr>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <input type="checkbox" class="select-item" value="{{ $atendimento->id }}">
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        {{ $atendimento->cliente ? $atendimento->cliente->name : 'N/A' }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        {{ $atendimento->tecnico ? $atendimento->tecnico->name : 'N/A' }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        {{ $atendimento->descricao ?? 'N/A' }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        {{ $atendimento->data_disponivel ?? 'N/A' }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        {{ $atendimento->hora_disponivel ?? 'N/A' }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        {{ $atendimento->status ? ucfirst($atendimento->status) : 'N/A' }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        {{ $atendimento->foto ? 'Sim' : 'Não' }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        {{ $atendimento->feedback_cliente ?? 'N/A' }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <!-- Botão Visualizar -->
                                        <a href="{{ route('admin.atendimentos.show', $atendimento->id) }}" class="px-2 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                            Visualizar
                                        </a>

                                        <!-- Botão Editar -->
                                        <a href="{{ route('admin.atendimentos.edit', $atendimento->id) }}" class="px-2 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">
                                            Editar
                                        </a>

                                        <!-- Botão Deletar -->
                                        <button onclick="deleteSingle({{ $atendimento->id }})" class="px-2 py-1 bg-red-500 text-white rounded-md hover:bg-red-600">
                                            Deletar
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <button id="delete-selected" onclick="deleteSelected()" class="mt-4 px-4 py-2 bg-red-500 text-white rounded-md shadow-md hover:bg-red-600">
                        Excluir Selecionados
                    </button>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Selecionar todos os checkboxes
        document.getElementById('select-all').addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('.select-item');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });

        // Função para excluir os itens selecionados
        function deleteSelected() {
            const selectedIds = Array.from(document.querySelectorAll('.select-item:checked'))
                .map(checkbox => checkbox.value);

            if (selectedIds.length === 0) {
                alert('Nenhum atendimento selecionado para exclusão.');
                return;
            }

            if (!confirm('Tem certeza que deseja excluir os atendimentos selecionados?')) {
                return;
            }

            // Enviar requisição para a rota de exclusão
            fetch('{{ route('admin.atendimentos.destroy') }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ids: selectedIds })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Atendimentos excluídos com sucesso.');
                        location.reload(); // Recarregar a página
                    } else {
                        alert('Erro ao excluir atendimentos.');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao processar a solicitação.');
                });
        }

        // Função para excluir um único atendimento
        function deleteSingle(id) {
            if (!confirm('Tem certeza que deseja excluir este atendimento?')) {
                return;
            }

            // Enviar requisição para a rota de exclusão
            fetch(`{{ route('admin.atendimentos.destroy') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Atendimento excluído com sucesso.');
                        location.reload(); // Recarregar a página
                    } else {
                        alert('Erro ao excluir atendimento.');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao processar a solicitação.');
                });
        }
    </script>
</x-app-layout>