@extends('layouts.app')

@section('title', 'Cristalcopo - ' . ($cargo ? 'Editar Cargo' : 'Novo Cargo'))

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<style>
    .select2-container {
        width: 100% !important;
    }

    .resumo-texto {
        font-size: 12px;
        color: #6c757d;
        margin-top: 4px;
    }

    .bloco-vinculo {
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 12px;
        margin-bottom: 10px;
        background: #fafafa;
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">{{ $cargo ? 'Editar Cargo' : 'Novo Cargo' }}</h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item">Cadastros</li>
                                <li class="breadcrumb-item"><a href="{{ route('cargos.cargos.index') }}">Cargos</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $cargo ? 'Editar' : 'Novo' }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                @if($cargo)
                    <a href="{{ route('cargos.cargos.index') }}"
                       class="waves-effect waves-light btn mb-5 bg-gradient-secondary">
                        Voltar
                    </a>
                @endif
            </div>
        </div>

        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">{{ $cargo ? 'Dados do Cargo' : 'Cadastrar Cargo' }}</h4>
                        </div>

                        <form method="POST" action="{{ $cargo ? route('cargos.cargos.update', $cargo->id) : route('cargos.cargos.store') }}">
                            @csrf

                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="form-label">Título do Cargo *</label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="titulo_cargo"
                                                   maxlength="255"
                                                   value="{{ old('titulo_cargo', $cargo->titulo_cargo ?? '') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Código Importação</label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="codigo_importacao"
                                                   maxlength="100"
                                                   value="{{ old('codigo_importacao', $cargo->codigo_importacao ?? '') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">CBO *</label>
                                            <select class="form-control select2" name="cargo_cbo_id">
                                                <option value="">Selecione</option>
                                                @foreach($cbos as $cbo)
                                                    <option value="{{ $cbo->id }}"
                                                        {{ (string) old('cargo_cbo_id', $cargo->cargo_cbo_id ?? '') === (string) $cbo->id ? 'selected' : '' }}>
                                                        {{ $cbo->codigo_cbo }} - {{ $cbo->descricao_cbo }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Filiais *</label>
                                            @php
                                                $filiaisSelecionadas = array_map('intval', old('filiais', $cargo->filiais ?? []));
                                            @endphp
                                            <select class="form-control select2" id="filiais" name="filiais[]" multiple>
                                                @foreach($filiais as $filial)
                                                    <option value="{{ $filial->id }}"
                                                        {{ in_array((int)$filial->id, $filiaisSelecionadas) ? 'selected' : '' }}>
                                                        {{ $filial->nome_fantasia }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Setores *</label>
                                            @php
                                                $setoresSelecionados = array_map('intval', old('setores', $cargo->setores ?? []));
                                            @endphp
                                            <select class="form-control select2" id="setores" name="setores[]" multiple {{ count($setoresDisponiveis ?? []) ? '' : 'disabled' }}>
                                                @foreach($setoresDisponiveis as $setor)
                                                    <option value="{{ $setor->id }}"
                                                        {{ in_array((int)$setor->id, $setoresSelecionados) ? 'selected' : '' }}>
                                                        {{ $setor->descricao }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Riscos Ocupacionais</label>
                                            @php
                                                $riscosSelecionados = array_map('intval', old('riscos', $cargo->riscos ?? []));
                                            @endphp
                                            <select class="form-control select2" name="riscos[]" multiple>
                                                @foreach($riscos as $risco)
                                                    <option value="{{ $risco->id }}"
                                                        {{ in_array((int)$risco->id, $riscosSelecionados) ? 'selected' : '' }}>
                                                        {{ $risco->descricao }}{{ $risco->grupo_risco ? ' - ' . $risco->grupo_risco : '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Responsabilidades</label>
                                            @php
                                                $responsabilidadesSelecionadas = array_map('intval', old('responsabilidades', $cargo->responsabilidades ?? []));
                                            @endphp
                                            <select class="form-control select2" name="responsabilidades[]" multiple>
                                                @foreach($responsabilidades as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ in_array((int)$item->id, $responsabilidadesSelecionadas) ? 'selected' : '' }}>
                                                        {{ $item->descricao }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <input type="checkbox"
                                                       id="conta_base_jovem_aprendiz"
                                                       name="conta_base_jovem_aprendiz"
                                                       value="1"
                                                       {{ old('conta_base_jovem_aprendiz', $cargo->conta_base_jovem_aprendiz ?? false) ? 'checked' : '' }}>
                                                <label for="conta_base_jovem_aprendiz">Conta na base do jovem aprendiz</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-15">
                                        <h5>Competências</h5>
                                        <div id="competencias-wrapper">
                                            @php
                                                $competenciasPayload = old('competencias_payload', $cargo->competencias_payload ?? []);
                                            @endphp

                                            @foreach($competenciasPayload as $i => $linha)
                                                <div class="row bloco-vinculo competencia-row">
                                                    <div class="col-md-8">
                                                        <label class="form-label">Competência</label>
                                                        <select class="form-control select2" name="competencias_payload[{{ $i }}][competencia_id]">
                                                            <option value="">Selecione</option>
                                                            @foreach($competencias as $item)
                                                                <option value="{{ $item->id }}"
                                                                    {{ (string)($linha['competencia_id'] ?? '') === (string)$item->id ? 'selected' : '' }}>
                                                                    {{ $item->descricao }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Nota (0 a 10)</label>
                                                        <input type="number"
                                                               min="0"
                                                               max="10"
                                                               class="form-control"
                                                               name="competencias_payload[{{ $i }}][nota]"
                                                               value="{{ $linha['nota'] ?? '' }}"
                                                               placeholder="Opcional">
                                                    </div>
                                                    <div class="col-md-1 d-flex align-items-end">
                                                        <button type="button" class="btn btn-danger btn-remover-linha">
                                                            <i class="fa fa-trash-o"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-primary-light" id="btn-add-competencia">Adicionar Competência</button>
                                    </div>

                                    <div class="col-md-12 mt-15">
                                        <h5>Formações</h5>
                                        <div id="formacoes-wrapper">
                                            @php
                                                $formacoesPayload = old('formacoes_payload', $cargo->formacoes_payload ?? []);
                                            @endphp

                                            @foreach($formacoesPayload as $i => $linha)
                                                <div class="row bloco-vinculo">
                                                    <div class="col-md-7">
                                                        <label class="form-label">Formação</label>
                                                        <select class="form-control select2" name="formacoes_payload[{{ $i }}][formacao_id]">
                                                            <option value="">Selecione</option>
                                                            @foreach($formacoes as $item)
                                                                <option value="{{ $item->id }}"
                                                                    {{ (string)($linha['formacao_id'] ?? '') === (string)$item->id ? 'selected' : '' }}>
                                                                    {{ $item->descricao }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Tipo</label>
                                                        <select class="form-control" name="formacoes_payload[{{ $i }}][tipo]">
                                                            <option value="">Selecione</option>
                                                            <option value="desejado" {{ ($linha['tipo'] ?? '') === 'desejado' ? 'selected' : '' }}>Desejado</option>
                                                            <option value="obrigatorio" {{ ($linha['tipo'] ?? '') === 'obrigatorio' ? 'selected' : '' }}>Obrigatório</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-1 d-flex align-items-end">
                                                        <button type="button" class="btn btn-danger btn-remover-linha">
                                                            <i class="fa fa-trash-o"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-primary-light" id="btn-add-formacao">Adicionar Formação</button>
                                    </div>

                                    <div class="col-md-12 mt-15">
                                        <h5>Cursos</h5>
                                        <div id="cursos-wrapper">
                                            @php
                                                $cursosPayload = old('cursos_payload', $cargo->cursos_payload ?? []);
                                            @endphp

                                            @foreach($cursosPayload as $i => $linha)
                                                <div class="row bloco-vinculo">
                                                    <div class="col-md-7">
                                                        <label class="form-label">Curso</label>
                                                        <select class="form-control select2" name="cursos_payload[{{ $i }}][curso_id]">
                                                            <option value="">Selecione</option>
                                                            @foreach($cursos as $item)
                                                                <option value="{{ $item->id }}"
                                                                    {{ (string)($linha['curso_id'] ?? '') === (string)$item->id ? 'selected' : '' }}>
                                                                    {{ $item->descricao }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Tipo</label>
                                                        <select class="form-control" name="cursos_payload[{{ $i }}][tipo]">
                                                            <option value="">Selecione</option>
                                                            <option value="desejado" {{ ($linha['tipo'] ?? '') === 'desejado' ? 'selected' : '' }}>Desejado</option>
                                                            <option value="obrigatorio" {{ ($linha['tipo'] ?? '') === 'obrigatorio' ? 'selected' : '' }}>Obrigatório</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-1 d-flex align-items-end">
                                                        <button type="button" class="btn btn-danger btn-remover-linha">
                                                            <i class="fa fa-trash-o"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-primary-light" id="btn-add-curso">Adicionar Curso</button>
                                    </div>

                                    <div class="col-md-12 mt-15">
                                        <h5>Escolaridades</h5>
                                        <div id="escolaridades-wrapper">
                                            @php
                                                $escolaridadesPayload = old('escolaridades_payload', $cargo->escolaridades_payload ?? []);
                                            @endphp

                                            @foreach($escolaridadesPayload as $i => $linha)
                                                <div class="row bloco-vinculo">
                                                    <div class="col-md-7">
                                                        <label class="form-label">Escolaridade</label>
                                                        <select class="form-control select2" name="escolaridades_payload[{{ $i }}][escolaridade_id]">
                                                            <option value="">Selecione</option>
                                                            @foreach($escolaridades as $item)
                                                                <option value="{{ $item->id }}"
                                                                    {{ (string)($linha['escolaridade_id'] ?? '') === (string)$item->id ? 'selected' : '' }}>
                                                                    {{ $item->descricao }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Tipo</label>
                                                        <select class="form-control" name="escolaridades_payload[{{ $i }}][tipo]">
                                                            <option value="">Selecione</option>
                                                            <option value="desejado" {{ ($linha['tipo'] ?? '') === 'desejado' ? 'selected' : '' }}>Desejado</option>
                                                            <option value="obrigatorio" {{ ($linha['tipo'] ?? '') === 'obrigatorio' ? 'selected' : '' }}>Obrigatório</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-1 d-flex align-items-end">
                                                        <button type="button" class="btn btn-danger btn-remover-linha">
                                                            <i class="fa fa-trash-o"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-primary-light" id="btn-add-escolaridade">Adicionar Escolaridade</button>
                                    </div>

                                    @if($cargo)
                                        <div class="col-md-4 mt-15">
                                            <div class="form-group">
                                                <label class="form-label">Status Aprovação</label>
                                                <input type="text"
                                                       class="form-control"
                                                       value="{{ $cargo->status_aprovacao }}"
                                                       readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mt-15">
                                            <div class="form-group">
                                                <label class="form-label">Solicitação Aprovação</label>
                                                <input type="text"
                                                       class="form-control"
                                                       value="{{ $cargo->aprovacao_solicitacao_id ?? '' }}"
                                                       readonly>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-md-12">
                                        <p class="resumo-texto">
                                            O status inicial do cargo será definido conforme a configuração central de aprovação para o tipo de referência <strong>cargo</strong>.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="box-footer text-end">
                                <button type="submit" class="btn btn-success">
                                    {{ $cargo ? 'Atualizar' : 'Salvar' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function () {
    function initSelect2Local() {
        $('.select2').select2({
            placeholder: 'Selecione',
            allowClear: true,
            width: '100%'
        });
    }

    initSelect2Local();

    let setoresSelecionadosIniciais = @json(array_map('intval', old('setores', $cargo->setores ?? [])));
    let idxCompetencia = {{ count(old('competencias_payload', $cargo->competencias_payload ?? [])) }};
    let idxFormacao = {{ count(old('formacoes_payload', $cargo->formacoes_payload ?? [])) }};
    let idxCurso = {{ count(old('cursos_payload', $cargo->cursos_payload ?? [])) }};
    let idxEscolaridade = {{ count(old('escolaridades_payload', $cargo->escolaridades_payload ?? [])) }};

    function carregarSetoresPorFiliais() {
        const filiais = $('#filiais').val() || [];
        const $setores = $('#setores');

        if (!filiais.length) {
            $setores.html('').prop('disabled', true).trigger('change');
            return;
        }

        $.ajax({
            url: '{{ route('cargos.cargos.setores_por_filiais') }}',
            method: 'GET',
            data: { filiais: filiais },
            success: function (response) {
                const setores = response.data || [];
                const selecionadosAtuais = ($setores.val() || []).map(Number);
                const baseSelecionada = selecionadosAtuais.length ? selecionadosAtuais : setoresSelecionadosIniciais;

                $setores.html('');

                setores.forEach(function (setor) {
                    const selected = baseSelecionada.includes(Number(setor.id)) ? 'selected' : '';
                    $setores.append('<option value="' + setor.id + '" ' + selected + '>' + setor.descricao + '</option>');
                });

                $setores.prop('disabled', false).trigger('change');
                setoresSelecionadosIniciais = [];
            },
            error: function () {
                toastr.error('Erro ao carregar setores das filiais selecionadas.');
            }
        });
    }

    $('#filiais').on('change', function () {
        carregarSetoresPorFiliais();
    });

    @if(count(old('filiais', $cargo->filiais ?? [])))
        carregarSetoresPorFiliais();
    @endif

    $('#btn-add-competencia').on('click', function () {
        $('#competencias-wrapper').append(`
            <div class="row bloco-vinculo competencia-row">
                <div class="col-md-8">
                    <label class="form-label">Competência</label>
                    <select class="form-control select2" name="competencias_payload[${idxCompetencia}][competencia_id]">
                        <option value="">Selecione</option>
                        @foreach($competencias as $item)
                            <option value="{{ $item->id }}">{{ $item->descricao }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Nota (0 a 10)</label>
                    <input type="number" min="0" max="10" class="form-control" name="competencias_payload[${idxCompetencia}][nota]" placeholder="Opcional">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-remover-linha"><i class="fa fa-trash-o"></i></button>
                </div>
            </div>
        `);
        idxCompetencia++;
        initSelect2Local();
    });

    $('#btn-add-formacao').on('click', function () {
        $('#formacoes-wrapper').append(`
            <div class="row bloco-vinculo">
                <div class="col-md-7">
                    <label class="form-label">Formação</label>
                    <select class="form-control select2" name="formacoes_payload[${idxFormacao}][formacao_id]">
                        <option value="">Selecione</option>
                        @foreach($formacoes as $item)
                            <option value="{{ $item->id }}">{{ $item->descricao }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tipo</label>
                    <select class="form-control" name="formacoes_payload[${idxFormacao}][tipo]">
                        <option value="">Selecione</option>
                        <option value="desejado">Desejado</option>
                        <option value="obrigatorio">Obrigatório</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-remover-linha"><i class="fa fa-trash-o"></i></button>
                </div>
            </div>
        `);
        idxFormacao++;
        initSelect2Local();
    });

    $('#btn-add-curso').on('click', function () {
        $('#cursos-wrapper').append(`
            <div class="row bloco-vinculo">
                <div class="col-md-7">
                    <label class="form-label">Curso</label>
                    <select class="form-control select2" name="cursos_payload[${idxCurso}][curso_id]">
                        <option value="">Selecione</option>
                        @foreach($cursos as $item)
                            <option value="{{ $item->id }}">{{ $item->descricao }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tipo</label>
                    <select class="form-control" name="cursos_payload[${idxCurso}][tipo]">
                        <option value="">Selecione</option>
                        <option value="desejado">Desejado</option>
                        <option value="obrigatorio">Obrigatório</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-remover-linha"><i class="fa fa-trash-o"></i></button>
                </div>
            </div>
        `);
        idxCurso++;
        initSelect2Local();
    });

    $('#btn-add-escolaridade').on('click', function () {
        $('#escolaridades-wrapper').append(`
            <div class="row bloco-vinculo">
                <div class="col-md-7">
                    <label class="form-label">Escolaridade</label>
                    <select class="form-control select2" name="escolaridades_payload[${idxEscolaridade}][escolaridade_id]">
                        <option value="">Selecione</option>
                        @foreach($escolaridades as $item)
                            <option value="{{ $item->id }}">{{ $item->descricao }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tipo</label>
                    <select class="form-control" name="escolaridades_payload[${idxEscolaridade}][tipo]">
                        <option value="">Selecione</option>
                        <option value="desejado">Desejado</option>
                        <option value="obrigatorio">Obrigatório</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-remover-linha"><i class="fa fa-trash-o"></i></button>
                </div>
            </div>
        `);
        idxEscolaridade++;
        initSelect2Local();
    });

    $('#btn-add-competencia').triggerHandler('init');

    $(document).on('click', '.btn-remover-linha', function () {
        $(this).closest('.row').remove();
    });
});
</script>
@endsection
