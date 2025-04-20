<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Saudação --}}
            <div class="mb-6 bg-white shadow-md rounded-lg p-6">
                <h1 class="text-2xl font-bold text-gray-800">Olá, {{ Auth::user()->name }}!</h1>
                <p class="text-gray-600">Bem-vindo à sua dashboard. Aqui você pode acompanhar seus atendimentos e pagamentos.</p>
            </div>

            {{-- Cards de Informações --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Atendimentos Disponíveis --}}
                <div class="bg-white border border-gray-200 shadow-md rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800">Atendimentos Disponíveis</h3>
                    <p class="text-4xl font-bold text-blue-600 mt-4">{{ $atendimentosDisponiveis }}</p>
                    <a href="{{ route('tecnico.atendimentos.index') }}" class="mt-6 inline-block bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-500">
                        Ver Atendimentos
                    </a>
                </div>

                {{-- Atendimentos Aceitos --}}
                <div class="bg-white border border-gray-200 shadow-md rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800">Atendimentos Aceitos</h3>
                    <p class="text-4xl font-bold text-green-600 mt-4">{{ $atendimentosAceitos }}</p>
                    <a href="{{ route('tecnico.atendimentos.aceitos') }}" class="mt-6 inline-block bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-500">
                        Ver Atendimentos
                    </a>
                </div>
            </div>

            {{-- Total de Pagamentos Emitidos no Mês --}}
            <div class="mt-6 bg-white border border-gray-200 shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800">Total de Pagamentos Emitidos no Mês</h3>
                <p class="text-4xl font-bold text-indigo-600 mt-4">R$ {{ number_format($valorTotalPagamentos, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
