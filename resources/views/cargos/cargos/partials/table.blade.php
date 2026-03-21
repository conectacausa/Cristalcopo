<div class="table-responsive">
    <table class="table">
        <thead class="bg-primary">
            <tr align="center">
                <th align="left">Cargo</th>
                <th align="left">Lotação / Setor</th>
                <th>Situação</th>
                <th width="220">Ações</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($dados) && method_exists($dados, 'count') && $dados->count())
                @foreach($dados as $cargo)
                    <tr>
                        <td>
                            <strong>{{ $cargo->titulo_cargo }}</strong>
                            <span class="cargo-cbo">
                                {{ $cargo->codigo_cbo ?? '-' }}
                                @if(!empty($cargo->descricao_cbo))
                                    - {{ $cargo->descricao_cbo }}
                                @endif
                            </span>
                        </td>

                        <td>
                            <div>
                                <strong>Filiais:</strong>
                                {{ collect($cargo->filiais_lista ?? [])->pluck('nome')->implode(', ') ?: '-' }}
                            </div>
                            <div class="mt-5 text-muted">
                                <strong>Setores:</strong>
                                {{ collect($cargo->setores_lista ?? [])->pluck('nome')->implode(', ') ?: '-' }}
                            </div>
                        </td>

                        <td align="center">
                            @php
                                $status = $cargo->status_aprovacao ?? 'rascunho';
                                $statusTexto = 'Rascunho';
                                $statusClasse = 'badge badge-secondary';

                                if ($status === 'pendente_aprovacao') {
                                    $statusTexto = 'Pendente Aprovação';
                                    $statusClasse = 'badge badge-warning';
                                } elseif ($status === 'em_aprovacao') {
                                    $statusTexto = 'Em Aprovação';
                                    $statusClasse = 'badge badge-warning';
                                } elseif ($status === 'aprovado') {
                                    $statusTexto = 'Aprovado';
                                    $statusClasse = 'badge badge-success';
                                } elseif ($status === 'reprovado') {
                                    $statusTexto = 'Reprovado';
                                    $statusClasse = 'badge badge-danger';
                                } elseif ($status === 'cancelado') {
                                    $statusTexto = 'Cancelado';
                                    $statusClasse = 'badge badge-dark';
                                }
                            @endphp

                            <span class="{{ $statusClasse }}">
                                {{ $statusTexto }}
                            </span>
                        </td>

                        <td align="center">
                            <div class="clearfix">
                                <button type="button"
                                        class="waves-effect waves-light btn mb-5 bg-gradient-info btn-visualizar-cargo"
                                        data-id="{{ $cargo->id }}"
                                        title="Visualizar">
                                    <i class="fa fa-download"></i>
                                </button>

                                @if(!empty($permissoes['pode_editar']))
                                    <a href="{{ route('cargos.cargos.edit', $cargo->id) }}"
                                       class="waves-effect waves-light btn mb-5 bg-gradient-primary"
                                       title="Editar">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                @endif

                                @if(!empty($permissoes['pode_excluir']))
                                    <button type="button"
                                            class="waves-effect waves-light btn mb-5 bg-gradient-danger btn-excluir-cargo"
                                            data-url="{{ route('cargos.cargos.delete', $cargo->id) }}"
                                            title="Excluir">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" align="center">Nenhum cargo encontrado.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

@if(isset($dados) && method_exists($dados, 'links'))
    <div class="mt-15">
        {{ $dados->links() }}
    </div>
@endif
