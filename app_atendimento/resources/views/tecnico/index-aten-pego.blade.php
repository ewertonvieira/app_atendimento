<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Atendimentos Aceitos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($atendimentos as $atendimento)
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Atendimento #{{ $atendimento->id }}</h3>
                        <p class="text-sm text-gray-600"><strong>Descrição:</strong> {{ $atendimento->descricao }}</p>
                        <p class="text-sm text-gray-600"><strong>Data Disponível:</strong> {{ $atendimento->data_disponivel }}</p>
                        <p class="text-sm text-gray-600"><strong>Hora Disponível:</strong> {{ $atendimento->hora_disponivel }}</p>
                        <p class="text-sm text-gray-600"><strong>Prioridade:</strong> {{ ucfirst($atendimento->prioridade) }}</p>

                        {{-- Informações do Cliente --}}
                        <div class="mt-4">
                            <h4 class="text-md font-semibold text-gray-800">Informações do Cliente</h4>
                            <p class="text-sm text-gray-600"><strong>Nome:</strong> {{ $atendimento->cliente->name }}</p>
                            <p class="text-sm text-gray-600"><strong>Email:</strong> {{ $atendimento->cliente->email }}</p>
                            <p class="text-sm text-gray-600"><strong>Telefone:</strong> {{ $atendimento->cliente->phone_number }}</p>
                        </div>

                        {{-- Botões para alterar o status --}}
                        <div class="mt-4 flex space-x-2">
                            <form action="{{ route('tecnico.atendimentos.cancelar', $atendimento->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                    Cancelar
                                </button>
                            </form>
                            <form action="{{ route('tecnico.atendimentos.concluir', $atendimento->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Concluir
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-600">Nenhum atendimento aceito no momento.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>