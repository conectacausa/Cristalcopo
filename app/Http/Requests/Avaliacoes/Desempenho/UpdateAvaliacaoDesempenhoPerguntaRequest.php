<?php

namespace App\Http\Requests\Avaliacoes\Desempenho;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAvaliacaoDesempenhoPerguntaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'descricao_apoio' => $this->nullableString($this->input('descricao_apoio')),
            'peso' => $this->nullableNumeric($this->input('peso')),
            'opcoes' => $this->normalizarOpcoes($this->input('opcoes', [])),
            'regras' => $this->normalizarRegras($this->input('regras', [])),
        ]);
    }

    public function rules(): array
    {
        return [
            'ciclo_id' => ['required', 'integer', 'exists:avaliacao_desempenho_ciclos,id'],
            'pilar_id' => ['nullable', 'integer', 'exists:avaliacao_desempenho_pilares,id'],
            'grupo_id' => ['nullable', 'integer', 'exists:avaliacao_desempenho_grupos,id'],
            'subgrupo_id' => ['nullable', 'integer', 'exists:avaliacao_desempenho_subgrupos,id'],
            'enunciado' => ['required', 'string'],
            'descricao_apoio' => ['nullable', 'string'],
            'tipo_resposta' => ['required', Rule::in(['escala_1_5', 'escala_0_10', 'sim_nao', 'multipla_escolha', 'texto_aberto'])],
            'obrigatoria' => ['required', 'boolean'],
            'peso' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'ordem' => ['required', 'integer', 'min:1'],
            'ativa' => ['required', 'boolean'],
            'permite_comentario' => ['required', 'boolean'],
            'comentario_obrigatorio' => ['required', 'boolean'],
            'opcoes' => ['nullable', 'array'],
            'opcoes.*.texto' => ['required_with:opcoes', 'string', 'max:255'],
            'opcoes.*.valor' => ['nullable', 'string', 'max:100'],
            'opcoes.*.ordem' => ['required_with:opcoes', 'integer', 'min:1'],
            'opcoes.*.ativa' => ['required_with:opcoes', 'boolean'],
            'regras' => ['nullable', 'array'],
            'regras.*.regra_tipo' => ['required_with:regras', 'string', 'in:cargo,filial,setor'],
            'regras.*.referencia_id' => ['required_with:regras', 'integer', 'min:1'],
        ];
    }

    public function after(): array
    {
        return [function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            if ($this->input('tipo_resposta') === 'multipla_escolha' && count((array) $this->input('opcoes', [])) === 0) {
                $validator->errors()->add('opcoes', 'Informe ao menos uma opção para perguntas de múltipla escolha.');
            }

            if ($this->boolean('comentario_obrigatorio') && ! $this->boolean('permite_comentario')) {
                $validator->errors()->add('comentario_obrigatorio', 'Comentário obrigatório exige que o comentário esteja habilitado.');
            }
        }];
    }

    private function normalizarOpcoes(array $opcoes): array
    {
        return collect($opcoes)
            ->map(fn ($item) => [
                'texto' => trim((string) ($item['texto'] ?? '')),
                'valor' => $this->nullableString($item['valor'] ?? null),
                'ordem' => isset($item['ordem']) ? (int) $item['ordem'] : 1,
                'ativa' => filter_var($item['ativa'] ?? true, FILTER_VALIDATE_BOOLEAN),
            ])
            ->filter(fn ($item) => $item['texto'] !== '')
            ->values()
            ->all();
    }

    private function normalizarRegras(array $regras): array
    {
        return collect($regras)
            ->map(fn ($item) => [
                'regra_tipo' => $item['regra_tipo'] ?? null,
                'referencia_id' => isset($item['referencia_id']) ? (int) $item['referencia_id'] : null,
            ])
            ->filter(fn ($item) => ! empty($item['regra_tipo']) && ! empty($item['referencia_id']))
            ->values()
            ->all();
    }

    private function nullableString(mixed $value): ?string
    {
        $value = trim((string) $value);
        return $value === '' ? null : $value;
    }

    private function nullableNumeric(mixed $value): float|int|null
    {
        return $value === null || $value === '' ? null : (float) $value;
    }
}
