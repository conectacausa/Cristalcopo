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
        border: 1px solid #ced4da !important;
        border-radius: 0.25rem !important;
        padding: 2px 6px !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        margin-top: 4px !important;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #80bdff !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {
    let filtroTimeout = null;

    function iniciarSelect2() {
        $('select[name="filiais[]"]').select2({
            width: '100%',
            placeholder: 'Selecione as filiais',
            allowClear: true,
            closeOnSelect: false
        });

        $('select[name="setores[]"]').select2({
            width: '100%',
            placeholder: 'Selecione os setores',
            allowClear: true,
            closeOnSelect: false
        });

        $('select[name="cargos[]"]').select2({
            width: '100%',
            placeholder: 'Selecione os cargos',
            allowClear: true,
            closeOnSelect: false
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
                toastr.error('Erro ao atualizar tabela.');
            }
        });
    }

    function carregarSetores(callback = null) {
        const filiais = $('select[name="filiais[]"]').val() || [];
        const $setores = $('select[name="setores[]"]');

        $setores.prop('disabled', true).empty().trigger('change');

        if (filiais.length === 0) {
            $('select[name="cargos[]"]').prop('disabled', true).empty().trigger('change');
            if (typeof callback === 'function') callback();
            return;
        }

        $.ajax({
            url: "{{ route('pessoas.colaboradores.setores') }}",
            method: 'GET',
            data: { filiais: filiais },
            success: function (response) {
                $setores.empty();

                response.forEach(function (item) {
                    const option = new Option(item.descricao, item.id, false, false);
                    $setores.append(option);
                });

                $setores.prop('disabled', false).trigger('change');

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
        const $cargos = $('select[name="cargos[]"]');

        $cargos.prop('disabled', true).empty().trigger('change');

        if (setores.length === 0) {
            if (typeof callback === 'function') callback();
            return;
        }

        $.ajax({
            url: "{{ route('pessoas.colaboradores.cargos') }}",
            method: 'GET',
            data: { setores: setores },
            success: function (response) {
                $cargos.empty();

                response.forEach(function (item) {
                    const option = new Option(item.titulo_cargo, item.id, false, false);
                    $cargos.append(option);
                });

                $cargos.prop('disabled', false).trigger('change');

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

    $('select[name="setores[]"]').prop('disabled', true);
    $('select[name="cargos[]"]').prop('disabled', true);

    $('#form-filtros').on('input', 'input[name="texto"]', function () {
        dispararFiltroComDelay();
    });

    $('#form-filtros').on('change', 'select[name="situacao"]', function () {
        dispararFiltroComDelay();
    });

    $('#form-filtros').on('change', 'select[name="filiais[]"]', function () {
        carregarSetores(function () {
            $('select[name="cargos[]"]').prop('disabled', true).empty().trigger('change');
            dispararFiltroComDelay();
        });
    });

    $('#form-filtros').on('change', 'select[name="setores[]"]', function () {
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
