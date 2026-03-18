<?php

use App\Http\Controllers\Auth\AcessoController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('auth.login');
})->name('home');

Route::prefix('auth')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('auth.login');
    Route::post('/login', [LoginController::class, 'autenticar'])->name('auth.login.autenticar');
    Route::get('/logout', [LoginController::class, 'logout'])->name('auth.logout');

    Route::get('/acesso', [AcessoController::class, 'index'])->name('auth.acesso');
    Route::post('/acesso/validar', [AcessoController::class, 'validarIdentidade'])->name('auth.acesso.validar');
    Route::post('/acesso', [AcessoController::class, 'registrar'])->name('auth.acesso.registrar');

    Route::view('/recuperar', 'auth.recuperar.index')->name('auth.recuperar');
});

Route::get('/dashboard', function () {
    return 'Dashboard (logado)';
});
