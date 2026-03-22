<?php

namespace App\Http\Requests\Cargos;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCargoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo_cargo' => ['required', 'string', 'max:255'],
            'codigo_importacao' => ['nullable', 'string', 'max:100'],
            'cargo_cbo_id' => ['required', 'integer', 'exists:cargos_cbo,id'],
            'filiais' => ['required', 'array', 'min:1'],
            'filiais.*' => ['integer', 'exists:empresa_filial,id'],
            'setores' => ['required', 'array', 'min:1'],
            'setores.*' => ['integer', 'exists:empresa_setores,id'],
            'riscos' => ['nullable', 'array'],
            'riscos.*' => ['integer', 'exists:sst_riscos,id'],
            'responsabilidades' => ['nullable', 'array'],
            'responsabilidades.*' => ['integer', 'exists:cargos_responsabilidades,id'],
            'competencias_payload' => ['nullable', 'array'],
            'competencias_payload.*.competencia_id' => ['nullable', 'integer', 'exists:cargos_competencias,id'],
            'competencias_payload.*.nota' => ['nullable', 'integer', 'min:0', 'max:10'],
            'formacoes_payload' => ['nullable', 'array'],
            'formacoes_payload.*.formacao_id' => ['nullable', 'integer', 'exists:cargos_formacoes,id'],
            'formacoes_payload.*.tipo' => ['nullable', 'in:desejado,obrigatorio'],
            'cursos_payload' => ['nullable', 'array'],
            'cursos_payload.*.curso_id' => ['nullable', 'integer', 'exists:cargos_cursos,id'],
            'cursos_payload.*.tipo' => ['nullable', 'in:desejado,obrigatorio'],
            'escolaridades_payload' => ['nullable', 'array'],
            'escolaridades_payload.*.escolaridade_id' => ['nullable', 'integer', 'exists:cargos_escolaridades,id'],
            'escolaridades_payload.*.tipo' => ['nullable', 'in:desejado,obrigatorio'],
            'conta_base_jovem_aprendiz' => ['nullable'],
        ];
    }
}
