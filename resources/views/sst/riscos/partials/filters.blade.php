<div class="row">
    <div class="col-12">
        <div class="box">
            <div class="box-header with-border">
                <h4 class="box-title">Filtros</h4>
            </div>
            <div class="box-body">
                <form id="form-filtros-riscos">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="form-label">Descrição ou Grupo</label>
                                <input type="text"
                                       name="busca"
                                       id="busca"
                                       class="form-control"
                                       placeholder="Digite a descrição do risco ou o grupo">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Situação</label>
                                <select name="ativo" id="ativo_filtro" class="form-control">
                                    <option value="">Todos</option>
                                    <option value="1">Ativo</option>
                                    <option value="0">Inativo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
