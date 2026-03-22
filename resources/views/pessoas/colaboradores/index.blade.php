@extends('layouts.app')

@section('title', 'Cristalcopo - Colaboradores')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<style>
    .select2-container {
        width: 100% !important;
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-full">

        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Colaboradores</h4>
                </div>
            </div>
        </div>

        @include('pessoas.colaboradores.partials.filtros')

        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">Colaboradores</h4>
                        </div>

                        <div class="box-body">
                            <div id="resultado-tabela">
                                @include('pessoas.colaboradores.partials.tabela', [
                                    'dados' => $dados,
                                    'permissoes' => $permissoes,
                                ])
                            </div>
                        </div>

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
    const $filiais = $('select[name="filiais[]"]');
    const $setores = $('select[name="setores[]"]');
    const $cargos = $('select[name="cargos[]"]');

    $('.select2').select2({
        placeholder: 'Selecione',
        allowClear: true,
        width: '100%'
    });

    let request = null;
    let debounce = null;

    function carregarTabela(url = '{{ route('pessoas.colaboradores.index') }}') {
        if (request) {
            request.abort();
        }

        request = $.ajax({
            url: url,
            method: 'GET',
            data: $('#form-filtros').serialize(),
            success: function (response) {
                $('#resultado-tabela').html(response);
            },
            error: function (xhr, status) {
                if (status === 'abort') {
                    return;
                }

                toastr.error('Não foi possível carregar os colaboradores.');
            }
        });
    }

    function carregarSetores(selecionados = [], onLoaded = null) {
        $.ajax({
            url: "{{ route('pessoas.colaboradores.setores') }}",
            method: 'GET',
            data: { filiais: $filiais.val() || [] },
            success: function (data) {
                $setores.empty();

                data.forEach(function (item) {
                    const selected = selecionados.includes(String(item.id)) || selecionados.includes(item.id);
                    const option = new Option(item.descricao, item.id, selected, selected);
                    $setores.append(option);
                });

                $setores.trigger('change.select2');

                if (typeof onLoaded === 'function') {
                    onLoaded();
                }
            },
            error: function () {
                toastr.error('Erro ao carregar setores.');
            }
        });
    }

    function carregarCargos(selecionados = []) {
        $.ajax({
            url: "{{ route('pessoas.colaboradores.cargos') }}",
            method: 'GET',
            data: { setores: $setores.val() || [] },
            success: function (data) {
                $cargos.empty();

                data.forEach(function (item) {
                    const selected = selecionados.includes(String(item.id)) || selecionados.includes(item.id);
                    const option = new Option(item.titulo_cargo, item.id, selected, selected);
                    $cargos.append(option);
                });

                $cargos.trigger('change.select2');
            },
            error: function () {
                toastr.error('Erro ao carregar cargos.');
            }
        });
    }

    $('input[name="texto"]').on('keyup', function () {
        clearTimeout(debounce);
        debounce = setTimeout(function () {
            carregarTabela();
        }, 300);
    });

    $('select[name="situacao"]').on('change', function () {
        carregarTabela();
    });

    $filiais.on('change', function () {
        carregarSetores();
        $cargos.empty().trigger('change.select2');
        carregarTabela();
    });

    $setores.on('change', function () {
        carregarCargos();
        carregarTabela();
    });

    $cargos.on('change', function () {
        carregarTabela();
    });

    $(document).on('click', '#resultado-tabela .pagination a', function (e) {
        e.preventDefault();
        carregarTabela($(this).attr('href'));
    });

    const setoresSelecionados = @json(array_map('strval', $filtros['setores'] ?? []));
    const cargosSelecionados = @json(array_map('strval', $filtros['cargos'] ?? []));

    if (($filiais.val() || []).length > 0) {
        carregarSetores(setoresSelecionados, function () {
            if (setoresSelecionados.length > 0) {
                carregarCargos(cargosSelecionados);
            }
        });
    }
});
</script>
@endsection
