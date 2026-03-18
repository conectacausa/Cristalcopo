<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('auth.login');
    Route::post('/login', [LoginController::class, 'autenticar'])->name('auth.login.autenticar');
    Route::get('/logout', [LoginController::class, 'logout'])->name('auth.logout');

    Route::view('/recuperar', 'auth.recuperar.index')->name('auth.recuperar');
    Route::view('/acesso', 'auth.acesso.index')->name('auth.acesso');
});
