<div class="row">
    <div class="col-12">
        <div class="box">
            <div class="box-header with-border">
                <h4 class="box-title">Filtros</h4>
            </div>
            <div class="box-body">
                <form id="form-filtros" action="{{ route('pessoas.colaboradores.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label class="form-label">Nome, Matrícula ou CPF</label>
                                <input
                                    type="text"
                                    name="texto"
                                    class="form-control"
                                    placeholder="Digite nome, matrícula ou CPF"
                                    value="{{ $filtros['texto'] ?? '' }}"
                                >
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Situação</label>
                                <select name="situacao" class="form-control">
                                    <option value="ativo" @selected(($filtros['situacao'] ?? 'ativo') === 'ativo')>Ativo</option>
                                    <option value="inativo" @selected(($filtros['situacao'] ?? '') === 'inativo')>Inativo</option>
                                    <option value="todos" @selected(($filtros['situacao'] ?? '') === 'todos')>Todos</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Filiais</label>
                                <select name="filiais[]" class="form-control select2" multiple data-placeholder="Selecione as filiais">
                                    @foreach($filiaisLista as $filial)
                                        <option value="{{ $filial->id }}" @selected(in_array($filial->id, $filtros['filiais'] ?? []))>
                                            {{ $filial->nome_fantasia }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Setores</label>
                                <select name="setores[]" class="form-control select2" multiple data-placeholder="Selecione os setores">
                                    @foreach($setoresLista as $setor)
                                        <option value="{{ $setor->id }}" @selected(in_array($setor->id, $filtros['setores'] ?? []))>
                                            {{ $setor->descricao }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Cargos</label>
                                <select name="cargos[]" class="form-control select2" multiple data-placeholder="Selecione os cargos">
                                    @foreach($cargosLista as $cargo)
                                        <option value="{{ $cargo->id }}" @selected(in_array($cargo->id, $filtros['cargos'] ?? []))>
                                            {{ $cargo->titulo_cargo }}
                                        </option>
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
