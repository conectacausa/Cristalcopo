<div class="row">
    <div class="col-12">
        <div class="box">
            <div class="box-header with-border">
                <h4 class="box-title">Filtros</h4>
            </div>
            <div class="box-body">
                <form id="form-filtros-cargos">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Cargo ou CBO</label>
                                <input
                                    type="text"
                                    name="busca"
                                    id="busca"
                                    class="form-control"
                                    placeholder="Digite o cargo, código CBO ou descrição do CBO"
                                >
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Filiais</label>
                                <select name="filiais[]" id="filiais" class="form-control select2" multiple>
                                    @foreach($filiais as $filial)
                                        <option value="{{ $filial->id }}">{{ $filial->nome_fantasia }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
