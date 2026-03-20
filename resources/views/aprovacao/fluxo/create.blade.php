<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}">

    <title>Cristalcopo - Novo Fluxo de Aprovação</title>

    <link rel="stylesheet" href="{{ asset('assets/css/vendors_css.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/skin_color.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <style>
        .etapa-card {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background: #fdfdfd;
        }

        .etapa-card:last-child {
            margin-bottom: 0;
        }
    </style>
</head>
<body class="hold-transition light-skin sidebar-mini theme-primary fixed">

<div class="wrapper">
    <div id="loader"></div>

    @include('layouts.includes.header')
    @include('layouts.includes.menu')

    <div class="content-wrapper">
        <div class="container-full">

            <div class="content-header">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <h4 class="page-title">Novo Fluxo de Aprovação</h4>
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ url('/dashboard') }}">
                                            <i class="mdi mdi-home-outline"></i>
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item">Configuração</li>
                                    <li class="breadcrumb-item">Aprovações</li>
                                    <li class="breadcrumb-item active" aria-current="page">Novo Fluxo</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <form action="{{ route('aprovacao.fluxo.store') }}" method="POST" id="form-fluxo">
                    @csrf

                    <div class="row">
                        <div class="col-12">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h4 class="box-title">Dados do Fluxo</h4>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Nome do Fluxo *</label>
                                                <input
                                                    type="text"
                                                    name="nome_fluxo"
                                                    class="form-control"
                                                    value="{{ old('nome_fluxo') }}"
                                                    required
                                                >
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Tipo de Referência *</label>
                                                <input
                                                    type="text"
                                                    name="tipo_referencia"
                                                    class="form-control"
                                                    placeholder="Ex.: cargo, filial, colaborador"
                                                    value="{{ old('tipo_referencia') }}"
                                                    required
                                                >
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Descrição</label>
                                                <textarea
                                                    name="descricao"
                                                    class="form-control"
                                                    rows="3"
                                                >{{ old('descricao') }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Modo de Aprovação *</label>
                                                <select name="modo_aprovacao" class="form-select" required>
                                                    <option value="sequencial" {{ old('modo_aprovacao') === 'sequencial' ? 'selected' : '' }}>Sequencial</option>
                                                    <option value="paralelo" {{ old('modo_aprovacao') === 'paralelo' ? 'selected' : '' }}>Paralelo</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Permite Reprovação *</label>
                                                <select name="permite_reprovacao" class="form-select" required>
                                                    <option value="1" {{ old('permite_reprovacao', '1') == '1' ? 'selected' : '' }}>Sim</option>
                                                    <option value="0" {{ old('permite_reprovacao') == '0' ? 'selected' : '' }}>Não</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Permite Retorno *</label>
                                                <select name="permite_retorno" class="form-select" required>
                                                    <option value="1" {{ old('permite_retorno', '1') == '1' ? 'selected' : '' }}>Sim</option>
                                                    <option value="0" {{ old('permite_retorno') == '0' ? 'selected' : '' }}>Não</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Situação *</label>
                                                <select name="situacao" class="form-select" required>
                                                    <option value="ativo" {{ old('situacao', 'ativo') === 'ativo' ? 'selected' : '' }}>Ativo</option>
                                                    <option value="inativo" {{ old('situacao') === 'inativo' ? 'selected' : '' }}>Inativo</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="box">
                                <div class="box-header with-border d-flex justify-content-between align-items-center">
                                    <h4 class="box-title mb-0">Etapas</h4>
                                    <button type="button" id="btn-adicionar-etapa" class="btn btn-info btn-sm">
                                        Adicionar Etapa
                                    </button>
                                </div>

                                <div class="box-body">
                                    <div id="etapas-container">
                                        <div class="etapa-item etapa-card" data-index="0">
                                            <div class="row">
                                                <div class="col-12 d-flex justify-content-between align-items-center mb-15">
                                                    <h5 class="mb-0 titulo-etapa">Etapa 1</h5>
                                                    <button type="button" class="btn btn-danger btn-sm remover-etapa" style="display:none;">
                                                        Remover
                                                    </button>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Nome da Etapa *</label>
                                                        <input
                                                            type="text"
                                                            name="etapas[0][nome_etapa]"
                                                            class="form-control"
                                                            required
                                                        >
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Ordem *</label>
                                                        <input
                                                            type="number"
                                                            name="etapas[0][ordem]"
                                                            class="form-control ordem-etapa"
                                                            value="1"
                                                            min="1"
                                                            required
                                                        >
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Tipo de Aprovação *</label>
                                                        <select
                                                            name="etapas[0][tipo_aprovacao_etapa]"
                                                            class="form-select tipo-aprovacao-etapa"
                                                            required
                                                        >
                                                            <option value="unanimidade">Unanimidade</option>
                                                            <option value="qualquer_um">Qualquer um</option>
                                                            <option value="maioria">Maioria</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 campo-quantidade-minima" style="display:none;">
                                                    <div class="form-group">
                                                        <label class="form-label">Qtd. Mínima Aprovação</label>
                                                        <input
                                                            type="number"
                                                            name="etapas[0][quantidade_minima_aprovacao]"
                                                            class="form-control"
                                                            min="1"
                                                        >
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Aprovadores *</label>
                                                        <select
                                                            name="etapas[0][aprovadores][]"
                                                            class="form-select select-aprovadores"
                                                            multiple
                                                            required
                                                            data-placeholder="Selecione os aprovadores"
                                                        >
                                                            @foreach($colaboradores as $colaborador)
                                                                <option value="{{ $colaborador->id }}">
                                                                    {{ $colaborador->nome_completo }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <small class="text-muted">
                                                            Pesquise e selecione um ou mais aprovadores.
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="box-footer text-end">
                                    <button type="submit" class="waves-effect waves-light btn bg-gradient-success">
                                        Salvar Fluxo
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>

    @include('layouts.includes.footer')
</div>

<script src="{{ asset('assets/js/vendors.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('assets/js/pages/chat-popup.js') }}"></script>
<script src="{{ asset('assets/icons/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('assets/vendor_components/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/vendor_components/sweetalert/jquery.sweet-alert.custom.js') }}"></script>
<script src="{{ asset('assets/js/template.js') }}"></script>

@if ($errors->any())
    <script>
        @foreach ($errors->all() as $error)
            toastr.error(@json($error));
        @endforeach
    </script>
@endif

<script>
    let etapaIndex = 1;

    function getColaboradoresOptions() {
        return `
            @foreach($colaboradores as $colaborador)
                <option value="{{ $colaborador->id }}">{{ $colaborador->nome_completo }}</option>
            @endforeach
        `;
    }

    function inicializarSelectBusca(context) {
    context.find('.select-aprovadores').each(function() {
        if ($(this).hasClass('select2-hidden-accessible')) {
            return;
        }

        $(this).select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: $(this).data('placeholder') || 'Selecione',
            closeOnSelect: false,
            allowClear: false
        });
    });
}

    function atualizarTitulosEtapas() {
        $('.etapa-item').each(function(index) {
            $(this).find('.titulo-etapa').text('Etapa ' + (index + 1));
            $(this).find('.ordem-etapa').val(index + 1);
            $(this).find('.remover-etapa').toggle($('.etapa-item').length > 1);
        });
    }

    function bindEventosEtapa(context) {
        context.find('.tipo-aprovacao-etapa').off('change').on('change', function() {
            const etapa = $(this).closest('.etapa-item');
            const valor = $(this).val();

            if (valor === 'maioria') {
                etapa.find('.campo-quantidade-minima').show();
            } else {
                etapa.find('.campo-quantidade-minima').hide();
                etapa.find('input[name*="[quantidade_minima_aprovacao]"]').val('');
            }
        });

        context.find('.remover-etapa').off('click').on('click', function() {
            const etapa = $(this).closest('.etapa-item');
            const select = etapa.find('.select-aprovadores');

            if (select.hasClass('select2-hidden-accessible')) {
                select.select2('destroy');
            }

            etapa.remove();
            atualizarTitulosEtapas();
        });
    }

    $('#btn-adicionar-etapa').on('click', function() {
        const html = `
            <div class="etapa-item etapa-card" data-index="${etapaIndex}">
                <div class="row">
                    <div class="col-12 d-flex justify-content-between align-items-center mb-15">
                        <h5 class="mb-0 titulo-etapa">Etapa</h5>
                        <button type="button" class="btn btn-danger btn-sm remover-etapa">
                            Remover
                        </button>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Nome da Etapa *</label>
                            <input
                                type="text"
                                name="etapas[${etapaIndex}][nome_etapa]"
                                class="form-control"
                                required
                            >
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Ordem *</label>
                            <input
                                type="number"
                                name="etapas[${etapaIndex}][ordem]"
                                class="form-control ordem-etapa"
                                value="${etapaIndex + 1}"
                                min="1"
                                required
                            >
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Tipo de Aprovação *</label>
                            <select
                                name="etapas[${etapaIndex}][tipo_aprovacao_etapa]"
                                class="form-select tipo-aprovacao-etapa"
                                required
                            >
                                <option value="unanimidade">Unanimidade</option>
                                <option value="qualquer_um">Qualquer um</option>
                                <option value="maioria">Maioria</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 campo-quantidade-minima" style="display:none;">
                        <div class="form-group">
                            <label class="form-label">Qtd. Mínima Aprovação</label>
                            <input
                                type="number"
                                name="etapas[${etapaIndex}][quantidade_minima_aprovacao]"
                                class="form-control"
                                min="1"
                            >
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Aprovadores *</label>
                            <select
                                name="etapas[${etapaIndex}][aprovadores][]"
                                class="form-select select-aprovadores"
                                multiple
                                required
                                data-placeholder="Selecione os aprovadores"
                            >
                                ${getColaboradoresOptions()}
                            </select>
                            <small class="text-muted">
                                Pesquise e selecione um ou mais aprovadores.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        `;

        $('#etapas-container').append(html);

        const novaEtapa = $('#etapas-container .etapa-item').last();

        bindEventosEtapa(novaEtapa);
        inicializarSelectBusca(novaEtapa);
        atualizarTitulosEtapas();

        etapaIndex++;
    });

    $(document).ready(function() {
        bindEventosEtapa($(document));
        inicializarSelectBusca($(document));
        atualizarTitulosEtapas();
    });
</script>

</body>
</html>
