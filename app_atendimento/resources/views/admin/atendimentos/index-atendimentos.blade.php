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
                <div class="mb-4 flex justify-between items-center">
                    <button id="delete-selected-btn" onclick="deleteSelected()" 
                            class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 hidden">
                        Excluir Selecionados
                    </button>

                    <button onclick="openModal()" 
                        class="px-5 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                        Criar Novo Registro
                    </button>
                </div>
                <!-- Contêiner para barra de rolagem horizontal -->
                <div class="overflow-x-auto">
                    <table class="table-auto w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-4 py-2"><input type="checkbox" id="select-all"></th>
                                <th class="border border-gray-300 px-4 py-2">Cliente</th>
                                <th class="border border-gray-300 px-4 py-2">Técnico</th>
                                <th class="border border-gray-300 px-4 py-2" style="max-width: 120px;">Descrição</th>
                                <th class="border border-gray-300 px-4 py-2">Data Disponível</th>
                                <th class="border border-gray-300 px-4 py-2">Hora Disponível</th>
                                <th class="border border-gray-300 px-4 py-2">Status</th>
                                <th class="border border-gray-300 px-4 py-2">Foto</th>
                                <th class="border border-gray-300 px-4 py-2" style="max-width: 90px;">Feedback do Cliente</th>
                                <th class="border border-gray-300 px-4 py-2" style="max-width: 200px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($atendimentos as $atendimento)
                                <tr>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <input type="checkbox" class="select-item" value="{{ $atendimento->id }}">
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2 truncate">
                                        {{ $atendimento->cliente ? $atendimento->cliente->name : 'N/A' }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2 truncate">
                                        {{ $atendimento->tecnico ? $atendimento->tecnico->name : 'N/A' }}
                                    </td>
                                    <td style="max-width: 120px;" class="border border-gray-300 px-4 py-2 truncate w-20" title="{{ $atendimento->descricao }}">
                                        {{ $atendimento->descricao ?? 'N/A' }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2 truncate">
                                        {{ $atendimento->data_disponivel ?? 'N/A' }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2 truncate">
                                        {{ $atendimento->hora_disponivel ?? 'N/A' }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2 truncate">
                                        {{ $atendimento->status ? ucfirst($atendimento->status) : 'N/A' }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2 truncate">
                                        {{ $atendimento->foto ? 'Sim' : 'Não' }}
                                    </td>
                                    <td style="max-width: 90px;" class="text-center border border-gray-300 px-4 py-2 truncate">
                                        {{ $atendimento->feedback_cliente ?? 'N/A' }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2 text-center">
                                        <!-- Botão Visualizar -->
                                        <a href="{{ route('admin.atendimentos.show', $atendimento->id) }}" 
                                           class="px-2 py-1 text-sm bg-blue-500 text-white rounded-md hover:bg-blue-600 inline-block">
                                            Ver
                                        </a>

                                        <!-- Botão Editar -->
                                        <a href="{{ route('admin.atendimentos.edit', $atendimento->id) }}" 
                                           class="px-2 py-1 text-sm bg-yellow-500 text-white rounded-md hover:bg-yellow-600 inline-block ml-2">
                                            Editar
                                        </a>

                                        <!-- Botão Deletar -->
                                        <button onclick="deleteSingle({{ $atendimento->id }})" 
                                                class="px-2 py-1 text-sm bg-red-500 text-white rounded-md hover:bg-red-600 inline-block ml-2">
                                            Deletar
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Fim do contêiner para barra de rolagem horizontal -->
            @endif
        </div>
    </div>
</div>

<script>

    // Selecionar todos os checkboxes
    document.getElementById('select-all').addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('.select-item');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        toggleDeleteButton();
    });

    // Monitorar mudanças nos checkboxes individuais
    document.querySelectorAll('.select-item').forEach(checkbox => {
        checkbox.addEventListener('change', toggleDeleteButton);
    });

    // Função para alternar a visibilidade do botão "Excluir Selecionados"
    function toggleDeleteButton() {
        const selectedIds = Array.from(document.querySelectorAll('.select-item:checked'));
        const deleteButton = document.getElementById('delete-selected-btn');
        if (selectedIds.length > 0) {
            deleteButton.classList.remove('hidden'); // Exibe o botão
        } else {
            deleteButton.classList.add('hidden'); // Esconde o botão
        }
    }

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