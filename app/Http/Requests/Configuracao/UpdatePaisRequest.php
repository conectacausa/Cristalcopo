<?php

namespace App\Http\Requests\Configuracao;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePaisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = (int) $this->route('id');

        return [
            'nome' => ['required', 'string', 'max:150'],
            'iso2' => [
                'required',
                'string',
                'size:2',
                Rule::unique('gestao_pais', 'iso2')->ignore($id)->whereNull('deleted_at'),
            ],
            'iso3' => [
                'required',
                'string',
                'size:3',
                Rule::unique('gestao_pais', 'iso3')->ignore($id)->whereNull('deleted_at'),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nome' => trim((string) $this->input('nome')),
            'iso2' => strtoupper(trim((string) $this->input('iso2'))),
            'iso3' => strtoupper(trim((string) $this->input('iso3'))),
        ]);
    }

    public function attributes(): array
    {
        return [
            'nome' => 'nome do país',
            'iso2' => 'ISO2',
            'iso3' => 'ISO3',
        ];
    }
}
