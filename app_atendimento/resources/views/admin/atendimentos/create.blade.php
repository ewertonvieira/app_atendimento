<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Criar Atendimento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.atendimentos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nome -->
                        <div>
                            <label for="cliente_id" class="block text-sm font-medium text-gray-700">Nome</label>
                            <select id="cliente_id" name="cliente_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" onchange="preencherDadosUsuario(this)">
                                <option value="">Selecione um usuário</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" data-email="{{ $cliente->email }}" data-rua="{{ $cliente->rua }}" data-bairro="{{ $cliente->bairro }}" data-cep="{{ $cliente->cep }}" data-estado="{{ $cliente->estado }}" data-telefone="{{ $cliente->phone_number }}" data-cpf="{{ $cliente->cpf }}">
                                        {{ $cliente->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="text" id="email" name="email" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" readonly>
                        </div>

                        <!-- Descrição -->
                        <div class="col-span-2">
                            <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição</label>
                            <textarea id="descricao" name="descricao" rows="3" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" require></textarea>
                        </div>

                        <!-- Rua -->
                        <div>
                            <label for="rua" class="block text-sm font-medium text-gray-700">Rua</label>
                            <input type="text" id="rua" name="rua" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" readonly>
                        </div>

                        <!-- Bairro -->
                        <div>
                            <label for="bairro" class="block text-sm font-medium text-gray-700">Bairro</label>
                            <input type="text" id="bairro" name="bairro" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" readonly>
                        </div>

                        <!-- CEP -->
                        <div>
                            <label for="cep" class="block text-sm font-medium text-gray-700">CEP</label>
                            <input type="text" id="cep" name="cep" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" readonly>
                        </div>

                        <!-- Estado -->
                        <div>
                            <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
                            <input type="text" id="estado" name="estado" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" readonly>
                        </div>

                        <!-- Telefone -->
                        <div>
                            <label for="telefone" class="block text-sm font-medium text-gray-700">Telefone</label>
                            <input type="text" id="telefone" name="telefone" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" readonly>
                        </div>

                        <!-- CPF -->
                        <div>
                            <label for="cpf" class="block text-sm font-medium text-gray-700">CPF</label>
                            <input type="text" id="cpf" name="cpf" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" readonly>
                        </div>

                        <!-- Data Disponível -->
                        <div>
                            <label for="data_disponivel" class="block text-sm font-medium text-gray-700">Data Disponível</label>
                            <input type="date" id="data_disponivel" name="data_disponivel" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                        </div>

                        <!-- Hora Disponível -->
                        <div>
                            <label for="hora_disponivel" class="block text-sm font-medium text-gray-700">Hora Disponível</label>
                            <input type="time" id="hora_disponivel" name="hora_disponivel" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                        </div>

                        <!-- Foto -->
                        <div class="col-span-2">
                            <label for="foto" class="block text-sm font-medium text-gray-700">Foto (opcional)</label>
                            <input type="file" id="foto" name="foto" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function preencherDadosUsuario(select) {
            const option = select.options[select.selectedIndex];
            document.getElementById('email').value = option.getAttribute('data-email') || '';
            document.getElementById('rua').value = option.getAttribute('data-rua') || '';
            document.getElementById('bairro').value = option.getAttribute('data-bairro') || '';
            document.getElementById('cep').value = option.getAttribute('data-cep') || '';
            document.getElementById('estado').value = option.getAttribute('data-estado') || '';
            document.getElementById('telefone').value = option.getAttribute('data-telefone') || '';
            document.getElementById('cpf').value = option.getAttribute('data-cpf') || '';
        }
    </script>
</x-app-layout>