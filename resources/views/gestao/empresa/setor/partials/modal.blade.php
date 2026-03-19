<div class="modal fade" id="modalSetor" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Setor</h4>
            </div>

            <div class="modal-body">
                <form id="formSetor">
                    @csrf
                    <input type="hidden" id="setor_id" name="id">

                    <div class="form-group">
                        <label class="form-label">Setor</label>
                        <input type="text" class="form-control" id="descricao" name="descricao">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Filiais</label>

                        @foreach(\App\Models\EmpresaFilial::orderBy('nome_fantasia')->get() as $filial)
                            <div class="form-check mb-5">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="filial_{{ $filial->id }}"
                                    name="filiais[]"
                                    value="{{ $filial->id }}"
                                >
                                <label class="form-check-label" for="filial_{{ $filial->id }}">
                                    {{ $filial->nome }}
                                </label>
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
