<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\AcessoController;

use App\Http\Controllers\Gestao\Empresa\EmpresaFilialController;
use App\Http\Controllers\Empresa\SetorController;
use App\Http\Controllers\CargoCboController;

use App\Http\Controllers\Aprovacao\FluxoAprovacaoController;
use App\Http\Controllers\Aprovacao\ConfiguracaoFluxoController;

use App\Http\Controllers\Cargos\CargosController;
use App\Http\Controllers\Cargos\ResponsabilidadesController;
use App\Http\Controllers\Cargos\CompetenciasController;
use App\Http\Controllers\Cargos\FormacoesController;
use App\Http\Controllers\Cargos\CursosController;

use App\Http\Controllers\Sst\RiscosController;

use App\Http\Controllers\Pessoas\Colaboradores\ColaboradoresController;
use App\Http\Controllers\Configuracao\Importar\ImportarColaboradoresController;

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('auth.redirect')
        : redirect()->route('auth.login');
})->name('home');


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
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


/*
|--------------------------------------------------------------------------
| SISTEMA (LOGADO)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'user.active'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::view('/dashboard', 'dashboard.index')
        ->name('dashboard')
        ->middleware('screen:dashboard');


    /*
    |--------------------------------------------------------------------------
    | FILIAIS
    |--------------------------------------------------------------------------
    */
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


    /*
    |--------------------------------------------------------------------------
    | CBO
    |--------------------------------------------------------------------------
    */
    Route::prefix('cargos/cbo')
        ->middleware('screen:cargos/cbo')
        ->group(function () {

            Route::get('/', [CargoCboController::class, 'index']);
            Route::get('/list', [CargoCboController::class, 'list']);
            Route::post('/store', [CargoCboController::class, 'store']);
            Route::post('/update/{id}', [CargoCboController::class, 'update']);
            Route::delete('/delete/{id}', [CargoCboController::class, 'delete']);
        });


    /*
    |--------------------------------------------------------------------------
    | SETORES
    |--------------------------------------------------------------------------
    */
    Route::prefix('empresa/setor')
        ->name('empresa.setor.')
        ->middleware('screen:empresa/setor')
        ->group(function () {

            Route::get('/', [SetorController::class, 'index'])->name('index');
            Route::get('/list', [SetorController::class, 'list'])->name('list');
            Route::post('/store', [SetorController::class, 'store'])->name('store');
            Route::post('/update/{id}', [SetorController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [SetorController::class, 'delete'])->name('delete');
        });


    /*
    |--------------------------------------------------------------------------
    | APROVAÇÃO - FLUXOS
    |--------------------------------------------------------------------------
    */
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


    /*
    |--------------------------------------------------------------------------
    | APROVAÇÃO - CONFIGURAÇÃO
    |--------------------------------------------------------------------------
    */
    Route::prefix('configuracao/aprovacao-config')
        ->name('aprovacao.configuracao.')
        ->middleware('screen:configuracao/aprovacao')
        ->group(function () {

            Route::get('/', [ConfiguracaoFluxoController::class, 'index'])->name('index');
            Route::post('/store', [ConfiguracaoFluxoController::class, 'store'])->name('store');
        });


    /*
    |--------------------------------------------------------------------------
    | SST - RISCOS
    |--------------------------------------------------------------------------
    */
    Route::prefix('sst/riscos')
        ->name('sst.riscos.')
        ->middleware('screen:sst/riscos')
        ->group(function () {

            Route::get('/', [RiscosController::class, 'index'])->name('index');
            Route::get('/list', [RiscosController::class, 'list'])->name('list');
            Route::post('/store', [RiscosController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [RiscosController::class, 'edit'])->name('edit');
            Route::post('/update/{id}', [RiscosController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [RiscosController::class, 'delete'])->name('delete');
        });


    /*
    |--------------------------------------------------------------------------
    | CARGOS
    |--------------------------------------------------------------------------
    */
    Route::prefix('cargos')
        ->name('cargos.cargos.')
        ->middleware('screen:cargos')
        ->group(function () {

            Route::get('/', [CargosController::class, 'index'])->name('index');
            Route::get('/list', [CargosController::class, 'list'])->name('list');
            Route::get('/create', [CargosController::class, 'create'])->name('create');
            Route::post('/store', [CargosController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [CargosController::class, 'editPage'])->name('edit');
            Route::post('/update/{id}', [CargosController::class, 'update'])->name('update');
            Route::get('/show/{id}', [CargosController::class, 'show'])->name('show');
            Route::get('/setores-por-filiais', [CargosController::class, 'setoresPorFiliais'])->name('setores_por_filiais');
            Route::delete('/delete/{id}', [CargosController::class, 'delete'])->name('delete');
        });

    /*
    |--------------------------------------------------------------------------
    | CARGOS - RESPONSABILIDADE
    |--------------------------------------------------------------------------
    */
    Route::prefix('cargos/responsabilidades')
        ->name('cargos.responsabilidades.')
        ->middleware('screen:cargos/responsabilidades')
        ->group(function () {
            Route::get('/', [ResponsabilidadesController::class, 'index'])->name('index');
            Route::get('/list', [ResponsabilidadesController::class, 'list'])->name('list');
            Route::post('/store', [ResponsabilidadesController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [ResponsabilidadesController::class, 'edit'])->name('edit');
            Route::post('/update/{id}', [ResponsabilidadesController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [ResponsabilidadesController::class, 'delete'])->name('delete');
    });

    /*
    |--------------------------------------------------------------------------
    | CARGOS - COMPETENCIAS
    |--------------------------------------------------------------------------
    */
    Route::prefix('cargos/competencias')
        ->name('cargos.competencias.')
        ->middleware('screen:cargos/competencias')
        ->group(function () {
            Route::get('/', [CompetenciasController::class, 'index'])->name('index');
            Route::get('/list', [CompetenciasController::class, 'list'])->name('list');
            Route::post('/store', [CompetenciasController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [CompetenciasController::class, 'edit'])->name('edit');
            Route::post('/update/{id}', [CompetenciasController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [CompetenciasController::class, 'delete'])->name('delete');
    });

    /*
    |--------------------------------------------------------------------------
    | CARGOS - FORMAÇÃO
    |--------------------------------------------------------------------------
    */
    Route::prefix('cargos/formacao')
        ->name('cargos.formacao.')
        ->middleware('screen:cargos/formacao')
        ->group(function () {
            Route::get('/', [FormacoesController::class, 'index'])->name('index');
            Route::get('/list', [FormacoesController::class, 'list'])->name('list');
            Route::post('/store', [FormacoesController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [FormacoesController::class, 'edit'])->name('edit');
            Route::post('/update/{id}', [FormacoesController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [FormacoesController::class, 'delete'])->name('delete');
    });
    
    /*
    |--------------------------------------------------------------------------
    | CARGOS - CURSOS
    |--------------------------------------------------------------------------
    */
    Route::prefix('cargos/cursos')
        ->name('cargos.cursos.')
        ->middleware('screen:cargos/cursos')
        ->group(function () {
            Route::get('/', [CursosController::class, 'index'])->name('index');
            Route::get('/list', [CursosController::class, 'list'])->name('list');
            Route::post('/store', [CursosController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [CursosController::class, 'edit'])->name('edit');
            Route::post('/update/{id}', [CursosController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [CursosController::class, 'delete'])->name('delete');
    });
    
    /*
    |--------------------------------------------------------------------------
    | PESSOAS - COLABORADORES
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'screen:pessoas/colaboradores'])->group(function () {
        Route::get('/pessoas/colaboradores', [ColaboradoresController::class, 'index'])
            ->name('pessoas.colaboradores.index');
    
        Route::get('/pessoas/colaboradores/setores', [ColaboradoresController::class, 'getSetores'])
            ->name('pessoas.colaboradores.setores');
    
        Route::get('/pessoas/colaboradores/cargos', [ColaboradoresController::class, 'getCargos'])
            ->name('pessoas.colaboradores.cargos');
    
        Route::delete('/pessoas/colaboradores/{colaborador}', [ColaboradoresController::class, 'destroy'])
            ->name('pessoas.colaboradores.destroy');
    });
    
    /*
    |--------------------------------------------------------------------------
    | CONFIGURAÇÃO - IMPORTAR PESSOAS
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'screen:configuracao/importar'])->group(function () {
        Route::get('/configuracao/importar', [ImportarColaboradoresController::class, 'index'])
            ->name('configuracao.importar.index');
    
        Route::post('/configuracao/importar', [ImportarColaboradoresController::class, 'store'])
            ->name('configuracao.importar.store');
    });
    
});
