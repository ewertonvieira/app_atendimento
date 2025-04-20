<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Criar Atendimento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('atendimentos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    {{-- Nome e Email --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                            <input type="text" name="name" id="name" value="{{ Auth::user()->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" disabled>
                            <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ Auth::user()->email }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" disabled>
                            <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                        </div>
                    </div>

                    {{-- Descrição --}}
                    <div>
                        <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição</label>
                        <textarea name="descricao" id="descricao" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>{{ old('descricao') }}</textarea>
                    </div>

                    {{-- Rua e Bairro --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="rua" class="block text-sm font-medium text-gray-700">Rua</label>
                            <input type="text" name="rua" id="rua" value="{{ Auth::user()->rua }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" {{ Auth::user()->rua ? 'disabled' : '' }}>
                            @if(Auth::user()->rua)
                                <input type="hidden" name="rua" value="{{ Auth::user()->rua }}">
                            @endif
                        </div>
                        <div>
                            <label for="bairro" class="block text-sm font-medium text-gray-700">Bairro</label>
                            <input type="text" name="bairro" id="bairro" value="{{ Auth::user()->bairro }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" {{ Auth::user()->bairro ? 'disabled' : '' }}>
                            @if(Auth::user()->bairro)
                                <input type="hidden" name="bairro" value="{{ Auth::user()->bairro }}">
                            @endif
                        </div>
                    </div>

                    {{-- CEP e Estado --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="cep" class="block text-sm font-medium text-gray-700">CEP</label>
                            <input type="text" name="cep" id="cep" value="{{ Auth::user()->cep }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" {{ Auth::user()->cep ? 'disabled' : '' }}>
                            @if(Auth::user()->cep)
                                <input type="hidden" name="cep" value="{{ Auth::user()->cep }}">
                            @endif
                        </div>
                        <div>
                            <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
                            <input type="text" name="estado" id="estado" value="{{ Auth::user()->estado }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" {{ Auth::user()->estado ? 'disabled' : '' }}>
                            @if(Auth::user()->estado)
                                <input type="hidden" name="estado" value="{{ Auth::user()->estado }}">
                            @endif
                        </div>
                    </div>

                    {{-- Telefone e CPF --}}
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700">Telefone</label>
                            <input type="text" name="phone_number" id="phone_number" value="{{ Auth::user()->phone_number }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" {{ Auth::user()->phone_number ? 'disabled' : '' }}>
                            @if(Auth::user()->phone_number)
                                <input type="hidden" name="phone_number" value="{{ Auth::user()->phone_number }}">
                            @endif
                        </div>
                        <div>
                            <label for="cpf" class="block text-sm font-medium text-gray-700">CPF</label>
                            <input type="text" name="cpf" id="cpf" value="{{ Auth::user()->cpf }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" {{ Auth::user()->cpf ? 'disabled' : '' }}>
                            @if(Auth::user()->cpf)
                                <input type="hidden" name="cpf" value="{{ Auth::user()->cpf }}">
                            @endif
                        </div>
                    </div>

                    {{-- Data e Hora Disponível --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="data_disponivel" class="block text-sm font-medium text-gray-700">Data Disponível</label>
                            <input type="date" name="data_disponivel" id="data_disponivel" value="{{ old('data_disponivel') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label for="hora_disponivel" class="block text-sm font-medium text-gray-700">Hora Disponível</label>
                            <input type="time" name="hora_disponivel" id="hora_disponivel" value="{{ old('hora_disponivel') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        </div>
                    </div>

                    {{-- Foto --}}
                    <div>
                        <label for="foto" class="block text-sm font-medium text-gray-700">Foto (opcional)</label>
                        <input type="file" name="foto" id="foto" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    {{-- Botão de Enviar --}}
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Criar Atendimento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>