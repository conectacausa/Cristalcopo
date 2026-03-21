<?php

use App\Http\Controllers\Auth\AcessoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Gestao\Empresa\EmpresaFilialController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CargoCboController;
use App\Http\Controllers\Empresa\SetorController;
use App\Http\Controllers\TesteAprovacaoController;
use App\Http\Controllers\Aprovacao\FluxoAprovacaoController;
use App\Http\Controllers\Aprovacao\ConfiguracaoFluxoController;
use App\Http\Controllers\Cargos\CargosController;

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

            Route::get('/ajax/porte/{codigo}', [EmpresaFilialController::class, 'buscarPortePorCodigo'])->name('porte.buscar');
            Route::get('/ajax/natureza/{codigo}', [EmpresaFilialController::class, 'buscarNaturezaPorCodigo'])->name('natureza.buscar');
            Route::get('/ajax/cnae/{subclasse}', [EmpresaFilialController::class, 'buscarCnaePorSubclasse'])->name('cnae.buscar');
            Route::get('/ajax/cnpj/{cnpj}', [EmpresaFilialController::class, 'consultarCnpj'])->name('cnpj.consultar');

            Route::post('/{filialId}/cnaes', [EmpresaFilialController::class, 'adicionarCnae'])->name('cnae.adicionar');
            Route::patch('/cnaes/{vinculoId}/principal', [EmpresaFilialController::class, 'atualizarPrincipalCnae'])->name('cnae.principal');
            Route::delete('/cnaes/{vinculoId}', [EmpresaFilialController::class, 'removerCnae'])->name('cnae.remover');
        });
    Route::middleware(['auth', 'screen:cargos/cbo'])->group(function () {
        Route::get('/cargos/cbo', [CargoCboController::class, 'index']);
        Route::get('/cargos/cbo/list', [CargoCboController::class, 'list']);
        Route::post('/cargos/cbo/store', [CargoCboController::class, 'store']);
        Route::post('/cargos/cbo/update/{id}', [CargoCboController::class, 'update']);
        Route::delete('/cargos/cbo/delete/{id}', [CargoCboController::class, 'delete']);
    });
   Route::middleware(['auth', 'user.active', 'screen:empresa/setor'])->group(function () {
        Route::get('/empresa/setor', [SetorController::class, 'index'])->name('empresa.setor.index');
        Route::get('/empresa/setor/list', [SetorController::class, 'list'])->name('empresa.setor.list');
        Route::post('/empresa/setor/store', [SetorController::class, 'store'])->name('empresa.setor.store');
        Route::post('/empresa/setor/update/{id}', [SetorController::class, 'update'])->name('empresa.setor.update');
        Route::delete('/empresa/setor/delete/{id}', [SetorController::class, 'delete'])->name('empresa.setor.delete');
    });
   Route::prefix('configuracao/aprovacao')
    ->name('aprovacao.fluxo.')
    ->middleware('screen:configuracao/aprovacao')
    ->group(function () {
        Route::get('/', [FluxoAprovacaoController::class, 'index'])->name('index');
        Route::get('/create', [FluxoAprovacaoController::class, 'create'])->name('create');
        Route::post('/store', [FluxoAprovacaoController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [FluxoAprovacaoController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [FluxoAprovacaoController::class, 'update'])->name('update');
        Route::post('/delete/{id}', [FluxoAprovacaoController::class, 'destroy'])->name('delete');
    });
    Route::prefix('configuracao/aprovacao-config')
    ->name('aprovacao.configuracao.')
    ->middleware('screen:configuracao/aprovacao')
    ->group(function () {
        Route::get('/', [ConfiguracaoFluxoController::class, 'index'])->name('index');
        Route::post('/store', [ConfiguracaoFluxoController::class, 'store'])->name('store');
    });
    Route::middleware(['auth', 'user.active', 'screen:cargos'])->group(function () {
        Route::get('/cargos', [CargosController::class, 'index'])->name('cargos.cargos.index');
        Route::get('/cargos/list', [CargosController::class, 'list'])->name('cargos.cargos.list');
    
        Route::get('/cargos/create', [CargosController::class, 'create'])->name('cargos.cargos.create');
        Route::post('/cargos/store', [CargosController::class, 'store'])->name('cargos.cargos.store');
    
        Route::get('/cargos/edit/{id}', [CargosController::class, 'editPage'])->name('cargos.cargos.edit');
        Route::post('/cargos/update/{id}', [CargosController::class, 'update'])->name('cargos.cargos.update');
    
        Route::get('/cargos/show/{id}', [CargosController::class, 'show'])->name('cargos.cargos.show');
        Route::get('/cargos/setores-por-filiais', [CargosController::class, 'setoresPorFiliais'])->name('cargos.cargos.setores_por_filiais');
    
        Route::delete('/cargos/delete/{id}', [CargosController::class, 'delete'])->name('cargos.cargos.delete');
    });
});
