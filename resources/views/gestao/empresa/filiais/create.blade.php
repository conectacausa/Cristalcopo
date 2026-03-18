@extends('layouts.app')

@section('title', 'Cristalcopo | Nova Filial')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Nova Filial</h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard') }}">
                                        <i class="mdi mdi-home-outline"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item">Empresa</li>
                                <li class="breadcrumb-item">Filiais</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <form method="POST" action="{{ route('empresa.filiais.store') }}" id="form-filial">
                @csrf

                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h4 class="box-title">Adicionar Filial</h4>
                            </div>

                            <div class="box-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <strong>Ocorreram erros ao salvar:</strong>
                                        <ul class="mb-0 mt-10">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">CNPJ</label>
                                            <div class="input-group">
                                                <input
                                                    type="text"
                                                    name="cnpj"
                                                    id="cnpj"
                                                    class="form-control @error('cnpj') is-invalid @enderror"
                                                    placeholder="CNPJ"
                                                    required
                                                    value="{{ old('cnpj') }}"
                                                >
                                                <button class="btn btn-primary btn-sm" type="button" id="btn-consultar-cnpj">
                                                    <span class="consulta-icon">
                                                        <i class="fa fa-search"></i>
                                                    </span>
                                                    <span class="consulta-loading d-none">
                                                        <i class="fa fa-spinner fa-spin"></i>
                                                    </span>
                                                </button>
                                            </div>
                                            @error('cnpj')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="form-label">Razão Social</label>
                                            <input
                                                type="text"
                                                name="razao_social"
                                                id="razao_social"
                                                class="form-control @error('razao_social') is-invalid @enderror"
                                                placeholder="Razão Social"
                                                value="{{ old('razao_social') }}"
                                            >
                                            @error('razao_social')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Nome Fantasia</label>
                                            <input
                                                type="text"
                                                name="nome_fantasia"
                                                id="nome_fantasia"
                                                class="form-control @error('nome_fantasia') is-invalid @enderror"
                                                placeholder="Nome Fantasia"
                                                value="{{ old('nome_fantasia') }}"
                                            >
                                            @error('nome_fantasia')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <ul class="nav nav-tabs nav-fill" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#fiscal" role="tab">
                                                <span>
                                                    <i class="fa fa-institution"></i>
                                                </span>
                                                <span class="hidden-xs-down ms-15">Fiscal</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#contato" role="tab">
                                                <span>
                                                    <i class="fa fa-phone"></i>
                                                </span>
                                                <span class="hidden-xs-down ms-15">Contato</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#endereco" role="tab">
                                                <span>
                                                    <i class="fa fa-map"></i>
                                                </span>
                                                <span class="hidden-xs-down ms-15">Endereço</span>
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content tabcontent-border">
                                        <div class="tab-pane active" id="fiscal" role="tabpanel">
                                            <div class="p-15">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label class="form-label">Porte</label>
                                                            <input
                                                                type="text"
                                                                id="porte_codigo"
                                                                class="form-control"
                                                                placeholder="Código"
                                                                value="{{ old('porte_codigo') }}"
                                                            >
                                                            <input type="hidden" name="porte_id" id="porte_id" value="{{ old('porte_id') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Descrição Porte</label>
                                                            <input
                                                                type="text"
                                                                id="porte_descricao"
                                                                class="form-control"
                                                                placeholder="Descrição Porte"
                                                                readonly
                                                                value="{{ old('porte_descricao') }}"
                                                            >
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label class="form-label">Natureza Jurídica</label>
                                                            <input
                                                                type="text"
                                                                id="natureza_codigo"
                                                                class="form-control"
                                                                placeholder="Código"
                                                                value="{{ old('natureza_codigo') }}"
                                                            >
                                                            <input type="hidden" name="natureza_juridica_id" id="natureza_id" value="{{ old('natureza_juridica_id') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Descrição Natureza Jurídica</label>
                                                            <input
                                                                type="text"
                                                                id="natureza_descricao"
                                                                class="form-control"
                                                                placeholder="Descrição Natureza Jurídica"
                                                                readonly
                                                                value="{{ old('natureza_descricao') }}"
                                                            >
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Data Abertura</label>
                                                            <input
                                                                type="date"
                                                                name="data_abertura"
                                                                id="data_abertura"
                                                                class="form-control @error('data_abertura') is-invalid @enderror"
                                                                placeholder="Data Abertura"
                                                                value="{{ old('data_abertura') }}"
                                                            >
                                                            @error('data_abertura')
                                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Situação</label>
                                                            <select
                                                                name="situacao"
                                                                id="situacao"
                                                                class="form-control select2 @error('situacao') is-invalid @enderror"
                                                                style="width: 100%;"
                                                            >
                                                                <option value="1" {{ old('situacao', '1') == '1' ? 'selected' : '' }}>Ativo</option>
                                                                <option value="0" {{ old('situacao') == '0' ? 'selected' : '' }}>Inativo</option>
                                                            </select>
                                                            @error('situacao')
                                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Tipo</label>
                                                            <select
                                                                name="tipo"
                                                                id="tipo"
                                                                class="form-control select2 @error('tipo') is-invalid @enderror"
                                                                style="width: 100%;"
                                                            >
                                                                <option value="matriz" {{ old('tipo') == 'matriz' ? 'selected' : '' }}>Matriz</option>
                                                                <option value="filial" {{ old('tipo', 'filial') == 'filial' ? 'selected' : '' }}>Filial</option>
                                                            </select>
                                                            @error('tipo')
                                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr>

                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label class="form-label">Código CNAE</label>
                                                            <input
                                                                type="text"
                                                                id="cnae_subclasse"
                                                                class="form-control"
                                                                placeholder="Código"
                                                            >
                                                        </div>
                                                    </div>

                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label class="form-label">Descrição CNAE</label>
                                                            <input
                                                                type="text"
                                                                id="cnae_descricao"
                                                                class="form-control"
                                                                placeholder="Descrição CNAE"
                                                                readonly
                                                            >
                                                            <input type="hidden" id="cnae_subclasse_normalizada">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label class="form-label">&nbsp;</label>
                                                            <button
                                                                type="button"
                                                                id="btn-adicionar-cnae"
                                                                class="waves-effect waves-light btn bg-gradient-success w-150 d-block"
                                                            >
                                                                Adicionar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                <thead class="bg-primary">
                                                                    <tr align="center">
                                                                        <th width="120">Prioritário</th>
                                                                        <th width="180">Subclasse</th>
                                                                        <th>Descrição</th>
                                                                        <th width="120">Ações</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="tabela-cnaes-body">
                                                                    <tr id="linha-sem-cnae">
                                                                        <td colspan="4" class="text-center">Nenhum CNAE adicionado.</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div id="cnaes-hidden-container"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane" id="contato" role="tabpanel">
                                            <div class="p-15">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Telefone</label>
                                                            <input
                                                                type="text"
                                                                name="telefone1"
                                                                id="telefone1"
                                                                class="form-control @error('telefone1') is-invalid @enderror"
                                                                placeholder="Telefone"
                                                                value="{{ old('telefone1') }}"
                                                            >
                                                            @error('telefone1')
                                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Telefone</label>
                                                            <input
                                                                type="text"
                                                                name="telefone2"
                                                                id="telefone2"
                                                                class="form-control @error('telefone2') is-invalid @enderror"
                                                                placeholder="Telefone"
                                                                value="{{ old('telefone2') }}"
                                                            >
                                                            @error('telefone2')
                                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">E-mail</label>
                                                            <input
                                                                type="text"
                                                                name="email"
                                                                id="email"
                                                                class="form-control @error('email') is-invalid @enderror"
                                                                placeholder="E-mail"
                                                                value="{{ old('email') }}"
                                                            >
                                                            @error('email')
                                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane" id="endereco" role="tabpanel">
                                            <div class="p-15">
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <div class="form-group">
                                                            <label class="form-label">Logradouro</label>
                                                            <input
                                                                type="text"
                                                                name="logradouro"
                                                                id="logradouro"
                                                                class="form-control @error('logradouro') is-invalid @enderror"
                                                                placeholder="Logradouro"
                                                                value="{{ old('logradouro') }}"
                                                            >
                                                            @error('logradouro')
                                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label class="form-label">Número</label>
                                                            <input
                                                                type="text"
                                                                name="numero"
                                                                id="numero"
                                                                class="form-control @error('numero') is-invalid @enderror"
                                                                placeholder="Número"
                                                                value="{{ old('numero') }}"
                                                            >
                                                            @error('numero')
                                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Bairro</label>
                                                            <input
                                                                type="text"
                                                                name="bairro"
                                                                id="bairro"
                                                                class="form-control @error('bairro') is-invalid @enderror"
                                                                placeholder="Bairro"
                                                                value="{{ old('bairro') }}"
                                                            >
                                                            @error('bairro')
                                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-label">Cidade</label>
                                                            <select
                                                                name="cidade_id"
                                                                id="cidade_id"
                                                                class="form-control select2 @error('cidade_id') is-invalid @enderror"
                                                                style="width: 100%;"
                                                            >
                                                                <option value="">Cidade</option>
                                                            </select>
                                                            @error('cidade_id')
                                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label class="form-label">UF</label>
                                                            <select
                                                                name="estado_id"
                                                                id="estado_id"
                                                                class="form-control select2 @error('estado_id') is-invalid @enderror"
                                                                style="width: 100%;"
                                                            >
                                                                <option value="">UF</option>
                                                            </select>
                                                            @error('estado_id')
                                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-label">País</label>
                                                            <select
                                                                name="pais_id"
                                                                id="pais_id"
                                                                class="form-control select2 @error('pais_id') is-invalid @enderror"
                                                                style="width: 100%;"
                                                            >
                                                                <option value="">País</option>
                                                                @foreach($paises as $pais)
                                                                    <option value="{{ $pais->id }}" {{ old('pais_id') == $pais->id ? 'selected' : '' }}>
                                                                        {{ $pais->nome }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('pais_id')
                                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <label class="form-label">Complemento</label>
                                                            <input
                                                                type="text"
                                                                name="complemento"
                                                                id="complemento"
                                                                class="form-control @error('complemento') is-invalid @enderror"
                                                                placeholder="Complemento"
                                                                value="{{ old('complemento') }}"
                                                            >
                                                            @error('complemento')
                                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-label">CEP</label>
                                                            <input
                                                                type="text"
                                                                name="cep"
                                                                id="cep"
                                                                class="form-control @error('cep') is-invalid @enderror"
                                                                placeholder="CEP"
                                                                value="{{ old('cep') }}"
                                                            >
                                                            @error('cep')
                                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> {{-- tab-content --}}
                                </div> {{-- row tabs --}}
                            </div> {{-- box-body --}}

                            <div class="box-footer text-end">
                                <button type="submit" class="waves-effect waves-light btn mb-5 bg-gradient-success">
                                    Salvar
                                </button>
                            </div>
                        </div> {{-- box --}}
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const portes = @json($portes->map(fn($item) => [
        'id' => $item->id,
        'codigo' => (string) $item->codigo,
        'descricao' => $item->descricao,
    ])->values());

    const naturezas = @json($naturezasJuridicas->map(fn($item) => [
        'id' => $item->id,
        'codigo' => (string) $item->codigo,
        'descricao' => $item->descricao,
    ])->values());

    const oldCnaes = @json(old('cnaes', []));

    const csrfToken = document.querySelector('meta[name="csrf-token"]')
        ? document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        : '{{ csrf_token() }}';

    const cnpjInput = document.getElementById('cnpj');
    const btnConsultarCnpj = document.getElementById('btn-consultar-cnpj');
    const consultaIcon = btnConsultarCnpj.querySelector('.consulta-icon');
    const consultaLoading = btnConsultarCnpj.querySelector('.consulta-loading');

    const razaoSocialInput = document.getElementById('razao_social');
    const nomeFantasiaInput = document.getElementById('nome_fantasia');
    const dataAberturaInput = document.getElementById('data_abertura');
    const situacaoSelect = document.getElementById('situacao');
    const tipoSelect = document.getElementById('tipo');

    const porteCodigoInput = document.getElementById('porte_codigo');
    const porteIdInput = document.getElementById('porte_id');
    const porteDescricaoInput = document.getElementById('porte_descricao');

    const naturezaCodigoInput = document.getElementById('natureza_codigo');
    const naturezaIdInput = document.getElementById('natureza_id');
    const naturezaDescricaoInput = document.getElementById('natureza_descricao');

    const telefone1Input = document.getElementById('telefone1');
    const telefone2Input = document.getElementById('telefone2');
    const emailInput = document.getElementById('email');

    const logradouroInput = document.getElementById('logradouro');
    const numeroInput = document.getElementById('numero');
    const bairroInput = document.getElementById('bairro');
    const complementoInput = document.getElementById('complemento');
    const cepInput = document.getElementById('cep');

    const paisSelect = document.getElementById('pais_id');
    const estadoSelect = document.getElementById('estado_id');
    const cidadeSelect = document.getElementById('cidade_id');

    const cnaeSubclasseInput = document.getElementById('cnae_subclasse');
    const cnaeDescricaoInput = document.getElementById('cnae_descricao');
    const cnaeSubclasseNormalizadaInput = document.getElementById('cnae_subclasse_normalizada');
    const btnAdicionarCnae = document.getElementById('btn-adicionar-cnae');
    const tabelaCnaesBody = document.getElementById('tabela-cnaes-body');
    const linhaSemCnae = document.getElementById('linha-sem-cnae');
    const cnaesHiddenContainer = document.getElementById('cnaes-hidden-container');

    let cnaes = [];

    if (window.jQuery && $.fn.select2) {
        $('.select2').select2();
    }

    function toastrSuccess(message) {
        if (typeof toastr !== 'undefined') {
            toastr.success(message);
        } else {
            alert(message);
        }
    }

    function toastrError(message) {
        if (typeof toastr !== 'undefined') {
            toastr.error(message);
        } else {
            alert(message);
        }
    }

    function onlyNumbers(value) {
        return (value || '').replace(/\D+/g, '');
    }

    function maskCnpj(value) {
        value = onlyNumbers(value).slice(0, 14);
        value = value.replace(/^(\d{2})(\d)/, '$1.$2');
        value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
        value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
        value = value.replace(/(\d{4})(\d)/, '$1-$2');
        return value;
    }

    function maskCep(value) {
        value = onlyNumbers(value).slice(0, 8);
        value = value.replace(/^(\d{5})(\d)/, '$1-$2');
        return value;
    }

    function maskPhone(value) {
        value = onlyNumbers(value).slice(0, 11);

        if (value.length <= 10) {
            value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
            value = value.replace(/(\d{4})(\d)/, '$1-$2');
            return value;
        }

        value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
        value = value.replace(/(\d{5})(\d)/, '$1-$2');
        return value;
    }

    function normalizeCnaeFromDigits(digits) {
        digits = onlyNumbers(digits).slice(0, 7);

        if (digits.length <= 2) return digits;
        if (digits.length <= 4) return digits.slice(0, 2) + '.' + digits.slice(2);
        if (digits.length <= 5) return digits.slice(0, 2) + '.' + digits.slice(2, 4) + '-' + digits.slice(4);
        return digits.slice(0, 2) + '.' + digits.slice(2, 4) + '-' + digits.slice(4, 5) + '-' + digits.slice(5);
    }

    function normalizeCnaeToSave(value) {
        const digits = onlyNumbers(value);

        if (digits.length !== 7) {
            return digits;
        }

        return digits.slice(0, 2) + '.' + digits.slice(2, 4) + '-' + digits.slice(4, 5) + '-' + digits.slice(5, 7);
    }

    function setLoadingConsulta(loading) {
        btnConsultarCnpj.disabled = loading;

        if (loading) {
            consultaIcon.classList.add('d-none');
            consultaLoading.classList.remove('d-none');
        } else {
            consultaIcon.classList.remove('d-none');
            consultaLoading.classList.add('d-none');
        }
    }

    function setSelectValue(selectEl, value) {
        selectEl.value = value ? String(value) : '';
        if (window.jQuery && $(selectEl).hasClass('select2-hidden-accessible')) {
            $(selectEl).trigger('change.select2');
        }
    }

    function fillOptions(selectEl, items, placeholder, selectedValue, labelFn) {
        selectEl.innerHTML = '';
        const optionPlaceholder = document.createElement('option');
        optionPlaceholder.value = '';
        optionPlaceholder.textContent = placeholder;
        selectEl.appendChild(optionPlaceholder);

        items.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = labelFn ? labelFn(item) : item.nome;
            if (selectedValue && String(selectedValue) === String(item.id)) {
                option.selected = true;
            }
            selectEl.appendChild(option);
        });

        if (window.jQuery && $(selectEl).hasClass('select2-hidden-accessible')) {
            $(selectEl).trigger('change.select2');
        }
    }

    async function loadEstados(paisId, selectedEstadoId = null) {
        fillOptions(estadoSelect, [], 'UF', null, item => item.uf ? item.uf : item.nome);
        fillOptions(cidadeSelect, [], 'Cidade', null, item => item.nome);

        if (!paisId) {
            return;
        }

        try {
            const response = await fetch(`{{ url('/empresa/filiais/ajax/estados') }}/${paisId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const estados = await response.json();

            fillOptions(estadoSelect, estados, 'UF', selectedEstadoId, item => item.uf ? item.uf : item.nome);
        } catch (error) {
            toastrError('Não foi possível carregar os estados.');
        }
    }

    async function loadCidades(estadoId, selectedCidadeId = null) {
        fillOptions(cidadeSelect, [], 'Cidade', null, item => item.nome);

        if (!estadoId) {
            return;
        }

        try {
            const response = await fetch(`{{ url('/empresa/filiais/ajax/cidades') }}/${estadoId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const cidades = await response.json();

            fillOptions(cidadeSelect, cidades, 'Cidade', selectedCidadeId, item => item.nome);
        } catch (error) {
            toastrError('Não foi possível carregar as cidades.');
        }
    }

    function applyLookup(localCollection, codeInput, idInput, descInput) {
        const code = String(codeInput.value || '').trim();

        if (!code) {
            idInput.value = '';
            descInput.value = '';
            return;
        }

        const found = localCollection.find(item => String(item.codigo).trim() === code);

        if (found) {
            idInput.value = found.id;
            descInput.value = found.descricao;
        } else {
            idInput.value = '';
            descInput.value = '';
        }
    }

    function buildCnaeHiddenInputs() {
        cnaesHiddenContainer.innerHTML = '';

        cnaes.forEach((item, index) => {
            const inputSubclasse = document.createElement('input');
            inputSubclasse.type = 'hidden';
            inputSubclasse.name = `cnaes[${index}][subclasse]`;
            inputSubclasse.value = item.subclasse;

            const inputPrincipal = document.createElement('input');
            inputPrincipal.type = 'hidden';
            inputPrincipal.name = `cnaes[${index}][principal]`;
            inputPrincipal.value = item.principal ? '1' : '0';

            cnaesHiddenContainer.appendChild(inputSubclasse);
            cnaesHiddenContainer.appendChild(inputPrincipal);
        });
    }

    function renderTabelaCnaes() {
        const ordered = [...cnaes].sort((a, b) => {
            if (a.principal !== b.principal) {
                return a.principal ? -1 : 1;
            }
            return a.subclasse.localeCompare(b.subclasse);
        });

        tabelaCnaesBody.innerHTML = '';

        if (!ordered.length) {
            const tr = document.createElement('tr');
            tr.id = 'linha-sem-cnae';
            tr.innerHTML = `<td colspan="4" class="text-center">Nenhum CNAE adicionado.</td>`;
            tabelaCnaesBody.appendChild(tr);
            buildCnaeHiddenInputs();
            return;
        }

        const hasPrincipal = ordered.some(item => item.principal);

        ordered.forEach((item, index) => {
            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td align="center">
                    <input type="checkbox"
                        class="form-check-input cnae-principal-checkbox"
                        data-index="${index}"
                        ${item.principal ? 'checked' : ''}
                        ${hasPrincipal && !item.principal ? 'disabled' : ''}>
                </td>
                <td>${item.subclasse}</td>
                <td>${item.descricao}</td>
                <td align="center">
                    <button type="button" class="btn btn-danger btn-sm btn-remover-cnae" data-index="${index}">
                        <i class="fa fa-trash-o"></i>
                    </button>
                </td>
            `;

            tabelaCnaesBody.appendChild(tr);
        });

        cnaes = ordered;
        buildCnaeHiddenInputs();
        bindCnaeEvents();
    }

    function bindCnaeEvents() {
        document.querySelectorAll('.btn-remover-cnae').forEach(button => {
            button.addEventListener('click', function () {
                const index = Number(this.getAttribute('data-index'));
                cnaes.splice(index, 1);
                renderTabelaCnaes();
            });
        });

        document.querySelectorAll('.cnae-principal-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const index = Number(this.getAttribute('data-index'));
                const checked = this.checked;

                cnaes = cnaes.map((item, idx) => {
                    if (idx === index) {
                        return { ...item, principal: checked };
                    }

                    if (checked) {
                        return { ...item, principal: false };
                    }

                    return item;
                });

                renderTabelaCnaes();
            });
        });
    }

    async function buscarDescricaoCnae() {
        const subclasse = normalizeCnaeToSave(cnaeSubclasseInput.value);
        cnaeSubclasseNormalizadaInput.value = subclasse;
        cnaeDescricaoInput.value = '';

        if (onlyNumbers(subclasse).length !== 7) {
            return;
        }

        try {
            const response = await fetch(`{{ url('/empresa/filiais/ajax/cnae') }}/${encodeURIComponent(subclasse)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (result.found && result.data) {
                cnaeDescricaoInput.value = result.data.descricao || '';
            } else {
                toastrError('CNAE não encontrado.');
            }
        } catch (error) {
            toastrError('Não foi possível consultar o CNAE.');
        }
    }

    async function consultarCnpj() {
        const cnpj = onlyNumbers(cnpjInput.value);

        if (cnpj.length !== 14) {
            toastrError('Informe um CNPJ válido.');
            return;
        }

        setLoadingConsulta(true);

        try {
            const response = await fetch(`{{ url('/empresa/filiais/ajax/cnpj') }}/${cnpj}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                toastrError(result.message || 'Não foi possível consultar o CNPJ.');
                return;
            }

            const api = result.data.api || {};
            const refs = result.data.referencias || {};

            cnpjInput.value = maskCnpj(api.cnpj || cnpj);
            razaoSocialInput.value = api.razao_social || '';
            nomeFantasiaInput.value = api.nome_fantasia || '';
            dataAberturaInput.value = api.data_inicio_atividade || '';
            situacaoSelect.value = String(api.situacao_cadastral || '').toLowerCase() === 'ativa' ? '1' : '0';
            tipoSelect.value = String(api.matriz_filial || '').toLowerCase() === 'matriz' ? 'matriz' : 'filial';

            if (window.jQuery) {
                $('#situacao').trigger('change.select2');
                $('#tipo').trigger('change.select2');
            }

            if (refs.porte) {
                porteCodigoInput.value = refs.porte.codigo || '';
                porteIdInput.value = refs.porte.id || '';
                porteDescricaoInput.value = refs.porte.descricao || '';
            }

            if (refs.natureza_juridica) {
                naturezaCodigoInput.value = refs.natureza_juridica.codigo || '';
                naturezaIdInput.value = refs.natureza_juridica.id || '';
                naturezaDescricaoInput.value = refs.natureza_juridica.descricao || '';
            }

            logradouroInput.value = api.logradouro || '';
            numeroInput.value = api.numero || '';
            complementoInput.value = api.complemento || '';
            bairroInput.value = api.bairro || '';
            cepInput.value = maskCep(api.cep || '');
            emailInput.value = api.email || '';

            if (Array.isArray(api.telefones) && api.telefones.length) {
                const tel1 = api.telefones[0] ? `${api.telefones[0].ddd || ''}${api.telefones[0].numero || ''}` : '';
                const tel2 = api.telefones[1] ? `${api.telefones[1].ddd || ''}${api.telefones[1].numero || ''}` : '';

                telefone1Input.value = maskPhone(tel1);
                telefone2Input.value = maskPhone(tel2);
            }

            if (refs.pais) {
                setSelectValue(paisSelect, refs.pais.id);
                await loadEstados(refs.pais.id, refs.estado ? refs.estado.id : null);
            }

            if (refs.estado) {
                setSelectValue(estadoSelect, refs.estado.id);
                await loadCidades(refs.estado.id, refs.cidade ? refs.cidade.id : null);
            }

            if (refs.cidade) {
                setSelectValue(cidadeSelect, refs.cidade.id);
            }

            const importedCnaes = [];
            const seen = new Set();

            if (refs.cnae_principal && refs.cnae_principal.subclasse) {
                const key = refs.cnae_principal.subclasse;
                if (!seen.has(key)) {
                    importedCnaes.push({
                        subclasse: refs.cnae_principal.subclasse,
                        descricao: refs.cnae_principal.descricao || 'CNAE importado via API',
                        principal: true,
                    });
                    seen.add(key);
                }
            }

            if (Array.isArray(refs.cnaes_secundarios)) {
                refs.cnaes_secundarios.forEach(item => {
                    if (item && item.subclasse && !seen.has(item.subclasse)) {
                        importedCnaes.push({
                            subclasse: item.subclasse,
                            descricao: item.descricao || 'CNAE importado via API',
                            principal: false,
                        });
                        seen.add(item.subclasse);
                    }
                });
            }

            cnaes = importedCnaes;
            renderTabelaCnaes();

            toastrSuccess('Consulta realizada com sucesso.');
        } catch (error) {
            toastrError('Erro ao consultar o CNPJ.');
        } finally {
            setLoadingConsulta(false);
        }
    }

    btnConsultarCnpj.addEventListener('click', consultarCnpj);

    cnpjInput.addEventListener('input', function () {
        this.value = maskCnpj(this.value);
    });

    telefone1Input.addEventListener('input', function () {
        this.value = maskPhone(this.value);
    });

    telefone2Input.addEventListener('input', function () {
        this.value = maskPhone(this.value);
    });

    cepInput.addEventListener('input', function () {
        this.value = maskCep(this.value);
    });

    porteCodigoInput.addEventListener('input', function () {
        applyLookup(portes, porteCodigoInput, porteIdInput, porteDescricaoInput);
    });

    naturezaCodigoInput.addEventListener('input', function () {
        applyLookup(naturezas, naturezaCodigoInput, naturezaIdInput, naturezaDescricaoInput);
    });

    cnaeSubclasseInput.addEventListener('input', function () {
        this.value = normalizeCnaeFromDigits(this.value);
    });

    cnaeSubclasseInput.addEventListener('blur', function () {
        buscarDescricaoCnae();
    });

    btnAdicionarCnae.addEventListener('click', function () {
        const subclasse = normalizeCnaeToSave(cnaeSubclasseInput.value);
        const descricao = (cnaeDescricaoInput.value || '').trim();

        if (onlyNumbers(subclasse).length !== 7) {
            toastrError('Informe uma subclasse CNAE válida.');
            return;
        }

        if (!descricao) {
            toastrError('CNAE não encontrado para o código informado.');
            return;
        }

        const exists = cnaes.some(item => item.subclasse === subclasse);

        if (exists) {
            toastrError('Este CNAE já foi adicionado.');
            return;
        }

        cnaes.push({
            subclasse: subclasse,
            descricao: descricao,
            principal: cnaes.length === 0,
        });

        cnaeSubclasseInput.value = '';
        cnaeDescricaoInput.value = '';
        cnaeSubclasseNormalizadaInput.value = '';

        renderTabelaCnaes();
    });

    paisSelect.addEventListener('change', async function () {
        const paisId = this.value;
        await loadEstados(paisId, null);
    });

    estadoSelect.addEventListener('change', async function () {
        const estadoId = this.value;
        await loadCidades(estadoId, null);
    });

    document.getElementById('form-filial').addEventListener('submit', function () {
        buildCnaeHiddenInputs();
    });

    applyLookup(portes, porteCodigoInput, porteIdInput, porteDescricaoInput);
    applyLookup(naturezas, naturezaCodigoInput, naturezaIdInput, naturezaDescricaoInput);

    if (oldCnaes && Array.isArray(oldCnaes) && oldCnaes.length) {
        cnaes = oldCnaes
            .filter(item => item && item.subclasse)
            .map(item => ({
                subclasse: normalizeCnaeToSave(item.subclasse),
                descricao: item.descricao || 'CNAE selecionado',
                principal: String(item.principal) === '1' || item.principal === true,
            }));

        const missingDescriptions = cnaes.filter(item => !item.descricao || item.descricao === 'CNAE selecionado');

        if (!missingDescriptions.length) {
            renderTabelaCnaes();
        } else {
            Promise.all(
                cnaes.map(async item => {
                    try {
                        const response = await fetch(`{{ url('/empresa/filiais/ajax/cnae') }}/${encodeURIComponent(item.subclasse)}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        const result = await response.json();
                        if (result.found && result.data) {
                            item.descricao = result.data.descricao || item.descricao;
                        }
                    } catch (e) {}
                    return item;
                })
            ).finally(() => renderTabelaCnaes());
        }
    } else {
        renderTabelaCnaes();
    }

    const oldPaisId = '{{ old('pais_id') }}';
    const oldEstadoId = '{{ old('estado_id') }}';
    const oldCidadeId = '{{ old('cidade_id') }}';

    if (oldPaisId) {
        loadEstados(oldPaisId, oldEstadoId).then(() => {
            if (oldEstadoId) {
                loadCidades(oldEstadoId, oldCidadeId);
            }
        });
    }
});
</script>
@endsection
