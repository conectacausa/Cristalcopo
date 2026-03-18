<?php

use App\Http\Controllers\Auth\AcessoController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('auth.login');
})->name('home');

Route::prefix('auth')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [LoginController::class, 'index'])->name('auth.login');
        Route::post('/login', [LoginController::class, 'autenticar'])->name('auth.login.autenticar');

        Route::view('/recuperar', 'auth.recuperar.index')->name('auth.recuperar');
    });

    Route::middleware(['auth', 'user.active', 'first.access.pending'])->group(function () {
        Route::get('/acesso', [AcessoController::class, 'index'])->name('auth.acesso');
        Route::post('/acesso/validar', [AcessoController::class, 'validarIdentidade'])->name('auth.acesso.validar');
        Route::post('/acesso', [AcessoController::class, 'registrar'])->name('auth.acesso.registrar');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/logout', [LoginController::class, 'logout'])->name('auth.logout');
    });
});

Route::middleware(['auth', 'user.active', 'first.access.completed'])->group(function () {
    Route::get('/dashboard', function () {
        return 'Dashboard (logado)';
    })->name('dashboard');
});
