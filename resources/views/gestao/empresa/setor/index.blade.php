<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">

<title>Cristalcopo - Setor</title>

<link rel="stylesheet" href="{{ asset('assets/css/vendors_css.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/skin_color.css') }}">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed">

<div class="wrapper">

@include('layouts.includes.header')
@include('layouts.includes.menu')

<div class="content-wrapper">
<div class="container-full">

<div class="content-header">
<div class="d-flex align-items-center">
<div class="me-auto">
<h4 class="page-title">Setor</h4>
</div>

<button onclick="abrirNovo()" class="btn bg-gradient-success w-200">Novo Setor</button>
</div>
</div>

<section class="content">

<div class="box">
<div class="box-body">
<div class="row">

<div class="col-md-7">
<input type="text" id="nome" class="form-control" placeholder="Nome">
</div>

<div class="col-md-5">
<select id="filial_id" class="form-control">
<option value="">Todas Filiais</option>
@foreach($filiais as $f)
<option value="{{ $f->id }}">{{ $f->nome }}</option>
@endforeach
</select>
</div>

</div>
</div>
</div>

<div class="box">
<div class="box-body">
<div id="tabela"></div>
</div>
</div>

</section>

</div>
</div>

@include('layouts.includes.footer')

</div>

<script src="{{ asset('assets/js/vendors.min.js') }}"></script>
<script src="{{ asset('assets/vendor_components/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/js/template.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

@include('gestao.empresa.setor.partials.modal')

<script>
toastr.options = {closeButton:true,progressBar:true};

let timer;

$('#nome, #filial_id').on('keyup change', function(){
    clearTimeout(timer);
    timer = setTimeout(carregarTabela, 300);
});

function carregarTabela(page=1){
    $.get('/empresa/setor/list?page='+page+'&nome='+$('#nome').val()+'&filial_id='+$('#filial_id').val(), function(data){
        $('#tabela').html(data);
    });
}

function abrirNovo(){
    $('#form')[0].reset();
    $('#id').val('');
    $('#modal').modal('show');
}

function editar(id,nome,filiais){
    $('#modal').modal('show');
    $('#id').val(id);
    $('#nome_setor').val(nome);

    $('input[name="filiais[]"]').prop('checked', false);
    filiais.forEach(f => $('#filial_'+f).prop('checked', true));
}

function salvar(){
    let id = $('#id').val();
    let url = id ? '/empresa/setor/update/'+id : '/empresa/setor/store';

    $.post(url,$('#form').serialize())
    .done(()=>{
        toastr.success('Salvo com sucesso');
        $('#modal').modal('hide');
        carregarTabela();
    });
}

function excluir(id){
    swal({
        title:"Excluir?",
        type:"warning",
        showCancelButton:true
    },function(){
        $.ajax({
            url:'/empresa/setor/delete/'+id,
            type:'DELETE',
            data:{_token:'{{ csrf_token() }}'},
            success:function(){
                toastr.success('Excluído com sucesso');
                carregarTabela();
            }
        });
    });
}

$(document).on('click','.pagination a',function(e){
    e.preventDefault();
    let page=$(this).attr('href').split('page=')[1];
    carregarTabela(page);
});

$(document).ready(carregarTabela);
</script>

</body>
</html>
