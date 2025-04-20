<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Bem-vindo</title>

        <!-- Tailwind CSS -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 flex flex-col min-h-screen">
        <!-- Navbar -->
        <header class="bg-white dark:bg-gray-800 shadow-md">
            <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
                <div class="flex items-center gap-8">
                    <a href="#" class="text-2xl font-bold text-gray-800 dark:text-gray-200">Minha Aplicação</a>
                    <nav class="hidden md:flex gap-6">
                        <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">Página Inicial</a>
                        <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">Quem Somos</a>
                        <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">Atendimento</a>
                        <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">Blog</a>
                    </nav>
                </div>
                <div class="flex gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                                Login
                            </a>
                            @if (Route::has('custom.register.create'))
                                <a href="{{ route('custom.register.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Registrar
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </header>

        <!-- Carrossel -->
        <section class="bg-gray-200 dark:bg-gray-700 py-12">
            <div class="max-w-7xl mx-auto px-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md h-64 flex items-center justify-center">
                    <p class="text-gray-600 dark:text-gray-400">Carrossel vazio. Preencha com imagens ou conteúdo.</p>
                </div>
            </div>
        </section>

        <!-- Certificados -->
        <section class="py-12 bg-gray-100 dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-200 mb-6">Certificados da Empresa</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-8">Nossa empresa é certificada pelos melhores padrões de qualidade e segurança.</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-md">
                        <p class="text-gray-600 dark:text-gray-400">Certificado 1</p>
                    </div>
                    <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-md">
                        <p class="text-gray-600 dark:text-gray-400">Certificado 2</p>
                    </div>
                    <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-md">
                        <p class="text-gray-600 dark:text-gray-400">Certificado 3</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Promoções -->
        <section class="py-12 bg-blue-600 text-white">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <h2 class="text-3xl font-bold mb-6">Promoções de Desconto</h2>
                <p class="mb-8">Aproveite nossas promoções exclusivas e economize em seus atendimentos!</p>
                <a href="#" class="px-6 py-3 bg-white text-blue-600 rounded-lg hover:bg-gray-200">Saiba Mais</a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-800 text-gray-200 py-12">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">Minha Aplicação</h3>
                    <p class="text-gray-400">Gerencie seus atendimentos de forma eficiente e organizada.</p>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Links Rápidos</h3>
                    <ul class="text-gray-400">
                        <li><a href="#" class="hover:text-white">Página Inicial</a></li>
                        <li><a href="#" class="hover:text-white">Quem Somos</a></li>
                        <li><a href="#" class="hover:text-white">Atendimento</a></li>
                        <li><a href="#" class="hover:text-white">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Contato</h3>
                    <p class="text-gray-400">Email: contato@minhaaplicacao.com</p>
                    <p class="text-gray-400">Telefone: (11) 1234-5678</p>
                </div>
            </div>
            <div class="text-center text-gray-500 mt-8">
                &copy; {{ date('Y') }} Minha Aplicação. Todos os direitos reservados.
            </div>
        </footer>
    </body>
</html>
