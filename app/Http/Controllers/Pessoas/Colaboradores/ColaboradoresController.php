<?php

namespace App\Http\Controllers\Pessoas\Colaboradores;

use App\Http\Controllers\Controller;
use App\Models\Pessoas\Colaborador;
use App\Models\EmpresaFilial;
use App\Models\EmpresaSetor;
use App\Models\Cargos\Cargo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class ColaboradoresController extends Controller
{
    public function index(Request $request)
    {
        $permissao = DB::table('vinculo_permissao_x_tela')
            ->join('gestao_tela', 'gestao_tela.id', '=', 'vinculo_permissao_x_tela.tela_id')
            ->where('gestao_tela.slug', 'pessoas/colaboradores')
            ->where('vinculo_permissao_x_tela.permissao_id', auth()->user()->permissao_id)
            ->first();

        abort_unless($permissao && $permissao->pode_ler, 403);

        $texto = trim((string) $request->get('texto'));
        $filiais = array_filter((array) $request->get('filiais', []));
        $setores = array_filter((array) $request->get('setores', []));
        $cargos = array_filter((array) $request->get('cargos', []));
        $situacao = $request->get('situacao', 'ativo');

        $query = Colaborador::query()
            ->with(['cargo', 'filial', 'setor'])
            ->when($texto !== '', function ($q) use ($texto) {
                $cpf = preg_replace('/\D/', '', $texto);

                $q->where(function ($sub) use ($texto, $cpf) {
                    $sub->where('nome_completo', 'ILIKE', "%{$texto}%")
                        ->orWhere('matricula', 'ILIKE', "%{$texto}%");

                    if ($cpf !== '') {
                        $sub->orWhere('cpf', 'ILIKE', "%{$cpf}%");
                    }
                });
            })
            ->when(!empty($filiais), fn($q) => $q->whereIn('filial_id', $filiais))
            ->when(!empty($setores), fn($q) => $q->whereIn('setor_id', $setores))
            ->when(!empty($cargos), fn($q) => $q->whereIn('cargo_id', $cargos))
            ->when($situacao === 'ativo', fn($q) => $q->where('situacao', true))
            ->when($situacao === 'inativo', fn($q) => $q->where('situacao', false))
            ->orderBy('nome_completo')
            ->paginate(25)
            ->withQueryString();

        $filiaisLista = EmpresaFilial::where('situacao', true)
            ->orderBy('nome_fantasia')
            ->get();

        return view('pessoas.colaboradores.index', [
            'colaboradores' => $query,
            'filiaisLista' => $filiaisLista,
            'permissao' => $permissao,
            'filtros' => compact('texto', 'filiais', 'setores', 'cargos', 'situacao'),
        ]);
    }

    public function getSetores(Request $request): JsonResponse
    {
        $filiais = array_filter((array) $request->get('filiais', []));

        $setores = EmpresaSetor::query()
            ->when(!empty($filiais), function ($q) use ($filiais) {
                $q->whereIn('id', function ($sub) use ($filiais) {
                    $sub->select('setor_id')
                        ->from('vinculo_filial_x_setor')
                        ->whereIn('filial_id', $filiais);
                });
            }, function ($q) {
                $q->whereRaw('1 = 0');
            })
            ->orderBy('descricao')
            ->get(['id', 'descricao']);

        return response()->json($setores);
    }

    public function getCargos(Request $request): JsonResponse
    {
        $setores = array_filter((array) $request->get('setores', []));

        $cargos = Cargo::query()
            ->when(!empty($setores), function ($q) use ($setores) {
                $q->whereIn('id', function ($sub) use ($setores) {
                    $sub->select('cargo_id')
                        ->from('vinculo_cargo_x_setor')
                        ->whereIn('setor_id', $setores);
                });
            }, function ($q) {
                $q->whereRaw('1 = 0');
            })
            ->orderBy('titulo_cargo')
            ->get(['id', 'titulo_cargo']);

        return response()->json($cargos);
    }

    public function destroy(Colaborador $colaborador)
    {
        $colaborador->delete();

        return redirect()
            ->route('pessoas.colaboradores.index')
            ->with('success', 'Colaborador excluído com sucesso.');
    }
}
