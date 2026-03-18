<?php

namespace App\Http\Controllers\Gestao\Empresa;

use App\Http\Controllers\Controller;
use App\Models\EmpresaCnae;
use App\Models\EmpresaFilial;
use App\Models\EmpresaNatJuridica;
use App\Models\EmpresaPorte;
use App\Models\GestaoCidade;
use App\Models\GestaoEstado;
use App\Models\GestaoPais;
use App\Models\VinculoFilialXCnae;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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
            ->orderBy('codigo')
            ->get(['id', 'codigo', 'descricao']);

        $naturezasJuridicas = EmpresaNatJuridica::query()
            ->orderBy('codigo')
            ->get(['id', 'codigo', 'descricao']);

        $paises = GestaoPais::query()
            ->orderBy('nome')
            ->get(['id', 'nome', 'iso2', 'iso3']);

        return view('gestao.empresa.filiais.create', [
            'portes' => $portes,
            'naturezasJuridicas' => $naturezasJuridicas,
            'paises' => $paises,
            'estados' => collect(),
            'cidades' => collect(),
        ]);
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

        $filial = null;

        DB::transaction(function () use (&$filial, $validated) {
            $filial = EmpresaFilial::create($validated);

            if (request()->filled('cnaes')) {
                $this->syncCnaesFromRequest($filial, request()->input('cnaes', []));
            }
        });

        return redirect()
            ->route('empresa.filiais.edit', $filial->id)
            ->with('success', 'Filial cadastrada com sucesso.');
    }

    public function edit(int $id): View
    {
        $filial = EmpresaFilial::query()
            ->with([
                'porte:id,codigo,descricao',
                'naturezaJuridica:id,codigo,descricao',
                'pais:id,nome,iso2,iso3',
                'estado:id,nome,uf,pais_id',
                'cidade:id,nome,estado_id',
            ])
            ->findOrFail($id);

        $portes = EmpresaPorte::query()
            ->orderBy('codigo')
            ->get(['id', 'codigo', 'descricao']);

        $naturezasJuridicas = EmpresaNatJuridica::query()
            ->orderBy('codigo')
            ->get(['id', 'codigo', 'descricao']);

        $paises = GestaoPais::query()
            ->orderBy('nome')
            ->get(['id', 'nome', 'iso2', 'iso3']);

        $estados = GestaoEstado::query()
            ->where('pais_id', $filial->pais_id)
            ->orderBy('nome')
            ->get(['id', 'nome', 'uf', 'pais_id']);

        $cidades = GestaoCidade::query()
            ->where('estado_id', $filial->estado_id)
            ->orderBy('nome')
            ->get(['id', 'nome', 'estado_id']);

        $cnaes = VinculoFilialXCnae::query()
            ->with(['cnae:id,subclasse,descricao'])
            ->where('filial_id', $filial->id)
            ->orderByDesc('principal')
            ->join('empresa_cnae', 'empresa_cnae.id', '=', 'vinculo_filial_x_cnae.cnae_id')
            ->orderBy('empresa_cnae.subclasse')
            ->select('vinculo_filial_x_cnae.*')
            ->get();

        return view('gestao.empresa.filiais.edit', compact(
            'filial',
            'portes',
            'naturezasJuridicas',
            'paises',
            'estados',
            'cidades',
            'cnaes'
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

        DB::transaction(function () use ($filial, $validated, $request) {
            $filial->update($validated);

            if ($request->filled('cnaes')) {
                $this->syncCnaesFromRequest($filial, $request->input('cnaes', []));
            }
        });

        return redirect()
            ->route('empresa.filiais.edit', $filial->id)
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

    public function buscarPortePorCodigo(string $codigo): JsonResponse
    {
        $porte = EmpresaPorte::query()
            ->where('codigo', $codigo)
            ->first(['id', 'codigo', 'descricao']);

        return response()->json([
            'found' => (bool) $porte,
            'data' => $porte,
        ]);
    }

    public function buscarNaturezaPorCodigo(string $codigo): JsonResponse
    {
        $natureza = EmpresaNatJuridica::query()
            ->where('codigo', $codigo)
            ->first(['id', 'codigo', 'descricao']);

        return response()->json([
            'found' => (bool) $natureza,
            'data' => $natureza,
        ]);
    }

    public function buscarCnaePorSubclasse(string $subclasse): JsonResponse
    {
        $subclasse = $this->normalizeCnae($subclasse);

        $cnae = EmpresaCnae::query()
            ->where('subclasse', $subclasse)
            ->first(['id', 'subclasse', 'descricao']);

        return response()->json([
            'found' => (bool) $cnae,
            'data' => $cnae,
        ]);
    }

    public function adicionarCnae(Request $request, int $filialId): JsonResponse
    {
        $filial = EmpresaFilial::query()->findOrFail($filialId);

        $validated = $request->validate([
            'subclasse' => ['required', 'string', 'max:15'],
            'principal' => ['nullable', 'boolean'],
        ]);

        $subclasse = $this->normalizeCnae($validated['subclasse']);

        $cnae = EmpresaCnae::query()
            ->where('subclasse', $subclasse)
            ->first();

        if (!$cnae) {
            return response()->json([
                'success' => false,
                'message' => 'CNAE não encontrado.',
            ], 422);
        }

        DB::transaction(function () use ($filial, $cnae, $validated) {
            $principal = (bool) ($validated['principal'] ?? false);

            if ($principal) {
                VinculoFilialXCnae::query()
                    ->where('filial_id', $filial->id)
                    ->whereNull('deleted_at')
                    ->update(['principal' => false]);
            }

            VinculoFilialXCnae::query()->updateOrCreate(
                [
                    'filial_id' => $filial->id,
                    'cnae_id' => $cnae->id,
                ],
                [
                    'principal' => $principal,
                    'deleted_at' => null,
                ]
            );
        });

        return response()->json([
            'success' => true,
            'message' => 'CNAE adicionado com sucesso.',
            'data' => $this->listarCnaesFilial($filial->id),
        ]);
    }

    public function atualizarPrincipalCnae(Request $request, int $vinculoId): JsonResponse
    {
        $vinculo = VinculoFilialXCnae::query()->findOrFail($vinculoId);

        $validated = $request->validate([
            'principal' => ['required', 'boolean'],
        ]);

        DB::transaction(function () use ($vinculo, $validated) {
            if ($validated['principal']) {
                VinculoFilialXCnae::query()
                    ->where('filial_id', $vinculo->filial_id)
                    ->whereNull('deleted_at')
                    ->update(['principal' => false]);
            }

            $vinculo->update([
                'principal' => $validated['principal'],
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Prioridade atualizada com sucesso.',
            'data' => $this->listarCnaesFilial($vinculo->filial_id),
        ]);
    }

    public function removerCnae(int $vinculoId): JsonResponse
    {
        $vinculo = VinculoFilialXCnae::query()->findOrFail($vinculoId);
        $filialId = $vinculo->filial_id;

        $vinculo->delete();

        return response()->json([
            'success' => true,
            'message' => 'CNAE removido com sucesso.',
            'data' => $this->listarCnaesFilial($filialId),
        ]);
    }

    public function consultarCnpj(string $cnpj): JsonResponse
    {
        $cnpj = $this->onlyNumbers($cnpj);

        if (strlen($cnpj) !== 14) {
            return response()->json([
                'success' => false,
                'message' => 'CNPJ inválido.',
            ], 422);
        }

        $response = Http::timeout(20)->get("https://api.opencnpj.org/{$cnpj}");

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível consultar o CNPJ na API.',
            ], 422);
        }

        $payload = $response->json();

        if (!is_array($payload)) {
            return response()->json([
                'success' => false,
                'message' => 'Resposta inválida da API.',
            ], 422);
        }

        $referencias = DB::transaction(function () use ($payload) {
            return $this->garantirReferenciasDaApi($payload);
        });

        return response()->json([
            'success' => true,
            'message' => 'CNPJ consultado com sucesso.',
            'data' => [
                'api' => $payload,
                'referencias' => $referencias,
            ],
        ]);
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

    private function garantirReferenciasDaApi(array $payload): array
    {
        $pais = GestaoPais::query()->firstOrCreate(
            ['iso2' => 'BR'],
            [
                'nome' => 'Brasil',
                'iso3' => 'BRA',
            ]
        );

        $estado = GestaoEstado::query()->firstOrCreate(
            [
                'pais_id' => $pais->id,
                'uf' => (string) ($payload['uf'] ?? ''),
            ],
            [
                'nome' => (string) ($payload['uf'] ?? ''),
                'codigo_ibge' => null,
            ]
        );

        $cidade = GestaoCidade::query()->firstOrCreate(
            [
                'estado_id' => $estado->id,
                'nome' => trim((string) ($payload['municipio'] ?? '')),
            ],
            [
                'codigo_ibge' => null,
            ]
        );

        $porte = EmpresaPorte::query()->firstOrCreate(
            ['descricao' => trim((string) ($payload['porte_empresa'] ?? ''))],
            [
                'codigo' => $this->nextCodigo(EmpresaPorte::class),
            ]
        );

        $natureza = EmpresaNatJuridica::query()->firstOrCreate(
            ['descricao' => trim((string) ($payload['natureza_juridica'] ?? ''))],
            [
                'codigo' => $this->nextCodigo(EmpresaNatJuridica::class),
            ]
        );

        $cnaePrincipal = null;

        if (!empty($payload['cnae_principal'])) {
            $cnaePrincipal = EmpresaCnae::query()->firstOrCreate(
                ['subclasse' => $this->normalizeCnae((string) $payload['cnae_principal'])],
                [
                    'descricao' => 'CNAE importado via API',
                ]
            );
        }

        $cnaesSecundarios = [];

        foreach (($payload['cnaes_secundarios'] ?? []) as $subclasse) {
            $cnaesSecundarios[] = EmpresaCnae::query()->firstOrCreate(
                ['subclasse' => $this->normalizeCnae((string) $subclasse)],
                [
                    'descricao' => 'CNAE importado via API',
                ]
            );
        }

        return [
            'pais' => $pais,
            'estado' => $estado,
            'cidade' => $cidade,
            'porte' => $porte,
            'natureza_juridica' => $natureza,
            'cnae_principal' => $cnaePrincipal,
            'cnaes_secundarios' => $cnaesSecundarios,
        ];
    }

    private function syncCnaesFromRequest(EmpresaFilial $filial, array $cnaes): void
    {
        VinculoFilialXCnae::query()
            ->where('filial_id', $filial->id)
            ->delete();

        $temPrincipal = false;

        foreach ($cnaes as $item) {
            if (empty($item['subclasse'])) {
                continue;
            }

            $subclasse = $this->normalizeCnae((string) $item['subclasse']);

            $cnae = EmpresaCnae::query()->where('subclasse', $subclasse)->first();

            if (!$cnae) {
                continue;
            }

            $principal = !$temPrincipal && !empty($item['principal']);

            VinculoFilialXCnae::query()->create([
                'filial_id' => $filial->id,
                'cnae_id' => $cnae->id,
                'principal' => $principal,
            ]);

            if ($principal) {
                $temPrincipal = true;
            }
        }
    }

    private function listarCnaesFilial(int $filialId): array
    {
        return VinculoFilialXCnae::query()
            ->with(['cnae:id,subclasse,descricao'])
            ->where('filial_id', $filialId)
            ->whereNull('vinculo_filial_x_cnae.deleted_at')
            ->join('empresa_cnae', 'empresa_cnae.id', '=', 'vinculo_filial_x_cnae.cnae_id')
            ->orderByDesc('vinculo_filial_x_cnae.principal')
            ->orderBy('empresa_cnae.subclasse')
            ->get([
                'vinculo_filial_x_cnae.id',
                'vinculo_filial_x_cnae.filial_id',
                'vinculo_filial_x_cnae.cnae_id',
                'vinculo_filial_x_cnae.principal',
            ])
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'filial_id' => $item->filial_id,
                    'cnae_id' => $item->cnae_id,
                    'principal' => (bool) $item->principal,
                    'subclasse' => $item->cnae?->subclasse,
                    'descricao' => $item->cnae?->descricao,
                ];
            })
            ->values()
            ->toArray();
    }

    private function nextCodigo(string $modelClass): string
    {
        $ultimoCodigo = $modelClass::query()
            ->select('codigo')
            ->whereRaw("codigo ~ '^[0-9]+$'")
            ->orderByRaw('CAST(codigo AS INTEGER) DESC')
            ->value('codigo');

        return str_pad(((int) $ultimoCodigo) + 1, 3, '0', STR_PAD_LEFT);
    }

    private function normalizeCnae(?string $value): string
    {
        $value = $this->onlyNumbers($value);

        if (strlen($value) !== 7) {
            return $value;
        }

        return substr($value, 0, 2) . '.' .
            substr($value, 2, 2) . '-' .
            substr($value, 4, 1) . '-' .
            substr($value, 5, 2);
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
