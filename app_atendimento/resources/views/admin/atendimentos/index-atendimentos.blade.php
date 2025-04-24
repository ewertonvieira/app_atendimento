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
                    <a href="{{ route('admin.atendimentos.create') }}" 
                        class="px-5 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                        Criar Novo Registro
                    </a>
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
                                    <td class="border border-gray-300 px-4 py-2 truncate" style="max-width: 140px;">
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
                                        <button onclick="openModal({
                                            cliente: '{{ $atendimento->cliente ? $atendimento->cliente->name : 'Null' }}',
                                            tecnico: '{{ $atendimento->tecnico ? $atendimento->tecnico->name : 'Null' }}',
                                            descricao: '{{ $atendimento->descricao ?? 'Null' }}',
                                            rua: '{{ $atendimento->cliente ? $atendimento->cliente->rua : 'Null' }}',
                                            bairro: '{{ $atendimento->cliente ? $atendimento->cliente->bairro : 'Null' }}',
                                            cep: '{{ $atendimento->cliente ? $atendimento->cliente->cep : 'Null' }}',
                                            estado: '{{ $atendimento->cliente ? $atendimento->cliente->estado : 'Null' }}',
                                            telefone: '{{ $atendimento->cliente ? $atendimento->cliente->phone_number : 'Null' }}',
                                            cpf: '{{ $atendimento->cliente ? $atendimento->cliente->cpf : 'Null' }}',
                                            data_disponivel: '{{ $atendimento->data_disponivel ?? 'Null' }}',
                                            hora_disponivel: '{{ $atendimento->hora_disponivel ?? 'Null' }}',
                                            status: '{{ $atendimento->status ?? 'Null' }}',
                                            foto: '{{ $atendimento->foto ?? null }}',
                                            feedback_cliente: '{{ $atendimento->feedback_cliente ?? 'Null' }}'
                                        })" 
                                        class="px-2 py-1 text-sm bg-blue-500 text-white rounded-md hover:bg-blue-600 inline-block">
                                            Ver
                                        </button>

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
            @endif
        </div>
    </div>
</div>

<!-- Adicionando Modal -->

<div id="infoModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg w-3/4 max-w-4xl">
        <div class="p-4 border-b flex justify-between items-center">
            <h3 class="text-lg font-semibold">Detalhes do Atendimento</h3>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        <div class="p-6">
            <!-- Informações do Atendimento -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Cliente -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Cliente</label>
                    <p id="modal-cliente" class="text-gray-900">Null</p>
                </div>

                <!-- Técnico -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Técnico</label>
                    <p id="modal-tecnico" class="text-gray-900">Null</p>
                </div>

                <!-- Descrição -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Descrição</label>
                    <p id="modal-descricao" class="text-gray-900">Null</p>
                </div>

                <!-- Rua -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Rua</label>
                    <p id="modal-rua" class="text-gray-900">Null</p>
                </div>

                <!-- Bairro -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Bairro</label>
                    <p id="modal-bairro" class="text-gray-900">Null</p>
                </div>

                <!-- CEP -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">CEP</label>
                    <p id="modal-cep" class="text-gray-900">Null</p>
                </div>

                <!-- Estado -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Estado</label>
                    <p id="modal-estado" class="text-gray-900">Null</p>
                </div>

                <!-- Telefone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Telefone</label>
                    <p id="modal-telefone" class="text-gray-900">Null</p>
                </div>

                <!-- CPF -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">CPF</label>
                    <p id="modal-cpf" class="text-gray-900">Null</p>
                </div>

                <!-- Data Disponível -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Data Disponível</label>
                    <p id="modal-data-disponivel" class="text-gray-900">Null</p>
                </div>

                <!-- Hora Disponível -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Hora Disponível</label>
                    <p id="modal-hora-disponivel" class="text-gray-900">Null</p>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <p id="modal-status" class="text-gray-900">Null</p>
                </div>

                <!-- Foto -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Foto</label>
                    <p id="modal-foto" class="text-gray-900">Null</p>
                </div>

                <!-- Feedback do Cliente -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Feedback do Cliente</label>
                    <p id="modal-feedback" class="text-gray-900">Null</p>
                </div>
            </div>
        </div>
        <div class="p-4 border-t flex justify-end">
            <button onclick="closeModal()" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Fechar</button>
        </div>
    </div>
</div>

<script>

    // Função para abrir o modal com detalhes do atendimento

    function openModal(data) {
        // Preencher os campos do modal com os dados fornecidos
        document.getElementById('modal-cliente').textContent = data.cliente || 'Null';
        document.getElementById('modal-tecnico').textContent = data.tecnico || 'Null';
        document.getElementById('modal-descricao').textContent = data.descricao || 'Null';
        document.getElementById('modal-rua').textContent = data.rua || 'Null';
        document.getElementById('modal-bairro').textContent = data.bairro || 'Null';
        document.getElementById('modal-cep').textContent = data.cep || 'Null';
        document.getElementById('modal-estado').textContent = data.estado || 'Null';
        document.getElementById('modal-telefone').textContent = data.telefone || 'Null';
        document.getElementById('modal-cpf').textContent = data.cpf || 'Null';
        document.getElementById('modal-data-disponivel').textContent = data.data_disponivel || 'Null';
        document.getElementById('modal-hora-disponivel').textContent = data.hora_disponivel || 'Null';
        document.getElementById('modal-status').textContent = data.status || 'Null';
        document.getElementById('modal-foto').textContent = data.foto ? 'Sim' : 'Null';
        document.getElementById('modal-feedback').textContent = data.feedback_cliente || 'Null';

        // Exibir o modal
        document.getElementById('infoModal').classList.remove('hidden');
    }

    function closeModal() {
        // Esconder o modal
        document.getElementById('infoModal').classList.add('hidden');
    }

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