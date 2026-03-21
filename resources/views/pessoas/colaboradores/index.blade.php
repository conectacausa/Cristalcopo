@extends('layouts.app')

@section('title', 'Colaboradores')

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

        <section class="content">
            @include('pessoas.colaboradores.partials.filtros')

            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">Listagem</h4>
                        </div>

                        <div class="box-body">
                            <div id="resultado-tabela">
                                @include('pessoas.colaboradores.partials.tabela', [
                                    'colaboradores' => $colaboradores,
                                    'permissao' => $permissao
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

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container {
        width: 100% !important;
    }

    .select2-container .select2-selection--multiple {
        min-height: 38px !important;
        border: 1px solid #d2d6de !important;
        border-radius: 4px !important;
        padding: 2px 6px !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        display: block !important;
        white-space: normal !important;
        padding-top: 2px !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        margin-top: 4px !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__placeholder {
        color: #999 !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {
    let filtroTimeout = null;

    function iniciarSelect2() {
        $('select.select2').each(function () {
            if ($(this).hasClass('select2-hidden-accessible')) {
                $(this).select2('destroy');
            }

            $(this).select2({
                width: '100%',
                placeholder: $(this).data('placeholder') || 'Selecione',
                allowClear: false,
                closeOnSelect: false
            });
        });
    }

    function atualizarTabela(url = null) {
        const form = $('#form-filtros');
        const targetUrl = url || form.attr('action');

        $.ajax({
            url: targetUrl,
            method: 'GET',
            data: form.serialize(),
            success: function (html) {
                $('#resultado-tabela').html(html);
            },
            error: function () {
                toastr.error('Erro ao atualizar a tabela.');
            }
        });
    }

    function carregarSetores(callback = null) {
        const filiais = $('select[name="filiais[]"]').val() || [];
        const setoresSelecionados = @json($filtros['setores'] ?? []);
        const $setores = $('select[name="setores[]"]');
        const $cargos = $('select[name="cargos[]"]');

        $setores.prop('disabled', true).empty().trigger('change');
        $cargos.prop('disabled', true).empty().trigger('change');

        if (filiais.length === 0) {
            if (typeof callback === 'function') {
                callback();
            }
            return;
        }

        $.ajax({
            url: "{{ route('pessoas.colaboradores.setores') }}",
            method: 'GET',
            traditional: true,
            data: { filiais: filiais },
            success: function (response) {
                $setores.empty();

                response.forEach(function (item) {
                    const selected = setoresSelecionados.map(String).includes(String(item.id));
                    const option = new Option(item.descricao, item.id, false, selected);
                    $setores.append(option);
                });

                $setores.prop('disabled', false).trigger('change.select2');

                if (typeof callback === 'function') {
                    callback();
                }
            },
            error: function () {
                toastr.error('Erro ao carregar setores.');
            }
        });
    }

    function carregarCargos(callback = null) {
        const setores = $('select[name="setores[]"]').val() || [];
        const cargosSelecionados = @json($filtros['cargos'] ?? []);
        const $cargos = $('select[name="cargos[]"]');

        $cargos.prop('disabled', true).empty().trigger('change');

        if (setores.length === 0) {
            if (typeof callback === 'function') {
                callback();
            }
            return;
        }

        $.ajax({
            url: "{{ route('pessoas.colaboradores.cargos') }}",
            method: 'GET',
            traditional: true,
            data: { setores: setores },
            success: function (response) {
                $cargos.empty();

                response.forEach(function (item) {
                    const selected = cargosSelecionados.map(String).includes(String(item.id));
                    const option = new Option(item.titulo_cargo, item.id, false, selected);
                    $cargos.append(option);
                });

                $cargos.prop('disabled', false).trigger('change.select2');

                if (typeof callback === 'function') {
                    callback();
                }
            },
            error: function () {
                toastr.error('Erro ao carregar cargos.');
            }
        });
    }

    function dispararFiltroComDelay() {
        clearTimeout(filtroTimeout);
        filtroTimeout = setTimeout(function () {
            atualizarTabela();
        }, 300);
    }

    iniciarSelect2();

    const filiaisIniciais = $('select[name="filiais[]"]').val() || [];
    if (filiaisIniciais.length > 0) {
        carregarSetores(function () {
            const setoresIniciais = $('select[name="setores[]"]').val() || [];
            if (setoresIniciais.length > 0) {
                carregarCargos();
            }
        });
    } else {
        $('select[name="setores[]"]').prop('disabled', true);
        $('select[name="cargos[]"]').prop('disabled', true);
    }

    $('#form-filtros').on('input', 'input[name="texto"]', function () {
        dispararFiltroComDelay();
    });

    $('#form-filtros').on('change', 'select[name="situacao"]', function () {
        dispararFiltroComDelay();
    });

    $('#form-filtros').on('change', 'select[name="filiais[]"]', function () {
        $('select[name="setores[]"]').empty().trigger('change');
        $('select[name="cargos[]"]').empty().trigger('change');

        carregarSetores(function () {
            dispararFiltroComDelay();
        });
    });

    $('#form-filtros').on('change', 'select[name="setores[]"]', function () {
        $('select[name="cargos[]"]').empty().trigger('change');

        carregarCargos(function () {
            dispararFiltroComDelay();
        });
    });

    $('#form-filtros').on('change', 'select[name="cargos[]"]', function () {
        dispararFiltroComDelay();
    });

    $(document).on('click', '#resultado-tabela .pagination a', function (e) {
        e.preventDefault();
        atualizarTabela($(this).attr('href'));
    });

    $(document).on('click', '.btn-excluir-colaborador', function (e) {
        e.preventDefault();

        const form = $(this).closest('form');

        swal({
            title: 'Excluir colaborador?',
            text: 'Esta ação fará exclusão lógica do registro.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, excluir',
            cancelButtonText: 'Cancelar'
        }, function (isConfirm) {
            if (isConfirm) {
                form.submit();
            }
        });
    });
});
</script>
@endpush
