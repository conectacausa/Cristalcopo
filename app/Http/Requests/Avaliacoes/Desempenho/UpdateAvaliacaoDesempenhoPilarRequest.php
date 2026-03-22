<?php

namespace App\Http\Requests\Avaliacoes\Desempenho;

use App\Models\Avaliacoes\Desempenho\AvaliacaoDesempenhoPilar;

class UpdateAvaliacaoDesempenhoPilarRequest extends StoreAvaliacaoDesempenhoPilarRequest
{
    public function after(): array
    {
        return [function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $pilarId = (int) $this->route('pilar');
            $cicloId = (int) $this->input('ciclo_id');
            $pesoAtual = (float) $this->input('peso');
            $somaExistente = (float) AvaliacaoDesempenhoPilar::query()
                ->where('ciclo_id', $cicloId)
                ->where('id', '<>', $pilarId)
                ->sum('peso');

            if (($somaExistente + $pesoAtual) > 100.0001) {
                $validator->errors()->add('peso', 'A soma dos pesos dos pilares do ciclo não pode ultrapassar 100.');
            }
        }];
    }
}
