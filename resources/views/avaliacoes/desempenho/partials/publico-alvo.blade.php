@php
    $publicoSelecionado = old('publico_alvo', $publicoSelecionado ?? []);
@endphp
<div class="box mt-3">
    <div class="box-header with-border d-flex justify-content-between align-items-center">
        <h4 class="box-title mb-0">Público-alvo</h4>
        <button type="button" class="btn btn-info btn-sm" onclick="window.adicionarLinhaPublico(this)">Adicionar item</button>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Escopo principal</label>
                    <select name="publico_tipo" class="form-control select2" style="width:100%;">
                        <option value="todos" @selected(old('publico_tipo', $ciclo->publico_tipo) === 'todos')>Todos os colaboradores ativos</option>
                        <option value="filial" @selected(old('publico_tipo', $ciclo->publico_tipo) === 'filial')>Por filial</option>
                        <option value="setor" @selected(old('publico_tipo', $ciclo->publico_tipo) === 'setor')>Por setor</option>
                        <option value="cargo" @selected(old('publico_tipo', $ciclo->publico_tipo) === 'cargo')>Por cargo</option>
                        <option value="manual" @selected(old('publico_tipo', $ciclo->publico_tipo) === 'manual')>Seleção manual</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="publico-container">
            @forelse($publicoSelecionado as $index => $item)
                <div class="row publico-item align-items-end mb-2">
                    <div class="col-md-5">
                        <label class="form-label">Tipo</label>
                        <select name="publico_alvo[{{ $index }}][tipo]" class="form-control select2" style="width:100%;">
                            <option value="filial" @selected(($item['tipo'] ?? '') === 'filial')>Filial</option>
                            <option value="setor" @selected(($item['tipo'] ?? '') === 'setor')>Setor</option>
                            <option value="cargo" @selected(($item['tipo'] ?? '') === 'cargo')>Cargo</option>
                            <option value="colaborador" @selected(($item['tipo'] ?? '') === 'colaborador')>Colaborador</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Referência</label>
                        <input type="number" min="1" class="form-control" name="publico_alvo[{{ $index }}][referencia_id]" value="{{ $item['referencia_id'] ?? '' }}">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.publico-item').remove()">Remover</button>
                    </div>
                </div>
            @empty
                <div class="row publico-item align-items-end mb-2">
                    <div class="col-md-5">
                        <label class="form-label">Tipo</label>
                        <select name="publico_alvo[0][tipo]" class="form-control select2" style="width:100%;">
                            <option value="filial">Filial</option>
                            <option value="setor">Setor</option>
                            <option value="cargo">Cargo</option>
                            <option value="colaborador">Colaborador</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Referência</label>
                        <input type="number" min="1" class="form-control" name="publico_alvo[0][referencia_id]" value="">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.publico-item').remove()">Remover</button>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
