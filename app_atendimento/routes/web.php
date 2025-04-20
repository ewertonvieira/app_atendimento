<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Tecnico\TecnicoController;
use App\Http\Controllers\Atendimentos\AtendimentosController;
use App\Http\Controllers\Pagamentos\PagamentosController;
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
    Route::resource('atendimentos', AtendimentosController::class)->only(['create', 'store', 'index', 'show', 'edit', 'update']);

    // Rotas de pagamentos
    Route::get('/pagamentos', [PagamentosController::class, 'index'])->name('pagamentos.index');
    Route::get('/pagamentos/{id}', [PagamentosController::class, 'show'])->name('pagamentos.show');

    // Rotas de perfil do usuário
    Route::get('/user/profile', [UserController::class, 'editProfile'])->name('user.edit-profile');
    Route::put('/user/profile', [UserController::class, 'updateProfile'])->name('user.update-profile');
});

// Rotas para técnicos (Tecnico)
Route::middleware(['auth', 'tecnicoMiddleware'])->group(function () {
    Route::get('/tecnico/dashboard', [TecnicoController::class, 'index'])->name('tecnico.dashboard');
    Route::get('/tecnico/atendimentos', [AtendimentosController::class, 'index'])->name('tecnico.atendimentos.index');
    Route::post('/tecnico/atendimentos/{id}/aceitar', [AtendimentosController::class, 'aceitar'])->name('tecnico.atendimentos.aceitar');
    Route::post('/tecnico/atendimentos/{id}/executar', [AtendimentosController::class, 'executar'])->name('tecnico.atendimentos.executar');
});

// Rotas para administradores (Admin)
Route::middleware(['auth', 'adminMiddleware'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('users', AdminController::class); // CRUD de usuários
    Route::resource('atendimentos', AtendimentosController::class)->only(['index', 'show', 'destroy']);
    Route::resource('pagamentos', PagamentosController::class)->only(['index', 'show', 'destroy']);
});