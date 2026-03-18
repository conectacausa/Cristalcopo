<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Cristalcopo - CBO</title>

  <link rel="stylesheet" href="{{ asset('assets/css/vendors_css.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/skin_color.css') }}">
</head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed">

<div class="wrapper">

  {{-- HEADER --}}
  @include('layouts.includes.header')

  {{-- MENU --}}
  @include('layouts.includes.menu')

  <div class="content-wrapper">
    <div class="container-full">

      <div class="content-header">
        <div class="d-flex align-items-center">
          <div class="me-auto">
            <h4 class="page-title">CBO</h4>
          </div>

          <button onclick="abrirNovo()" class="btn bg-gradient-success w-200">
            Novo CBO
          </button>
        </div>
      </div>

      <section class="content">

        {{-- FILTRO --}}
        <div class="box">
          <div class="box-body">
            <input type="text" id="filtro" class="form-control" placeholder="Descrição ou Código">
          </div>
        </div>

        {{-- TABELA --}}
        <div class="box">
          <div class="box-body">
            <div id="tabela"></div>
          </div>
        </div>

      </section>

    </div>
  </div>

  {{-- FOOTER --}}
  @include('layouts.includes.footer')

</div>

<script src="{{ asset('assets/js/vendors.min.js') }}"></script>
<script src="{{ asset('assets/vendor_components/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/js/template.js') }}"></script>

@include('gestao.cargos.cbo.partials.modal')

<script>
let filtroTimer;

$('#filtro').on('keyup', function () {
    clearTimeout(filtroTimer);
    filtroTimer = setTimeout(() => {
        carregarTabela();
    }, 400);
});

function carregarTabela(page = 1) {
    $.get('/cargos/cbo/list?page=' + page + '&filtro=' + $('#filtro').val(), function (data) {
        $('#tabela').html(data);
    });
}

function abrirNovo() {
    $('#modalCbo').modal('show');
    $('#formCbo')[0].reset();
    $('#id').val('');
}

function editar(id, codigo, descricao) {
    $('#modalCbo').modal('show');
    $('#id').val(id);
    $('#codigo_cbo').val(codigo);
    $('#descricao_cbo').val(descricao);
}

function salvar() {
    let id = $('#id').val();
    let url = id ? '/cargos/cbo/update/' + id : '/cargos/cbo/store';

    $.post(url, $('#formCbo').serialize(), function () {
        $('#modalCbo').modal('hide');
        toastr.success('Salvo com sucesso');
        carregarTabela();
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
            url: '/cargos/cbo/delete/' + id,
            type: 'DELETE',
            data: {_token: '{{ csrf_token() }}'},
            success: function () {
                toastr.success('Excluído com sucesso');
                carregarTabela();
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
