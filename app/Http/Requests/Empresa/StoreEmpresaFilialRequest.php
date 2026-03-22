<?php

namespace App\Http\Requests\Empresa;

use App\Models\GestaoCidade;
use App\Models\GestaoEstado;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmpresaFilialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'razao_social' => trim((string) $this->input('razao_social')),
            'cnpj' => $this->onlyNumbers($this->input('cnpj')),
            'nome_fantasia' => trim((string) $this->input('nome_fantasia')),
            'porte_id' => $this->input('porte_id'),
            'natureza_juridica_id' => $this->input('natureza_juridica_id'),
            'tipo' => $this->input('tipo'),
            'situacao' => filter_var($this->input('situacao'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            'telefone1' => $this->nullableString($this->onlyNumbers($this->input('telefone1'))),
            'telefone2' => $this->nullableString($this->onlyNumbers($this->input('telefone2'))),
            'email' => $this->nullableString(mb_strtolower(trim((string) $this->input('email')))),
            'logradouro' => $this->nullableString(trim((string) $this->input('logradouro'))),
            'numero' => $this->nullableString(trim((string) $this->input('numero'))),
            'bairro' => $this->nullableString(trim((string) $this->input('bairro'))),
            'cidade_id' => $this->input('cidade_id'),
            'estado_id' => $this->input('estado_id'),
            'pais_id' => $this->input('pais_id'),
            'complemento' => $this->nullableString(trim((string) $this->input('complemento'))),
            'cep' => $this->nullableString($this->onlyNumbers($this->input('cep'))),
        ]);
    }

    public function rules(): array
    {
        return [
            'razao_social' => ['required', 'string', 'max:200'],
            'cnpj' => ['required', 'string', 'size:14', Rule::unique('empresa_filial', 'cnpj')],
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

    public function messages(): array
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

    public function attributes(): array
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

    public function after(): array
    {
        return [function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $estado = GestaoEstado::query()
                ->where('id', $this->input('estado_id'))
                ->where('pais_id', $this->input('pais_id'))
                ->first();

            if (! $estado) {
                $validator->errors()->add('estado_id', 'O estado informado não pertence ao país selecionado.');
                return;
            }

            $cidade = GestaoCidade::query()
                ->where('id', $this->input('cidade_id'))
                ->where('estado_id', $this->input('estado_id'))
                ->first();

            if (! $cidade) {
                $validator->errors()->add('cidade_id', 'A cidade informada não pertence ao estado selecionado.');
            }
        }];
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
