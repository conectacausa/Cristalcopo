<div class="modal fade" id="modalCbo">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">CBO</h4>
            </div>

            <div class="modal-body">
                <form id="formCbo">
                    @csrf
                    <input type="hidden" id="id" name="id">

                    <div class="form-group">
                        <label>Código</label>
                        <input type="text" name="codigo_cbo" id="codigo_cbo" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Descrição</label>
                        <input type="text" name="descricao_cbo" id="descricao_cbo" class="form-control">
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
