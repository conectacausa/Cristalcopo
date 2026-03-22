<?php

namespace App\Http\Requests\Avaliacoes\Desempenho;

use App\Models\Avaliacoes\Desempenho\AvaliacaoDesempenhoPilar;
use Illuminate\Foundation\Http\FormRequest;

class StoreAvaliacaoDesempenhoPilarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nome' => trim((string) $this->input('nome')),
            'descricao' => $this->nullableString($this->input('descricao')),
            'regras' => $this->normalizarRegras($this->input('regras', [])),
        ]);
    }

    public function rules(): array
    {
        return [
            'ciclo_id' => ['required', 'integer', 'exists:avaliacao_desempenho_ciclos,id'],
            'nome' => ['required', 'string', 'max:150'],
            'descricao' => ['nullable', 'string'],
            'peso' => ['required', 'numeric', 'min:0', 'max:100'],
            'ordem' => ['required', 'integer', 'min:1'],
            'ativo' => ['required', 'boolean'],
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

            $cicloId = (int) $this->input('ciclo_id');
            $pesoAtual = (float) $this->input('peso');
            $somaExistente = (float) AvaliacaoDesempenhoPilar::query()
                ->where('ciclo_id', $cicloId)
                ->sum('peso');

            if (($somaExistente + $pesoAtual) > 100.0001) {
                $validator->errors()->add('peso', 'A soma dos pesos dos pilares do ciclo não pode ultrapassar 100.');
            }
        }];
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
}
