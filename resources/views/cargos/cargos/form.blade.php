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
                                                $filiaisSelecionadas = old('filiais', $cargo->filiais ?? []);
                                            @endphp
                                            <select class="form-control select2" id="filiais" name="filiais[]" multiple>
                                                @foreach($filiais as $filial)
                                                    <option value="{{ $filial->id }}"
                                                        {{ in_array($filial->id, $filiaisSelecionadas) ? 'selected' : '' }}>
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
                                                $setoresSelecionados = old('setores', $cargo->setores ?? []);
                                            @endphp
                                            <select class="form-control select2" id="setores" name="setores[]" multiple {{ count($setoresDisponiveis ?? []) ? '' : 'disabled' }}>
                                                @foreach($setoresDisponiveis as $setor)
                                                    <option value="{{ $setor->id }}"
                                                        {{ in_array($setor->id, $setoresSelecionados) ? 'selected' : '' }}>
                                                        {{ $setor->descricao }}
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

                                    @if($cargo)
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Status Aprovação</label>
                                                <input type="text"
                                                       class="form-control"
                                                       value="{{ $cargo->status_aprovacao }}"
                                                       readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
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
        $('.select2').select2({
            placeholder: 'Selecione',
            allowClear: true,
            width: '100%'
        });

        let setoresSelecionadosIniciais = @json(array_map('intval', old('setores', $cargo->setores ?? [])));

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
    });
</script>
@endsection
