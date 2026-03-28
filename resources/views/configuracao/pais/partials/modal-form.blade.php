<div class="modal fade" id="modal-pais" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="form-pais">
            @csrf
            <input type="hidden" id="pais_id" name="pais_id">
            <input type="hidden" id="pais-form-action" value="{{ route('configuracao.pais.store') }}">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pais-modal-title">Adicionar novo país</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="nome">Descrição / Nome do País <span class="text-danger">*</span></label>
                                <input type="text" id="nome" name="nome" class="form-control" maxlength="150" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="iso2">ISO2 <span class="text-danger">*</span></label>
                                <input type="text" id="iso2" name="iso2" class="form-control text-uppercase" maxlength="2" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="iso3">ISO3 <span class="text-danger">*</span></label>
                                <input type="text" id="iso3" name="iso3" class="form-control text-uppercase" maxlength="3" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success" id="pais-submit-text">Salvar</button>
                </div>
            </div>
        </form>
    </div>
</div>
