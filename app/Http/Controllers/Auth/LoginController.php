<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Colaborador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        if (session()->has('colaborador_id')) {
            return $this->redirectAfterLogin($request, session('colaborador_id'));
        }

        return view('auth.login.index', [
            'returnurl' => $request->query('returnurl'),
        ]);
    }

    public function autenticar(Request $request)
    {
        $request->validate([
            'cpf' => ['required', 'string'],
            'senha' => ['nullable', 'string'],
            'lembrar' => ['nullable'],
            'returnurl' => ['nullable', 'string'],
        ], [
            'cpf.required' => 'Informe o CPF.',
        ]);

        $cpf = preg_replace('/\D/', '', $request->cpf);

        $colaboradores = Colaborador::with('permissao.loginTela')
            ->where('cpf', $cpf)
            ->where('situacao', 1)
            ->get();

        if ($colaboradores->isEmpty()) {
            return back()
                ->withInput($request->except('senha'))
                ->withErrors([
                    'cpf' => 'CPF ou senha inválidos.',
                ]);
        }

        $colaboradorValido = null;

        foreach ($colaboradores as $colaborador) {
            if (empty($colaborador->senha)) {
                continue;
            }

            if (Hash::check($request->senha ?? '', $colaborador->senha)) {
                $colaboradorValido = $colaborador;
                break;
            }
        }

        if (!$colaboradorValido) {
            return back()
                ->withInput($request->except('senha'))
                ->withErrors([
                    'cpf' => 'CPF ou senha inválidos.',
                ]);
        }

        if (
            !$colaboradorValido->permissao ||
            !$colaboradorValido->permissao->situacao
        ) {
            return back()
                ->withInput($request->except('senha'))
                ->withErrors([
                    'cpf' => 'Sua permissão de acesso está inativa.',
                ]);
        }

        session([
            'colaborador_id' => $colaboradorValido->id,
            'colaborador_nome' => $colaboradorValido->nome_completo,
            'permissao_id' => $colaboradorValido->permissao_id,
        ]);

        if ($request->boolean('lembrar')) {
            session()->put('login_lembrar', true);
        } else {
            session()->forget('login_lembrar');
        }

        $request->session()->regenerate();

        return $this->redirectAfterLogin($request, $colaboradorValido->id);
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login');
    }

    private function redirectAfterLogin(Request $request, int $colaboradorId)
    {
        $colaborador = Colaborador::with('permissao.loginTela')->find($colaboradorId);

        if (!$colaborador) {
            $request->session()->flush();
            return redirect()->route('auth.login');
        }

        $returnurl = $request->query('returnurl') ?? $request->input('returnurl');

        if (!empty($returnurl) && $this->isSafeInternalReturnUrl($returnurl)) {
            return redirect($returnurl);
        }

        $slug = optional(optional($colaborador->permissao)->loginTela)->slug;

        if (!empty($slug)) {
            return redirect('/' . ltrim($slug, '/'));
        }

        return redirect('/');
    }

    private function isSafeInternalReturnUrl(string $url): bool
    {
        return str_starts_with($url, '/') && !str_starts_with($url, '//');
    }
}
