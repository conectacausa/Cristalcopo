<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Editar Fluxo</title>

    <link rel="stylesheet" href="{{ asset('assets/css/vendors_css.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/skin_color.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .etapa-card {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background: #fdfdfd;
        }
    </style>
</head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed">

<div class="wrapper">

@include('layouts.includes.header')
@include('layouts.includes.menu')

<div class="content-wrapper">
<div class="container-full">

<section class="content">

<form method="POST" action="{{ route('aprovacao.fluxo.update', $fluxo->id) }}">
@csrf

<div class="box">
<div class="box-header">
<h4 class="box-title">Dados do Fluxo</h4>
</div>

<div class="box-body">
<div class="row">

<div class="col-md-6">
<label>Nome</label>
<input type="text" name="nome_fluxo" class="form-control"
value="{{ $fluxo->nome_fluxo }}" required>
</div>

<div class="col-md-6">
<label>Tipo Referência</label>
<input type="text" name="tipo_referencia" class="form-control"
value="{{ $fluxo->tipo_referencia }}" required>
</div>

<div class="col-md-12">
<label>Descrição</label>
<textarea name="descricao" class="form-control">{{ $fluxo->descricao }}</textarea>
</div>

<div class="col-md-3">
<label>Modo</label>
<select name="modo_aprovacao" class="form-control">
<option value="sequencial" {{ $fluxo->modo_aprovacao=='sequencial'?'selected':'' }}>Sequencial</option>
<option value="paralelo" {{ $fluxo->modo_aprovacao=='paralelo'?'selected':'' }}>Paralelo</option>
</select>
</div>

<div class="col-md-3">
<label>Permite Reprovação</label>
<select name="permite_reprovacao" class="form-control">
<option value="1" {{ $fluxo->permite_reprovacao?'selected':'' }}>Sim</option>
<option value="0" {{ !$fluxo->permite_reprovacao?'selected':'' }}>Não</option>
</select>
</div>

<div class="col-md-3">
<label>Permite Retorno</label>
<select name="permite_retorno" class="form-control">
<option value="1" {{ $fluxo->permite_retorno?'selected':'' }}>Sim</option>
<option value="0" {{ !$fluxo->permite_retorno?'selected':'' }}>Não</option>
</select>
</div>

<div class="col-md-3">
<label>Situação</label>
<select name="situacao" class="form-control">
<option value="ativo" {{ $fluxo->situacao=='ativo'?'selected':'' }}>Ativo</option>
<option value="inativo" {{ $fluxo->situacao=='inativo'?'selected':'' }}>Inativo</option>
</select>
</div>

</div>
</div>
</div>

<div class="box">
<div class="box-header d-flex justify-content-between">
<h4>Etapas</h4>
<button type="button" class="btn btn-info btn-sm" id="addEtapa">Adicionar</button>
</div>

<div class="box-body" id="etapasContainer">

@foreach($fluxo->etapas as $i => $etapa)
<div class="etapa-card">

<div class="row">

<div class="col-md-4">
<label>Nome Etapa</label>
<input type="text" name="etapas[{{ $i }}][nome_etapa]"
value="{{ $etapa->nome_etapa }}" class="form-control">
</div>

<div class="col-md-2">
<label>Ordem</label>
<input type="number" name="etapas[{{ $i }}][ordem]"
value="{{ $etapa->ordem }}" class="form-control">
</div>

<div class="col-md-3">
<label>Tipo</label>
<select name="etapas[{{ $i }}][tipo_aprovacao_etapa]" class="form-control">
<option value="unanimidade" {{ $etapa->tipo_aprovacao_etapa=='unanimidade'?'selected':'' }}>Unanimidade</option>
<option value="qualquer_um" {{ $etapa->tipo_aprovacao_etapa=='qualquer_um'?'selected':'' }}>Qualquer</option>
<option value="maioria" {{ $etapa->tipo_aprovacao_etapa=='maioria'?'selected':'' }}>Maioria</option>
</select>
</div>

<div class="col-md-3">
<label>Qtd Mínima</label>
<input type="number"
name="etapas[{{ $i }}][quantidade_minima_aprovacao]"
value="{{ $etapa->quantidade_minima_aprovacao }}"
class="form-control">
</div>

<div class="col-md-12">
<label>Aprovadores</label>

<select name="etapas[{{ $i }}][aprovadores][]"
class="form-control select-aprovadores" multiple>

@foreach($colaboradores as $colaborador)
<option value="{{ $colaborador->id }}"
{{ $etapa->aprovadores->pluck('colaborador_id')->contains($colaborador->id) ? 'selected' : '' }}>
{{ $colaborador->nome_completo }}
</option>
@endforeach

</select>

</div>

</div>

</div>
@endforeach

</div>

<div class="box-footer text-end">
<button class="btn btn-success">Salvar</button>
</div>

</div>

</form>

</section>
</div>
</div>

@include('layouts.includes.footer')

</div>

<script src="{{ asset('assets/js/vendors.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$('.select-aprovadores').select2({ width:'100%' });

$('#addEtapa').click(function(){

let index = $('#etapasContainer .etapa-card').length;

let html = `
<div class="etapa-card">
<div class="row">

<div class="col-md-4">
<input type="text" name="etapas[${index}][nome_etapa]" class="form-control">
</div>

<div class="col-md-2">
<input type="number" name="etapas[${index}][ordem]" class="form-control">
</div>

<div class="col-md-3">
<select name="etapas[${index}][tipo_aprovacao_etapa]" class="form-control">
<option value="unanimidade">Unanimidade</option>
<option value="qualquer_um">Qualquer</option>
<option value="maioria">Maioria</option>
</select>
</div>

<div class="col-md-3">
<input type="number" name="etapas[${index}][quantidade_minima_aprovacao]" class="form-control">
</div>

<div class="col-md-12">
<select name="etapas[${index}][aprovadores][]"
class="form-control select-aprovadores" multiple>
{!! collect($colaboradores)->map(fn($c)=>"<option value='{$c->id}'>{$c->nome_completo}</option>")->implode('') !!}
</select>
</div>

</div>
</div>
`;

$('#etapasContainer').append(html);

$('.select-aprovadores').select2({ width:'100%' });

});
</script>

</body>
</html>
