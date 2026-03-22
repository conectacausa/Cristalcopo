@php
    $regras = old('regras', $regras ?? []);
@endphp
<div class="box mt-3">
    <div class="box-header with-border d-flex justify-content-between align-items-center">
        <h4 class="box-title mb-0">Regras de Aplicação</h4>
        <button type="button" class="btn btn-info btn-sm" onclick="window.adicionarLinhaRegra(this)">Adicionar regra</button>
    </div>
    <div class="box-body">
        <p class="text-muted">Precedência: pergunta &gt; subgrupo &gt; grupo &gt; pilar &gt; geral.</p>
        <div class="regras-container">
            @forelse($regras as $index => $regra)
                <div class="row regra-item align-items-end mb-2">
                    <div class="col-md-5">
                        <label class="form-label">Tipo</label>
                        <select name="regras[{{ $index }}][regra_tipo]" class="form-control select2" style="width:100%;">
                            <option value="cargo" @selected(($regra['regra_tipo'] ?? '') === 'cargo')>Cargo</option>
                            <option value="filial" @selected(($regra['regra_tipo'] ?? '') === 'filial')>Filial</option>
                            <option value="setor" @selected(($regra['regra_tipo'] ?? '') === 'setor')>Setor</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Referência</label>
                        <input type="number" min="1" class="form-control" name="regras[{{ $index }}][referencia_id]" value="{{ $regra['referencia_id'] ?? '' }}">
                        <small class="text-muted">Use o ID da filial, setor ou cargo conforme o tipo escolhido.</small>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.regra-item').remove()">Remover</button>
                    </div>
                </div>
            @empty
                <div class="row regra-item align-items-end mb-2">
                    <div class="col-md-5">
                        <label class="form-label">Tipo</label>
                        <select name="regras[0][regra_tipo]" class="form-control select2" style="width:100%;">
                            <option value="cargo">Cargo</option>
                            <option value="filial">Filial</option>
                            <option value="setor">Setor</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Referência</label>
                        <input type="number" min="1" class="form-control" name="regras[0][referencia_id]" value="">
                        <small class="text-muted">Use o ID da filial, setor ou cargo conforme o tipo escolhido.</small>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.regra-item').remove()">Remover</button>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
