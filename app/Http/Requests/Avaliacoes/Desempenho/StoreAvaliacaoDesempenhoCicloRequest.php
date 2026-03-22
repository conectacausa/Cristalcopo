<?php

namespace App\Http\Requests\Avaliacoes\Desempenho;

use App\Models\Avaliacoes\Desempenho\AvaliacaoDesempenhoRegraAplicacao;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAvaliacaoDesempenhoCicloRequest extends FormRequest
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
            'lembrete_intervalo_dias' => $this->nullableInteger($this->input('lembrete_intervalo_dias')),
            'lembrete_canais' => array_values(array_filter((array) $this->input('lembrete_canais', []))),
            'publico_tipo' => $this->input('publico_tipo', 'todos'),
            'publico_alvo' => $this->normalizarColecao($this->input('publico_alvo', [])),
        ]);
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:150'],
            'descricao' => ['nullable', 'string'],
            'tipo_avaliacao' => ['required', Rule::in(['90', '180', '360'])],
            'data_inicio' => ['required', 'date'],
            'data_fim' => ['required', 'date', 'after_or_equal:data_inicio'],
            'status' => ['required', Rule::in(['rascunho', 'agendada', 'ativa', 'encerrada', 'cancelada'])],
            'forma_liberacao' => ['required', Rule::in(['manual', 'automatica'])],
            'permite_autoavaliacao' => ['required', 'boolean'],
            'permite_avaliacao_gestor' => ['required', 'boolean'],
            'permite_avaliacao_pares' => ['required', 'boolean'],
            'permite_avaliacao_subordinados' => ['required', 'boolean'],
            'anonimato' => ['required', 'boolean'],
            'permite_edicao_ate_prazo_final' => ['required', 'boolean'],
            'permite_resposta_parcial' => ['required', 'boolean'],
            'lembrete_ativo' => ['required', 'boolean'],
            'lembrete_frequencia' => ['nullable', Rule::in(['diario', 'semanal', 'personalizado'])],
            'lembrete_intervalo_dias' => ['nullable', 'integer', 'min:1', 'max:365'],
            'lembrete_horario' => ['nullable', 'date_format:H:i'],
            'lembrete_canais' => ['nullable', 'array'],
            'lembrete_canais.*' => ['string', Rule::in(['email', 'interna'])],
            'lembrete_parar_ao_responder' => ['required', 'boolean'],
            'lembrete_final_antes_encerramento' => ['required', 'boolean'],
            'publico_tipo' => ['required', Rule::in(['todos', 'filial', 'setor', 'cargo', 'manual'])],
            'publico_alvo' => ['nullable', 'array'],
            'publico_alvo.*.tipo' => ['required_with:publico_alvo', 'string', Rule::in(['filial', 'setor', 'cargo', 'colaborador'])],
            'publico_alvo.*.referencia_id' => ['required_with:publico_alvo', 'integer', 'min:1'],
        ];
    }

    public function after(): array
    {
        return [function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            if (! $this->boolean('lembrete_ativo')) {
                return;
            }

            if (empty($this->input('lembrete_canais'))) {
                $validator->errors()->add('lembrete_canais', 'Selecione ao menos um canal de lembrete.');
            }

            if (empty($this->input('lembrete_frequencia'))) {
                $validator->errors()->add('lembrete_frequencia', 'Informe a frequência do lembrete.');
            }

            if ($this->input('lembrete_frequencia') === 'personalizado' && empty($this->input('lembrete_intervalo_dias'))) {
                $validator->errors()->add('lembrete_intervalo_dias', 'Informe o intervalo em dias para lembretes personalizados.');
            }

            if ($this->input('publico_tipo') !== 'todos' && count((array) $this->input('publico_alvo', [])) === 0) {
                $validator->errors()->add('publico_alvo', 'Selecione ao menos um item para o público-alvo informado.');
            }
        }];
    }

    private function normalizarColecao(array $itens): array
    {
        return collect($itens)
            ->map(function ($item) {
                return [
                    'tipo' => $item['tipo'] ?? null,
                    'referencia_id' => isset($item['referencia_id']) ? (int) $item['referencia_id'] : null,
                ];
            })
            ->filter(fn ($item) => ! empty($item['tipo']) && ! empty($item['referencia_id']))
            ->values()
            ->all();
    }

    private function nullableString(mixed $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function nullableInteger(mixed $value): ?int
    {
        return $value === null || $value === '' ? null : (int) $value;
    }
}
