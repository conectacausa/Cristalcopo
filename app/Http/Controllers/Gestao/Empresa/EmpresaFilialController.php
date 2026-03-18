<?php

namespace App\Http\Controllers\Gestao\Empresa;

use App\Http\Controllers\Controller;
use App\Models\EmpresaFilial;
use App\Models\EmpresaNatJuridica;
use App\Models\EmpresaPorte;
use App\Models\GestaoCidade;
use App\Models\GestaoEstado;
use App\Models\GestaoPais;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class EmpresaFilialController extends Controller
{
    public function index(Request $request): View
    {
        $query = EmpresaFilial::query()
            ->with([
                'porte:id,descricao',
                'naturezaJuridica:id,descricao',
                'pais:id,nome',
                'estado:id,nome,uf',
                'cidade:id,nome',
            ]);

        if ($request->filled('busca')) {
            $busca = trim((string) $request->input('busca'));
            $buscaNumerica = $this->onlyNumbers($busca);

            $query->where(function ($q) use ($busca, $buscaNumerica) {
                $q->where('razao_social', 'like', "%{$busca}%");

                if (!empty($buscaNumerica)) {
                    $q->orWhere('cnpj', 'like', "%{$buscaNumerica}%");
                }
            });
        }

        $filiais = $query
            ->orderBy('nome_fantasia')
            ->paginate(15)
            ->withQueryString();

        return view('gestao.empresa.filiais.index', compact('filiais'));
    }

    public function create(): View
    {
        $portes = EmpresaPorte::query()
            ->orderBy('descricao')
            ->get(['id', 'descricao']);

        $naturezasJuridicas = EmpresaNatJuridica::query()
            ->orderBy('descricao')
            ->get(['id', 'descricao']);

        $paises = GestaoPais::query()
            ->orderBy('nome')
            ->get(['id', 'nome']);

        return view('gestao.empresa.filiais.create', compact(
            'portes',
            'naturezasJuridicas',
            'paises'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->normalizePayload($request);

        $validated = validator(
            $data,
            $this->rules(),
            $this->messages(),
            $this->attributes()
        )->validate();

        $this->validateGeography($validated);

        EmpresaFilial::create($validated);

        return redirect()
            ->route('empresa.filiais.index')
            ->with('success', 'Filial cadastrada com sucesso.');
    }

    public function edit(int $id): View
    {
        $filial = EmpresaFilial::query()->findOrFail($id);

        $portes = EmpresaPorte::query()
            ->orderBy('descricao')
            ->get(['id', 'descricao']);

        $naturezasJuridicas = EmpresaNatJuridica::query()
            ->orderBy('descricao')
            ->get(['id', 'descricao']);

        $paises = GestaoPais::query()
            ->orderBy('nome')
            ->get(['id', 'nome']);

        $estados = GestaoEstado::query()
            ->where('pais_id', $filial->pais_id)
            ->orderBy('nome')
            ->get(['id', 'nome', 'uf']);

        $cidades = GestaoCidade::query()
            ->where('estado_id', $filial->estado_id)
            ->orderBy('nome')
            ->get(['id', 'nome']);

        return view('gestao.empresa.filiais.edit', compact(
            'filial',
            'portes',
            'naturezasJuridicas',
            'paises',
            'estados',
            'cidades'
        ));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $filial = EmpresaFilial::query()->findOrFail($id);

        $data = $this->normalizePayload($request);

        $validated = validator(
            $data,
            $this->rules($filial->id),
            $this->messages(),
            $this->attributes()
        )->validate();

        $this->validateGeography($validated);

        $filial->update($validated);

        return redirect()
            ->route('empresa.filiais.index')
            ->with('success', 'Filial atualizada com sucesso.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $filial = EmpresaFilial::query()->findOrFail($id);
        $filial->delete();

        return redirect()
            ->route('empresa.filiais.index')
            ->with('success', 'Filial removida com sucesso.');
    }

    public function estadosPorPais(int $paisId): JsonResponse
    {
        $estados = GestaoEstado::query()
            ->where('pais_id', $paisId)
            ->orderBy('nome')
            ->get(['id', 'nome', 'uf']);

        return response()->json($estados);
    }

    public function cidadesPorEstado(int $estadoId): JsonResponse
    {
        $cidades = GestaoCidade::query()
            ->where('estado_id', $estadoId)
            ->orderBy('nome')
            ->get(['id', 'nome']);

        return response()->json($cidades);
    }

    private function rules(?int $id = null): array
    {
        return [
            'razao_social' => ['required', 'string', 'max:200'],
            'cnpj' => [
                'required',
                'string',
                'size:14',
                Rule::unique('empresa_filial', 'cnpj')->ignore($id),
            ],
            'nome_fantasia' => ['required', 'string', 'max:200'],
            'data_abertura' => ['required', 'date'],
            'porte_id' => ['required', 'integer', 'exists:empresa_porte,id'],
            'natureza_juridica_id' => ['required', 'integer', 'exists:empresa_nat_juridica,id'],
            'tipo' => ['required', Rule::in(['matriz', 'filial'])],
            'situacao' => ['required', 'boolean'],
            'telefone1' => ['nullable', 'string', 'max:20'],
            'telefone2' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:150'],
            'logradouro' => ['nullable', 'string', 'max:200'],
            'numero' => ['nullable', 'string', 'max:20'],
            'bairro' => ['nullable', 'string', 'max:150'],
            'cidade_id' => ['required', 'integer', 'exists:gestao_cidade,id'],
            'estado_id' => ['required', 'integer', 'exists:gestao_estado,id'],
            'pais_id' => ['required', 'integer', 'exists:gestao_pais,id'],
            'complemento' => ['nullable', 'string', 'max:200'],
            'cep' => ['nullable', 'string', 'size:8'],
        ];
    }

    private function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'string' => 'O campo :attribute deve ser um texto válido.',
            'integer' => 'O campo :attribute deve ser um número válido.',
            'email' => 'O campo e-mail deve conter um endereço válido.',
            'date' => 'O campo data de abertura deve conter uma data válida.',
            'max' => 'O campo :attribute não pode ter mais de :max caracteres.',
            'size' => 'O campo :attribute deve ter :size caracteres.',
            'exists' => 'O valor informado para :attribute é inválido.',
            'unique' => 'Já existe um registro com este :attribute.',
            'in' => 'O valor informado para :attribute é inválido.',
            'situacao.boolean' => 'A situação informada é inválida.',
        ];
    }

    private function attributes(): array
    {
        return [
            'razao_social' => 'razão social',
            'cnpj' => 'CNPJ',
            'nome_fantasia' => 'nome fantasia',
            'data_abertura' => 'data de abertura',
            'porte_id' => 'porte',
            'natureza_juridica_id' => 'natureza jurídica',
            'tipo' => 'tipo',
            'situacao' => 'situação',
            'telefone1' => 'telefone 1',
            'telefone2' => 'telefone 2',
            'email' => 'e-mail',
            'logradouro' => 'logradouro',
            'numero' => 'número',
            'bairro' => 'bairro',
            'cidade_id' => 'cidade',
            'estado_id' => 'estado',
            'pais_id' => 'país',
            'complemento' => 'complemento',
            'cep' => 'CEP',
        ];
    }

    private function normalizePayload(Request $request): array
    {
        return [
            'razao_social' => trim((string) $request->input('razao_social')),
            'cnpj' => $this->onlyNumbers($request->input('cnpj')),
            'nome_fantasia' => trim((string) $request->input('nome_fantasia')),
            'data_abertura' => $request->input('data_abertura'),
            'porte_id' => $request->input('porte_id'),
            'natureza_juridica_id' => $request->input('natureza_juridica_id'),
            'tipo' => $request->input('tipo'),
            'situacao' => filter_var($request->input('situacao'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            'telefone1' => $this->nullableString($this->onlyNumbers($request->input('telefone1'))),
            'telefone2' => $this->nullableString($this->onlyNumbers($request->input('telefone2'))),
            'email' => $this->nullableString(mb_strtolower(trim((string) $request->input('email')))),
            'logradouro' => $this->nullableString(trim((string) $request->input('logradouro'))),
            'numero' => $this->nullableString(trim((string) $request->input('numero'))),
            'bairro' => $this->nullableString(trim((string) $request->input('bairro'))),
            'cidade_id' => $request->input('cidade_id'),
            'estado_id' => $request->input('estado_id'),
            'pais_id' => $request->input('pais_id'),
            'complemento' => $this->nullableString(trim((string) $request->input('complemento'))),
            'cep' => $this->nullableString($this->onlyNumbers($request->input('cep'))),
        ];
    }

    private function validateGeography(array $data): void
    {
        $estado = GestaoEstado::query()
            ->where('id', $data['estado_id'])
            ->where('pais_id', $data['pais_id'])
            ->first();

        if (!$estado) {
            throw ValidationException::withMessages([
                'estado_id' => 'O estado informado não pertence ao país selecionado.',
            ]);
        }

        $cidade = GestaoCidade::query()
            ->where('id', $data['cidade_id'])
            ->where('estado_id', $data['estado_id'])
            ->first();

        if (!$cidade) {
            throw ValidationException::withMessages([
                'cidade_id' => 'A cidade informada não pertence ao estado selecionado.',
            ]);
        }
    }

    private function onlyNumbers(null|string $value): string
    {
        return preg_replace('/\D+/', '', $value ?? '') ?? '';
    }

    private function nullableString(?string $value): ?string
    {
        $value = is_string($value) ? trim($value) : $value;

        return $value === '' ? null : $value;
    }
}
