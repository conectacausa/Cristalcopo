<div class="modal fade" id="setorModal" tabindex="-1" aria-labelledby="setorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form id="formSetor">
                @csrf

                <input type="hidden" id="setor_id" name="setor_id">

                <div class="modal-header">
                    <h5 class="modal-title" id="setorModalLabel">Novo Setor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>

                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Descrição do Setor <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="descricao"
                                    name="descricao"
                                    placeholder="Digite a descrição do setor"
                                    maxlength="255"
                                    required>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Filial <span class="text-danger">*</span></label>
                                <select id="id_filial" name="id_filial" class="form-control" required>
                                    <option value="">Selecione</option>
                                    @foreach($filiais as $filial)
                                        <option value="{{ $filial->id }}">
                                            {{ $filial->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit" class="btn btn-success" id="btnSalvarSetor">
                        Salvar
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
