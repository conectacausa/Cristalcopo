<?php

use App\Http\Controllers\Auth\AcessoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Gestao\Empresa\EmpresaFilialController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('auth.redirect')
        : redirect()->route('auth.login');
})->name('home');

Route::prefix('auth')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [LoginController::class, 'index'])->name('auth.login');
        Route::post('/login', [LoginController::class, 'autenticar'])->name('auth.login.autenticar');

        Route::get('/acesso', [AcessoController::class, 'index'])->name('auth.acesso');
        Route::post('/acesso/validar', [AcessoController::class, 'validarIdentidade'])->name('auth.acesso.validar');
        Route::post('/acesso', [AcessoController::class, 'registrar'])->name('auth.acesso.registrar');

        Route::view('/recuperar', 'auth.recuperar.index')->name('auth.recuperar');
    });

    Route::middleware(['auth', 'user.active'])->group(function () {
        Route::get('/logout', [LoginController::class, 'logout'])->name('auth.logout');
        Route::get('/redirect', [LoginController::class, 'redirectAutenticado'])->name('auth.redirect');
    });
});

Route::middleware(['auth', 'user.active'])->group(function () {
    Route::view('/dashboard', 'dashboard.index')
        ->name('dashboard')
        ->middleware('screen:dashboard');

    Route::prefix('empresa/filiais')
        ->name('empresa.filiais.')
        ->middleware('screen:empresa/filiais')
        ->group(function () {
            Route::get('/', [EmpresaFilialController::class, 'index'])->name('index');
            Route::get('/create', [EmpresaFilialController::class, 'create'])->name('create');
            Route::post('/', [EmpresaFilialController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [EmpresaFilialController::class, 'edit'])->name('edit');
            Route::put('/{id}', [EmpresaFilialController::class, 'update'])->name('update');
            Route::delete('/{id}', [EmpresaFilialController::class, 'destroy'])->name('destroy');

            Route::get('/ajax/estados/{paisId}', [EmpresaFilialController::class, 'estadosPorPais'])->name('estados');
            Route::get('/ajax/cidades/{estadoId}', [EmpresaFilialController::class, 'cidadesPorEstado'])->name('cidades');
        });
});
