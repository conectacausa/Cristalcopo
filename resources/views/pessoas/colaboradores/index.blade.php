@extends('layouts.app')

@section('title', 'Colaboradores')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Colaboradores</h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item">Pessoas</li>
                                <li class="breadcrumb-item active" aria-current="page">Colaboradores</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                @if($permissao->pode_gravar)
                    <a href="#" class="waves-effect waves-light btn mb-5 bg-gradient-success w-200">
                        Novo Colaborador
                    </a>
                @endif
            </div>
        </div>

        <section class="content">
            @include('pessoas.colaboradores.partials.filtros')

            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">Listagem de Colaboradores</h4>
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
<script>
$(document).ready(function () {
    $('.select2').select2({
        width: '100%'
    });

    let filtroTimeout = null;

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

    $('#form-filtros').on('input change', 'input, select', function () {
        clearTimeout(filtroTimeout);
        filtroTimeout = setTimeout(function () {
            atualizarTabela();
        }, 300);
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
