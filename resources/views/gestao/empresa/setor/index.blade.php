<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Cristalcopo - Setor</title>

    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/vendors_css.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/skin_color.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
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
                        <h4 class="page-title">Setor</h4>
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="#"><i class="mdi mdi-home-outline"></i></a>
                                    </li>
                                    <li class="breadcrumb-item">Empresa</li>
                                    <li class="breadcrumb-item active">Setor</li>
                                </ol>
                            </nav>
                        </div>
                    </div>

                    <button type="button" onclick="abrirNovo()" class="waves-effect waves-light btn mb-5 bg-gradient-success w-200">
                        Novo Setor
                    </button>
                </div>
            </div>

            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h4 class="box-title">Filtros</h4>
                            </div>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label class="form-label">Nome</label>
                                            <input type="text" id="nome" class="form-control" placeholder="Nome do setor">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label">Filial</label>
                                            <select id="filial_id" class="form-control">
                                                <option value="">Todas as filiais</option>
                                                @foreach($filiais as $filial)
                                                    <option value="{{ $filial->id }}">{{ $filial->nome }}</option>
                                                @endforeach
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
                            <div class="box-header with-border">
                                <h4 class="box-title">Setor</h4>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive" id="tabela">
                                    <div class="text-center p-20">Carregando...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    @include('layouts.includes.footer')
</div>

@include('gestao.empresa.setor.partials.modal')

<script src="{{ asset('assets/js/vendors.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/chat-popup.js') }}"></script>
<script src="{{ asset('assets/icons/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('assets/vendor_components/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/vendor_components/sweetalert/jquery.sweet-alert.custom.js') }}"></script>
<script src="{{ asset('assets/js/template.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: "toast-top-right",
    timeOut: "3000"
};

let filtroTimer;

$('#nome').on('keyup', function () {
    clearTimeout(filtroTimer);
    filtroTimer = setTimeout(() => {
        carregarTabela();
    }, 400);
});

$('#filial_id').on('change', function () {
    carregarTabela();
});

function carregarTabela(page = 1) {
    $('#tabela').html('<div class="text-center p-20">Carregando...</div>');

    $.get('/empresa/setor/list?page=' + page + '&nome=' + $('#nome').val() + '&filial_id=' + $('#filial_id').val(), function (data) {
        $('#tabela').html(data);
    }).fail(function(xhr) {
        console.error(xhr.responseText);
        toastr.error('Erro ao carregar setores');
    });
}

function abrirNovo() {
    $('#modalSetor').modal('show');
    $('#formSetor')[0].reset();
    $('#id').val('');
    $('input[name="filiais[]"]').prop('checked', false);
}

function editar(id, descricao, filiais) {
    $('#modalSetor').modal('show');
    $('#id').val(id);
    $('#descricao_setor').val(descricao);

    $('input[name="filiais[]"]').prop('checked', false);

    if (Array.isArray(filiais)) {
        filiais.forEach(function (filialId) {
            $('#filial_' + filialId).prop('checked', true);
        });
    }
}

function salvar() {
    let id = $('#id').val();
    let url = id ? '/empresa/setor/update/' + id : '/empresa/setor/store';

    $.post(url, $('#formSetor').serialize())
    .done(function () {
        $('#modalSetor').modal('hide');
        toastr.success('Setor salvo com sucesso');
        carregarTabela();
    })
    .fail(function(xhr) {
        console.error(xhr.responseText);
        toastr.error('Erro ao salvar setor');
    });
}

function excluir(id) {
    swal({
        title: "Confirmar exclusão?",
        text: "Essa ação não poderá ser desfeita!",
        type: "warning",
        showCancelButton: true,
    }, function () {
        $.ajax({
            url: '/empresa/setor/delete/' + id,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function () {
                toastr.success('Setor excluído com sucesso');
                carregarTabela();
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                toastr.error('Erro ao excluir setor');
            }
        });
    });
}

$(document).on('click', '.pagination a', function(e){
    e.preventDefault();
    let page = $(this).attr('href').split('page=')[1];
    carregarTabela(page);
});

$(document).ready(function () {
    carregarTabela();
});
</script>

</body>
</html>
