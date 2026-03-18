<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}">

    <title>Cristalcopo - Setor</title>

    <!-- Vendors Style-->
    <link rel="stylesheet" href="{{ asset('assets/css/vendors_css.css') }}">

    <!-- Style-->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/skin_color.css') }}">

    <!-- Toastr CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
<body class="hold-transition light-skin sidebar-mini theme-primary fixed">

<div class="wrapper">
    <div id="loader"></div>

    @include('layouts.includes.header')

    @include('layouts.includes.menu')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
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
                    <button type="button" class="waves-effect waves-light btn mb-5 bg-gradient-success w-200" onclick="abrirNovo()">
                        Novo Setor
                    </button>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <!-- Filtros -->
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
                                            <input type="text" id="filtro_nome" class="form-control" placeholder="Nome do setor">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label">Filial</label>
                                            <select id="filtro_filial" class="form-select">
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

                <!-- Tabela -->
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h4 class="box-title">Setor</h4>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive" id="setores-table-container">
                                    @include('gestao.empresa.setor.partials.table', ['dados' => collect()])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
    </div>
    <!-- /.content-wrapper -->

    @include('layouts.includes.footer')
</div>
<!-- ./wrapper -->

@include('gestao.empresa.setor.partials.modal')

<!-- Vendor JS -->
<script src="{{ asset('assets/js/vendors.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/chat-popup.js') }}"></script>
<script src="{{ asset('assets/icons/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('assets/vendor_components/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/vendor_components/sweetalert/jquery.sweet-alert.custom.js') }}"></script>
<script src="{{ asset('assets/js/template.js') }}"></script>

<!-- Toastr CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: "3000"
    };

    let filtroTimer = null;

    function carregarTabela(page = 1) {
        $('#setores-table-container').html('<div class="text-center p-20">Carregando...</div>');

        $.ajax({
            url: "{{ url('/empresa/setor/list') }}",
            type: "GET",
            data: {
                page: page,
                nome: $('#filtro_nome').val(),
                filial_id: $('#filtro_filial').val()
            },
            success: function(response) {
                $('#setores-table-container').html(response);
            },
            error: function() {
                toastr.error('Erro ao carregar setores.');
            }
        });
    }

    $('#filtro_nome').on('keyup', function () {
        clearTimeout(filtroTimer);
        filtroTimer = setTimeout(function () {
            carregarTabela(1);
        }, 300);
    });

    $('#filtro_filial').on('change', function () {
        carregarTabela(1);
    });

    function abrirNovo() {
        $('#setor_id').val('');
        $('#nome').val('');
        $('input[name="filiais[]"]').prop('checked', false);
        $('#modalSetorLabel').text('Novo Setor');
        $('#modalSetor').modal('show');
    }

    function editar(id, nome, filiais) {
        $('#setor_id').val(id);
        $('#nome').val(nome);
        $('input[name="filiais[]"]').prop('checked', false);

        if (Array.isArray(filiais)) {
            filiais.forEach(function(filialId) {
                $('#filial_' + filialId).prop('checked', true);
            });
        }

        $('#modalSetorLabel').text('Editar Setor');
        $('#modalSetor').modal('show');
    }

    function salvar() {
        let id = $('#setor_id').val();
        let url = id
            ? "{{ url('/empresa/setor/update') }}/" + id
            : "{{ url('/empresa/setor/store') }}";

        $.ajax({
            url: url,
            type: "POST",
            data: $('#formSetor').serialize(),
            success: function() {
                $('#modalSetor').modal('hide');
                toastr.success('Setor salvo com sucesso.');
                carregarTabela();
            },
            error: function() {
                toastr.error('Erro ao salvar setor.');
            }
        });
    }

    function excluir(id) {
        swal({
            title: "Tem certeza?",
            text: "Deseja realmente excluir este setor?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#2962FF",
            confirmButtonText: "Sim, excluir",
            cancelButtonText: "Cancelar",
            closeOnConfirm: true
        }, function(confirmado) {
            if (confirmado) {
                $.ajax({
                    url: "{{ url('/empresa/setor/delete') }}/" + id,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function() {
                        toastr.success('Setor excluído com sucesso.');
                        carregarTabela();
                    },
                    error: function() {
                        toastr.error('Erro ao excluir setor.');
                    }
                });
            }
        });
    }

    $(document).on('click', '#setores-table-container .pagination a', function(e) {
        e.preventDefault();
        let href = $(this).attr('href');
        let page = new URL(href).searchParams.get('page') || 1;
        carregarTabela(page);
    });

    $(document).ready(function () {
        carregarTabela();
    });
</script>

</body>
</html>
