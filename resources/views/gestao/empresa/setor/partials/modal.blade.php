<div class="modal fade" id="modalSetor">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Setor</h4>
            </div>

            <div class="modal-body">
                <form id="formSetor">
                    @csrf
                    <input type="hidden" id="id" name="id">

                    <div class="form-group">
                        <label>Setor</label>
                        <input type="text" name="descricao" id="descricao_setor" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Filiais</label>

                        @foreach(\App\Models\EmpresaFilial::orderBy('nome_fantasia')->get() as $filial)
                            <div>
                                <input type="checkbox" id="filial_{{ $filial->id }}" name="filiais[]" value="{{ $filial->id }}">
                                <label for="filial_{{ $filial->id }}">{{ $filial->nome }}</label>
                            </div>
                        @endforeach
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="salvar()" class="btn btn-success">Salvar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>
