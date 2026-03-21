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

            {{-- FILTROS --}}
            @include('pessoas.colaboradores.partials.filtros')

            {{-- TABELA --}}
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

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(function () {

    $('.select2').select2({ width: '100%' });

    function atualizarTabela(url = null) {
        const form = $('#form-filtros');

        $.get(url || form.attr('action'), form.serialize(), function (html) {
            $('#resultado-tabela').html(html);
        }).fail(function () {
            toastr.error('Erro ao atualizar tabela');
        });
    }

    function carregarSetores() {
        const filiais = $('select[name="filiais[]"]').val();

        $.get("{{ route('pessoas.colaboradores.setores') }}", { filiais }, function (data) {

            let select = $('select[name="setores[]"]');
            select.empty();

            data.forEach(item => {
                select.append(new Option(item.descricao, item.id));
            });

            select.trigger('change.select2');
        });
    }

    function carregarCargos() {
        const setores = $('select[name="setores[]"]').val();

        $.get("{{ route('pessoas.colaboradores.cargos') }}", { setores }, function (data) {

            let select = $('select[name="cargos[]"]');
            select.empty();

            data.forEach(item => {
                select.append(new Option(item.titulo_cargo, item.id));
            });

            select.trigger('change.select2');
        });
    }

    $('#form-filtros').on('change', 'select[name="filiais[]"]', function () {
        carregarSetores();
        $('select[name="cargos[]"]').empty();
        atualizarTabela();
    });

    $('#form-filtros').on('change', 'select[name="setores[]"]', function () {
        carregarCargos();
        atualizarTabela();
    });

    $('#form-filtros').on('change input', 'input, select[name="cargos[]"], select[name="situacao"]', function () {
        atualizarTabela();
    });

    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        atualizarTabela($(this).attr('href'));
    });

});
</script>
@endpush
