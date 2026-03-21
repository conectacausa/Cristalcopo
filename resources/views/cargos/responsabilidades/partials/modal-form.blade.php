<div class="modal fade" id="modal-registro" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-registro">
                @csrf

                <input type="hidden" id="registro_id" name="registro_id">
                <input type="hidden" id="form-action" value="{{ route('cargos.responsabilidades.store') }}">

                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">Nova Responsabilidade</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Descrição *</label>
                        <input type="text" class="form-control" id="descricao" name="descricao" maxlength="255">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        <span id="submit-text">Salvar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
