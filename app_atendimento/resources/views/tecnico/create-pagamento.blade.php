<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pagamento do Atendimento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Atendimento #{{ $id }}</h3>
                <form action="{{ route('tecnico.store-pagamento', ['id' => $id]) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="valor" class="block text-sm font-medium text-gray-700">Valor do Atendimento (R$)</label>
                        <input type="text" name="valor" id="valor" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <a href="{{ route('tecnico.dashboard') }}" class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-400">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-500">
                            Salvar Pagamento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>