<div class="modal fade" id="modal">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h4>Setor</h4>
</div>

<div class="modal-body">

<form id="form">
@csrf
<input type="hidden" id="id" name="id">

<div class="form-group">
<label>Nome</label>
<input type="text" name="nome" id="nome_setor" class="form-control">
</div>

<div class="form-group">
<label>Filiais</label>

@foreach(\App\Models\EmpresaFilial::all() as $f)
<div>
<input type="checkbox" id="filial_{{ $f->id }}" name="filiais[]" value="{{ $f->id }}">
<label>{{ $f->nome }}</label>
</div>
@endforeach

</div>

</form>

</div>

<div class="modal-footer">
<button onclick="salvar()" class="btn btn-success">Salvar</button>
<button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
</div>

</div>
</div>
</div>
