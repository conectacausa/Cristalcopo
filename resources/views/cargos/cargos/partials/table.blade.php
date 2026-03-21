@php
    $isPaginator = $dados instanceof \Illuminate\Contracts\Pagination\Paginator
        || $dados instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    $statusLabel = function ($status) {
        return match ($status) {
            'rascunho' => 'Rascunho',
            'pendente_aprovacao' => 'Pendente Aprovação',
            'em_aprovacao' => 'Em Aprovação',
            'aprovado' => 'Aprovado',
            'reprovado' => 'Reprovado',
            'cancelado' => 'Cancelado',
            default => ucfirst(str_replace('_', ' ', $status ?? ''));
        };
    };

    $statusClass = function ($status) {
        return match ($status) {
            'aprovado' => 'badge badge-success',
            'reprovado' => 'badge badge-danger',
            'cancelado' => 'badge badge-dark',
            'pendente_aprovacao', 'em_aprovacao' => 'badge badge-warning',
            default => 'badge badge-secondary',
        };
    };
@endphp

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
            @if($isPaginator && $dados->count())
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
                            <span class="{{ $statusClass($cargo->status_aprovacao) }}">
                                {{ $statusLabel($cargo->status_aprovacao) }}
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

                                @if($permissoes['pode_editar'])
                                    <button type="button"
                                            class="waves-effect waves-light btn mb-5 bg-gradient-primary btn-editar-cargo"
                                            data-id="{{ $cargo->id }}"
                                            title="Editar">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                @endif

                                @if($permissoes['pode_excluir'])
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

@if($isPaginator)
    <div class="mt-15">
        {{ $dados->links() }}
    </div>
@endif
