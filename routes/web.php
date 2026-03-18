<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('auth.login');
});

Route::prefix('auth')->group(function () {

    // Login
    Route::get('/login', [LoginController::class, 'index'])->name('auth.login');
    Route::post('/login', [LoginController::class, 'autenticar'])->name('auth.login.autenticar');

    // Logout
    Route::get('/logout', [LoginController::class, 'logout'])->name('auth.logout');

    // Futuras páginas (placeholder)
    Route::view('/recuperar', 'auth.recuperar.index')->name('auth.recuperar');
    Route::view('/acesso', 'auth.acesso.index')->name('auth.acesso');
});

/*
|--------------------------------------------------------------------------
| Área protegida (exemplo futuro)
|--------------------------------------------------------------------------
*/

Route::middleware([])->group(function () {

    // Exemplo de rota após login (você pode ajustar depois)
    Route::get('/dashboard', function () {
        return 'Dashboard (logado)';
    });

});
