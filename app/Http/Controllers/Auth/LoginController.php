<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Colaborador;
use App\Models\VinculoPermissaoXTela;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectAutenticado($request);
        }

        $returnurl = (string) $request->query('returnurl', '');

        return view('auth.login.index', [
            'returnurl' => $returnurl,
        ]);
    }

    public function autenticar(Request $request): RedirectResponse
    {
        $request->validate([
            'cpf' => ['required', 'string'],
            'senha' => ['required', 'string'],
        ]);

        $cpf = preg_replace('/\D/', '', (string) $request->cpf);

        $colaborador = Colaborador::query()
            ->with(['permissao.loginTela'])
            ->where('cpf', $cpf)
            ->where('situacao', 1)
            ->whereNotNull('senha')
            ->first();

        if (! $colaborador || ! Hash::check($request->senha, $colaborador->senha)) {
            return back()
                ->withInput($request->only('cpf', 'returnurl'))
                ->with('error', 'CPF ou senha inválidos.');
        }

        if (! $colaborador->permissao || (int) $colaborador->permissao->situacao !== 1) {
            return back()
                ->withInput($request->only('cpf', 'returnurl'))
                ->with('error', 'Usuário sem permissão ativa para acessar o sistema.');
        }

        Auth::login($colaborador, $request->boolean('lembrar'));
        $request->session()->regenerate();

        return $this->resolveRedirectAfterLogin($request, $colaborador);
    }

    public function redirectAutenticado(Request $request): RedirectResponse
    {
        /** @var \App\Models\Colaborador|null $user */
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('auth.login');
        }

        $user->loadMissing(['permissao.loginTela']);

        return $this->resolveRedirectAfterLogin($request, $user);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('auth.login')
            ->with('success', 'Logout realizado com sucesso.');
    }

    protected function resolveRedirectAfterLogin(Request $request, Colaborador $user): RedirectResponse
    {
        $returnUrl = (string) $request->input('returnurl', $request->query('returnurl', ''));

        if ($this->isSafeInternalPath($returnUrl)) {
            return redirect($returnUrl);
        }

        $slug = $this->normalizeSlug($user->permissao?->loginTela?->slug);

        if (! empty($slug) && $this->userHasAccessToSlug($user->permissao_id, $slug)) {
            if ($slug === 'dashboard') {
                return redirect()->route('dashboard');
            }

            return redirect('/' . $slug);
        }

        $firstAllowedSlug = $this->normalizeSlug($this->firstAllowedSlug($user->permissao_id));

        if (! empty($firstAllowedSlug)) {
            if ($firstAllowedSlug === 'dashboard') {
                return redirect()->route('dashboard');
            }

            return redirect('/' . $firstAllowedSlug);
        }

        abort(403, 'Nenhuma tela autorizada foi encontrada para esta permissão.');
    }

    protected function userHasAccessToSlug(?int $permissaoId, string $slug): bool
    {
        if (empty($permissaoId) || empty($slug)) {
            return false;
        }

        return VinculoPermissaoXTela::query()
            ->where('permissao_id', $permissaoId)
            ->whereHas('tela', function ($query) use ($slug) {
                $query->where('slug', $slug)
                    ->orWhere('slug', '/' . $slug);
            })
            ->exists();
    }

    protected function firstAllowedSlug(?int $permissaoId): ?string
    {
        if (empty($permissaoId)) {
            return null;
        }

        $vinculo = VinculoPermissaoXTela::query()
            ->with('tela')
            ->where('permissao_id', $permissaoId)
            ->orderBy('id')
            ->first();

        return $vinculo?->tela?->slug;
    }

    protected function normalizeSlug(?string $slug): ?string
    {
        if ($slug === null) {
            return null;
        }

        $slug = trim($slug);

        if ($slug === '') {
            return null;
        }

        return ltrim($slug, '/');
    }

    protected function isSafeInternalPath(string $path): bool
    {
        if ($path === '') {
            return false;
        }

        if (! str_starts_with($path, '/')) {
            return false;
        }

        return ! str_starts_with($path, '//');
    }
}
