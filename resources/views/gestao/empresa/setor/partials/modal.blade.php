<div class="form-group">
<label>Filiais</label>

@foreach(\App\Models\EmpresaFilial::orderBy('nome_fantasia')->get() as $f)
<div>
<input type="checkbox" id="filial_{{ $f->id }}" name="filiais[]" value="{{ $f->id }}">
<label>{{ $f->nome }}</label>
</div>
@endforeach

</div>
