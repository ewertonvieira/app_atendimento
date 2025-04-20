<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Tecnico\TecnicoController;
use App\Http\Controllers\Atendimentos\AtendimentosController;
use App\Http\Controllers\Pagamentos\PagamentosController;
use App\Http\Controllers\User\AtendimentosUserController;
use App\Http\Controllers\Tecnico\AtendimentosTecnicoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rotas de perfil do usuário (acessível por todos os tipos de usuários autenticados)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Rotas para usuários (User)
Route::middleware(['auth', 'userMiddleware'])->group(function () {
    // Dashboard do usuário
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');

    // Rotas de atendimentos
    Route::get('/user/atendimentos', [AtendimentosUserController::class, 'index'])->name('atendimentos.index');
    Route::get('/user/atendimentos/create', [AtendimentosUserController::class, 'create'])->name('atendimentos.create');
    Route::post('/user/atendimentos', [AtendimentosUserController::class, 'store'])->name('atendimentos.store');
    Route::put('/user/atendimentos/{id}', [AtendimentosUserController::class, 'update'])->name('atendimentos.update');
    Route::delete('/user/atendimentos/{id}', [AtendimentosUserController::class, 'destroy'])->name('atendimentos.destroy');

    // Rotas de pagamentos
    Route::get('/pagamentos', [PagamentosController::class, 'index'])->name('pagamentos.index');
    Route::get('/pagamentos/{id}', [PagamentosController::class, 'show'])->name('pagamentos.show');

    // Rotas de perfil do usuário
    Route::get('/user/profile', [UserController::class, 'editProfile'])->name('user.edit-profile');
    Route::put('/user/profile', [UserController::class, 'updateProfile'])->name('user.update-profile');
});

// Rotas para criação de perfil
Route::middleware(['auth'])->group(function () {
    // Rota para exibir o formulário de criação de perfil
    Route::get('/user/create-profile', [UserController::class, 'showCreateProfileForm'])->name('user.create-profile');

    // Rota para salvar o novo perfil
    Route::post('/user/create-profile', [UserController::class, 'storeProfile'])->name('user.store-profile');
});

// Rotas para técnicos (Tecnico)
Route::middleware(['auth', 'tecnicoMiddleware'])->prefix('tecnico')->name('tecnico.')->group(function () {
    // Dashboard do técnico
    Route::get('/dashboard', [AtendimentosTecnicoController::class, 'dashboard'])->name('dashboard');

    // Listar atendimentos disponíveis
    Route::get('/atendimentos', [AtendimentosTecnicoController::class, 'index'])->name('atendimentos.index');

    // Listar atendimentos aceitos
    Route::get('/atendimentos-aceitos', [AtendimentosTecnicoController::class, 'atendimentosAceitos'])->name('atendimentos.aceitos');

    // Aceitar atendimento
    Route::post('/atendimentos/{id}/aceitar', [AtendimentosTecnicoController::class, 'aceitarAtendimento'])->name('atendimentos.aceitar');

    // Cancelar atendimento
    Route::put('/atendimentos/{id}/cancelar', [AtendimentosTecnicoController::class, 'cancelarAtendimento'])->name('atendimentos.cancelar');

    // Concluir atendimento
    Route::post('/atendimentos/{id}/concluir', [AtendimentosTecnicoController::class, 'concluirAtendimento'])->name('atendimentos.concluir');

    // Página de criação de pagamento
    Route::get('/create-pagamento/{id}', function ($id) {
        return view('tecnico.create-pagamento', ['id' => $id]);
    })->name('create-pagamento');

    // Salvar pagamento
    Route::post('/create-pagamento/{id}', [AtendimentosTecnicoController::class, 'storePagamento'])->name('store-pagamento');
});

// Rotas para administradores (Admin)
Route::middleware(['auth', 'adminMiddleware'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('users', AdminController::class); // CRUD de usuários
    Route::resource('atendimentos', AtendimentosController::class)->only(['index', 'show', 'destroy']);
    Route::resource('pagamentos', PagamentosController::class)->only(['index', 'show', 'destroy']);
});