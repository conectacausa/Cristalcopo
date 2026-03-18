<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Cristalcopo - CBO</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/vendors_css.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/skin_color.css') }}">

    <!-- TOASTR CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed">

<div class="wrapper">

    <div id="loader"></div>

    {{-- HEADER --}}
    @include('layouts.includes.header')

    {{-- MENU --}}
    @include('layouts.includes.menu')

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="container-full">

            <!-- Header -->
            <div class="content-header">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <h4 class="page-title">CBO</h4>

                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="#"><i class="mdi mdi-home-outline"></i></a>
                                    </li>
                                    <li class="breadcrumb-item">Cargos</li>
                                    <li class="breadcrumb-item active">CBO</li>
                                </ol>
                            </nav>
                        </div>
                    </div>

                    <button type="button" onclick="abrirNovo()" class="waves-effect waves-light btn mb-5 bg-gradient-success w-200">
                        Novo CBO
                    </button>
                </div>
            </div>

            <!-- Content -->
            <section class="content">

                <!-- Filtros -->
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h4 class="box-title">Filtros</h4>
                            </div>

                            <div class="box-body">
                                <div class="form-group">
                                    <label class="form-label">Descrição ou Código</label>
                                    <input type="text" id="filtro" class="form-control" placeholder="Descrição ou Código">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabela -->
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h4 class="box-title">CBO</h4>
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

    {{-- FOOTER --}}
    @include('layouts.includes.footer')

</div>

<!-- JS -->
<script src="{{ asset('assets/js/vendors.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/chat-popup.js') }}"></script>
<script src="{{ asset('assets/icons/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('assets/vendor_components/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/js/template.js') }}"></script>

<!-- TOASTR CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

@include('gestao.cargos.cbo.partials.modal')

<script>
/* ================================
   TOASTR CONFIG
================================ */
toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: "toast-top-right",
    timeOut: "3000"
};

/* ================================
   FILTRO COM DELAY
================================ */
let filtroTimer;

$('#filtro').on('keyup', function () {
    clearTimeout(filtroTimer);
    filtroTimer = setTimeout(() => {
        carregarTabela();
    }, 400);
});

/* ================================
   CARREGAR TABELA
================================ */
function carregarTabela(page = 1) {
    $('#tabela').html('<div class="text-center p-20">Carregando...</div>');

    $.get('/cargos/cbo/list?page=' + page + '&filtro=' + $('#filtro').val(), function (data) {
        $('#tabela').html(data);
    });
}

/* ================================
   NOVO
================================ */
function abrirNovo() {
    $('#modalCbo').modal('show');
    $('#formCbo')[0].reset();
    $('#id').val('');
}

/* ================================
   EDITAR
================================ */
function editar(id, codigo, descricao) {
    $('#modalCbo').modal('show');
    $('#id').val(id);
    $('#codigo_cbo').val(codigo);
    $('#descricao_cbo').val(descricao);
}

/* ================================
   SALVAR (CREATE + UPDATE)
================================ */
function salvar() {
    let id = $('#id').val();
    let url = id ? '/cargos/cbo/update/' + id : '/cargos/cbo/store';

    $.post(url, $('#formCbo').serialize())
    .done(function () {
        $('#modalCbo').modal('hide');

        toastr.success('CBO salvo com sucesso');

        carregarTabela();
    })
    .fail(function () {
        toastr.error('Erro ao salvar CBO');
    });
}

/* ================================
   EXCLUIR
================================ */
function excluir(id) {
    swal({
        title: "Confirmar exclusão?",
        text: "Essa ação não poderá ser desfeita!",
        type: "warning",
        showCancelButton: true,
    }, function () {

        $.ajax({
            url: '/cargos/cbo/delete/' + id,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function () {
                toastr.success('CBO excluído com sucesso');
                carregarTabela();
            },
            error: function () {
                toastr.error('Erro ao excluir CBO');
            }
        });

    });
}

/* ================================
   PAGINAÇÃO AJAX
================================ */
$(document).on('click', '.pagination a', function(e){
    e.preventDefault();
    let page = $(this).attr('href').split('page=')[1];
    carregarTabela(page);
});

/* ================================
   INIT
================================ */
$(document).ready(function () {
    carregarTabela();
});
</script>

</body>
</html>
