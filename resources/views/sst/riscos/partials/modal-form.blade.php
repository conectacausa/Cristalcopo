<div class="modal fade" id="modal-risco" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-risco">
                @csrf

                <input type="hidden" id="risco_id" name="risco_id">
                <input type="hidden" id="risco-form-action" value="{{ route('sst.riscos.store') }}">

                <div class="modal-header">
                    <h4 class="modal-title" id="risco-modal-title">Novo Risco Ocupacional</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Descrição *</label>
                        <input type="text" class="form-control" id="descricao" name="descricao" maxlength="150">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Grupo</label>
                        <select class="form-control" id="grupo_risco" name="grupo_risco">
                            <option value="">Selecione</option>
                            <option value="Físico">Físico</option>
                            <option value="Químico">Químico</option>
                            <option value="Biológico">Biológico</option>
                            <option value="Ergonômico">Ergonômico</option>
                            <option value="Acidente">Acidente</option>
                            <option value="Outros">Outros</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <input type="checkbox" id="ativo" name="ativo" value="1" checked>
                            <label for="ativo">Risco ativo</label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <span id="risco-submit-text">Salvar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
