<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}">

    <title>Cristalcopo - Fluxo de Aprovação</title>

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
                        <h4 class="page-title">Fluxo de Aprovação</h4>
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ url('/dashboard') }}">
                                            <i class="mdi mdi-home-outline"></i>
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item">Configuração</li>
                                    <li class="breadcrumb-item active" aria-current="page">Aprovações</li>
                                </ol>
                            </nav>
                        </div>
                    </div>

                    <a href="{{ route('aprovacao.fluxo.create') }}"
                       class="waves-effect waves-light btn mb-5 bg-gradient-success w-200">
                        Novo Fluxo
                    </a>
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
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Descrição</label>
                                            <input
                                                type="text"
                                                id="filtro-descricao"
                                                class="form-control"
                                                placeholder="Descrição"
                                                value="{{ request('descricao') }}"
                                            >
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
                                <h4 class="box-title">Fluxo de Aprovação</h4>
                            </div>
                            <div class="box-body">
                                <div id="fluxos-table-wrapper">
                                    @include('aprovacao.fluxo.partials.table', ['fluxos' => $fluxos])
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
        timeOut: "4000"
    };
</script>

@if(session('success'))
    <script>
        toastr.success(@json(session('success')));
    </script>
@endif

@if(session('error'))
    <script>
        toastr.error(@json(session('error')));
    </script>
@endif

@if(session('warning'))
    <script>
        toastr.warning(@json(session('warning')));
    </script>
@endif

@if(session('info'))
    <script>
        toastr.info(@json(session('info')));
    </script>
@endif

<script>
    let filtroTimeout = null;

    function carregarFluxos() {
        const descricao = document.getElementById('filtro-descricao').value;

        $.ajax({
            url: "{{ route('aprovacao.fluxo.index') }}",
            type: "GET",
            data: { descricao: descricao },
            success: function(response) {
                $('#fluxos-table-wrapper').html(response.html);
            },
            error: function() {
                toastr.error('Erro ao carregar os fluxos.');
            }
        });
    }

    $('#filtro-descricao').on('keyup', function() {
        clearTimeout(filtroTimeout);

        filtroTimeout = setTimeout(function() {
            carregarFluxos();
        }, 300);
    });
</script>
<script>
    $(document).on('click', '.btn-delete', function () {
        let form = $(this).closest('.form-delete');
        let nome = form.data('nome');

        swal({
            title: "Confirmar exclusão",
            text: "Deseja realmente excluir o fluxo: " + nome + "?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Sim, excluir",
            cancelButtonText: "Cancelar",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                form.submit();
            }
        });
    });
</script>
</body>
</html>
