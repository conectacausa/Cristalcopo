<div class="modal fade" id="modal-cargo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-cargo">
                @csrf

                <input type="hidden" id="cargo_id" name="cargo_id">
                <input type="hidden" id="cargo-form-action" value="{{ route('cargos.cargos.store') }}">

                <div class="modal-header">
                    <h4 class="modal-title" id="cargo-modal-title">Novo Cargo</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="form-label">Título do Cargo *</label>
                                <input type="text" class="form-control" id="titulo_cargo" name="titulo_cargo" maxlength="255">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Código Importação</label>
                                <input type="text" class="form-control" id="codigo_importacao" name="codigo_importacao" maxlength="100">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">CBO *</label>
                                <select class="form-control select2" id="cargo_cbo_id" name="cargo_cbo_id">
                                    <option value="">Selecione</option>
                                    @foreach($cbos as $cbo)
                                        <option value="{{ $cbo->id }}">
                                            {{ $cbo->codigo_cbo }} - {{ $cbo->descricao_cbo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Filiais *</label>
                                <select class="form-control select2" id="filiais_modal" name="filiais[]" multiple>
                                    @foreach($filiais as $filial)
                                        <option value="{{ $filial->id }}">{{ $filial->nome_fantasia }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Setores *</label>
                                <select class="form-control select2" id="setores_modal" name="setores[]" multiple>
                                    @foreach($setores as $setor)
                                        <option value="{{ $setor->id }}">{{ $setor->descricao }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="checkbox">
                                    <input type="checkbox" id="conta_base_jovem_aprendiz" name="conta_base_jovem_aprendiz" value="1">
                                    <label for="conta_base_jovem_aprendiz">Conta na base do jovem aprendiz</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <p class="resumo-texto">
                                O status de aprovação inicial será definido conforme a configuração central de fluxo para o tipo de referência <strong>cargo</strong>.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <span id="cargo-submit-text">Salvar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
