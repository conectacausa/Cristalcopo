<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Colaborador;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AcessoController extends Controller
{
    public function index()
    {
        return view('auth.acesso.index');
    }

    public function validarIdentidade(Request $request): JsonResponse
    {
        $request->validate([
            'cpf' => ['required', 'string'],
            'data_nascimento' => ['required', 'string'],
        ]);

        $cpf = preg_replace('/\D/', '', $request->cpf);
        $dataNascimento = $this->converterDataParaBanco($request->data_nascimento);

        if (!$dataNascimento) {
            return response()->json([
                'status' => 'erro',
                'message' => 'Data de nascimento inválida.',
            ], 422);
        }

        $colaboradores = Colaborador::query()
            ->where('cpf', $cpf)
            ->where('data_nascimento', $dataNascimento)
            ->where('situacao', 1)
            ->get();

        if ($colaboradores->isEmpty()) {
            return response()->json([
                'status' => 'erro',
                'message' => 'CPF e data de nascimento não correspondem.',
            ], 404);
        }

        $existeSenha = $colaboradores->contains(function ($colaborador) {
            return !empty($colaborador->senha);
        });

        if ($existeSenha) {
            return response()->json([
                'status' => 'senha_existente',
                'message' => 'Senha já cadastrada.',
            ], 409);
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'Dados validados com sucesso.',
        ]);
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'cpf' => ['required', 'string'],
            'data_nascimento' => ['required', 'string'],
            'senha' => ['required', 'string', 'min:6'],
            'senha_confirmacao' => ['required', 'same:senha'],
        ], [
            'cpf.required' => 'Informe o CPF.',
            'data_nascimento.required' => 'Informe a data de nascimento.',
            'senha.required' => 'Informe a senha.',
            'senha.min' => 'A senha deve ter no mínimo 6 caracteres.',
            'senha_confirmacao.required' => 'Confirme a senha.',
            'senha_confirmacao.same' => 'As senhas não correspondem.',
        ]);

        $cpf = preg_replace('/\D/', '', $request->cpf);
        $dataNascimento = $this->converterDataParaBanco($request->data_nascimento);

        if (!$dataNascimento) {
            return back()
                ->withInput($request->except('senha', 'senha_confirmacao'))
                ->with('error', 'Data de nascimento inválida.');
        }

        $colaboradores = Colaborador::query()
            ->where('cpf', $cpf)
            ->where('data_nascimento', $dataNascimento)
            ->where('situacao', 1)
            ->get();

        if ($colaboradores->isEmpty()) {
            return back()
                ->withInput($request->except('senha', 'senha_confirmacao'))
                ->with('error', 'CPF e data de nascimento não correspondem.');
        }

        $existeSenha = $colaboradores->contains(function ($colaborador) {
            return !empty($colaborador->senha);
        });

        if ($existeSenha) {
            return back()
                ->withInput($request->except('senha', 'senha_confirmacao'))
                ->with('error', 'Senha já cadastrada.');
        }

        Colaborador::query()
            ->where('cpf', $cpf)
            ->where('data_nascimento', $dataNascimento)
            ->where('situacao', 1)
            ->whereNull('senha')
            ->update([
                'senha' => Hash::make($request->senha),
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('auth.login')
            ->with('success', 'Senha cadastrada com sucesso. Faça seu login.');
    }

    private function converterDataParaBanco(?string $data): ?string
    {
        if (empty($data)) {
            return null;
        }

        try {
            return Carbon::createFromFormat('d/m/Y', $data)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }
}
