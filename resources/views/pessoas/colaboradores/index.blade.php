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

        {{-- FILTROS --}}
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

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {

    // ✅ SELECT2 (PADRÃO CARGOS)
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
            error: function () {
                toastr.error('Erro ao carregar colaboradores.');
            }
        });
    }

    // 🔎 TEXTO
    $('input[name="texto"]').on('keyup', function () {
        clearTimeout(debounce);
        debounce = setTimeout(function () {
            carregarTabela();
        }, 300);
    });

    // 📌 SITUAÇÃO
    $('select[name="situacao"]').on('change', function () {
        carregarTabela();
    });

    // 🏢 FILIAIS → SETORES
    $('select[name="filiais[]"]').on('change', function () {

        let filiais = $(this).val();

        $.ajax({
            url: "{{ route('pessoas.colaboradores.setores') }}",
            method: 'GET',
            data: { filiais: filiais },
            success: function (data) {

                let $setores = $('select[name="setores[]"]');
                $setores.empty();

                data.forEach(function (item) {
                    $setores.append(new Option(item.descricao, item.id));
                });

                $setores.trigger('change');

                carregarTabela();
            },
            error: function () {
                toastr.error('Erro ao carregar setores.');
            }
        });
    });

    // 🧩 SETORES → CARGOS
    $('select[name="setores[]"]').on('change', function () {

        let setores = $(this).val();

        $.ajax({
            url: "{{ route('pessoas.colaboradores.cargos') }}",
            method: 'GET',
            data: { setores: setores },
            success: function (data) {

                let $cargos = $('select[name="cargos[]"]');
                $cargos.empty();

                data.forEach(function (item) {
                    $cargos.append(new Option(item.titulo_cargo, item.id));
                });

                $cargos.trigger('change');

                carregarTabela();
            },
            error: function () {
                toastr.error('Erro ao carregar cargos.');
            }
        });
    });

    // 🧾 CARGOS
    $('select[name="cargos[]"]').on('change', function () {
        carregarTabela();
    });

    // 📄 PAGINAÇÃO AJAX
    $(document).on('click', '#resultado-tabela .pagination a', function (e) {
        e.preventDefault();
        carregarTabela($(this).attr('href'));
    });

    // 🗑️ EXCLUSÃO (SWEETALERT PADRÃO)
    $(document).on('click', '.btn-excluir-colaborador', function (e) {
        e.preventDefault();

        const form = $(this).closest('form');

        swal({
            title: 'Excluir colaborador?',
            text: 'Esta ação fará a exclusão lógica do registro.',
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
@endsection
