<?php

namespace App\Http\Controllers\Cargos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CursosController extends Controller
{
    public function index()
    {
        $permissoes = $this->getPermissoes();
        abort_unless($permissoes['pode_ler'], 403);

        return view('cargos.cursos.index', compact('permissoes'));
    }

    public function list(Request $request)
    {
        $permissoes = $this->getPermissoes();
        abort_unless($permissoes['pode_ler'], 403);

        $query = DB::table('cargos_cursos')->whereNull('deleted_at');

        if ($request->filled('busca')) {
            $query->where('descricao', 'ilike', '%' . trim($request->busca) . '%');
        }

        $dados = $query->orderBy('descricao')->paginate(25);

        return view('cargos.cursos.partials.table', compact('dados', 'permissoes'))->render();
    }

    public function store(Request $request)
    {
        abort_unless($this->getPermissoes()['pode_gravar'], 403);

        $request->validate([
            'descricao' => ['required', 'string', 'max:255'],
        ]);

        DB::table('cargos_cursos')->insert([
            'descricao' => trim($request->descricao),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Curso salvo com sucesso.']);
    }

    public function edit($id)
    {
        abort_unless($this->getPermissoes()['pode_editar'], 403);

        $registro = DB::table('cargos_cursos')->where('id', $id)->whereNull('deleted_at')->first();
        abort_if(!$registro, 404);

        return response()->json(['success' => true, 'data' => $registro]);
    }

    public function update(Request $request, $id)
    {
        abort_unless($this->getPermissoes()['pode_editar'], 403);

        $request->validate([
            'descricao' => ['required', 'string', 'max:255'],
        ]);

        DB::table('cargos_cursos')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->update([
                'descricao' => trim($request->descricao),
                'updated_at' => now(),
            ]);

        return response()->json(['success' => true, 'message' => 'Curso atualizado com sucesso.']);
    }

    public function delete($id)
    {
        abort_unless($this->getPermissoes()['pode_excluir'], 403);

        DB::table('cargos_cursos')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->update([
                'deleted_at' => now(),
                'updated_at' => now(),
            ]);

        return response()->json(['success' => true, 'message' => 'Curso excluído com sucesso.']);
    }

    private function getPermissoes(): array
    {
        $user = Auth::user();

        $registro = DB::table('vinculo_permissao_x_tela')
            ->join('gestao_tela', 'gestao_tela.id', '=', 'vinculo_permissao_x_tela.tela_id')
            ->where('vinculo_permissao_x_tela.permissao_id', $user->permissao_id)
            ->where('gestao_tela.slug', 'cargos/cursos')
            ->select(
                DB::raw('COALESCE(vinculo_permissao_x_tela.pode_ler, false) as pode_ler'),
                DB::raw('COALESCE(vinculo_permissao_x_tela.pode_gravar, false) as pode_gravar'),
                DB::raw('COALESCE(vinculo_permissao_x_tela.pode_editar, false) as pode_editar'),
                DB::raw('COALESCE(vinculo_permissao_x_tela.pode_excluir, false) as pode_excluir')
            )
            ->first();

        return [
            'pode_ler' => (bool)($registro->pode_ler ?? false),
            'pode_gravar' => (bool)($registro->pode_gravar ?? false),
            'pode_editar' => (bool)($registro->pode_editar ?? false),
            'pode_excluir' => (bool)($registro->pode_excluir ?? false),
        ];
    }
}
