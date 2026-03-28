<?php

namespace App\Http\Controllers\Configuracao;

use App\Http\Controllers\Controller;
use App\Http\Requests\Configuracao\StorePaisRequest;
use App\Http\Requests\Configuracao\UpdatePaisRequest;
use App\Models\GestaoPais;
use App\Services\Configuracao\PaisService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class PaisController extends Controller
{
    public function __construct(
        private readonly PaisService $paisService,
    ) {
    }

    public function index()
    {
        $permissoes = $this->getPermissoes();
        abort_unless($permissoes['can_view'], 403);

        return view('configuracao.pais.index', compact('permissoes'));
    }

    public function list(Request $request)
    {
        try {
            $permissoes = $this->getPermissoes();
            abort_unless($permissoes['can_view'], 403);

            $dados = $this->paisService->listar($request->input('descricao'));

            return view('configuracao.pais.partials.table', compact('dados', 'permissoes'))->render();
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Erro ao carregar países.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(StorePaisRequest $request): JsonResponse
    {
        $permissoes = $this->getPermissoes();
        abort_unless($permissoes['can_create'], 403);

        try {
            $this->paisService->criar($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'País cadastrado com sucesso.',
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao cadastrar país.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit(int $id): JsonResponse
    {
        try {
            $permissoes = $this->getPermissoes();
            abort_unless($permissoes['can_edit'], 403);

            $pais = GestaoPais::query()->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $pais,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar país.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdatePaisRequest $request, int $id): JsonResponse
    {
        $permissoes = $this->getPermissoes();
        abort_unless($permissoes['can_edit'], 403);

        try {
            $pais = GestaoPais::query()->findOrFail($id);
            $this->paisService->atualizar($pais, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'País atualizado com sucesso.',
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar país.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete(int $id): JsonResponse
    {
        $permissoes = $this->getPermissoes();
        abort_unless($permissoes['can_delete'], 403);

        try {
            DB::transaction(function () use ($id) {
                $pais = GestaoPais::query()->findOrFail($id);
                $this->paisService->excluir($pais);
            });

            return response()->json([
                'success' => true,
                'message' => 'País excluído com sucesso.',
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir país.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function getPermissoes(): array
    {
        $user = Auth::user();

        $registro = DB::table('vinculo_permissao_x_tela')
            ->join('gestao_tela', 'gestao_tela.id', '=', 'vinculo_permissao_x_tela.tela_id')
            ->where('vinculo_permissao_x_tela.permissao_id', $user->permissao_id)
            ->where('gestao_tela.slug', 'configuracao/pais')
            ->select(
                DB::raw('COALESCE(vinculo_permissao_x_tela.pode_ler, false) as pode_ler'),
                DB::raw('COALESCE(vinculo_permissao_x_tela.pode_gravar, false) as pode_gravar'),
                DB::raw('COALESCE(vinculo_permissao_x_tela.pode_editar, false) as pode_editar'),
                DB::raw('COALESCE(vinculo_permissao_x_tela.pode_excluir, false) as pode_excluir')
            )
            ->first();

        return [
            'can_view' => (bool) ($registro->pode_ler ?? false),
            'can_create' => (bool) ($registro->pode_gravar ?? false),
            'can_edit' => (bool) ($registro->pode_editar ?? false),
            'can_delete' => (bool) ($registro->pode_excluir ?? false),
            'pode_ler' => (bool) ($registro->pode_ler ?? false),
            'pode_gravar' => (bool) ($registro->pode_gravar ?? false),
            'pode_editar' => (bool) ($registro->pode_editar ?? false),
            'pode_excluir' => (bool) ($registro->pode_excluir ?? false),
        ];
    }
}
