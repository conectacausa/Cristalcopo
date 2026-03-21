<div class="table-responsive">
    <table class="table">
        <thead class="bg-primary">
            <tr align="center">
                <th align="left">Descrição</th>
                <th align="left">Grupo</th>
                <th>Situação</th>
                <th width="180">Ações</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($dados) && method_exists($dados, 'count') && $dados->count())
                @foreach($dados as $risco)
                    <tr>
                        <td>{{ $risco->descricao }}</td>
                        <td>{{ $risco->grupo_risco ?: '-' }}</td>
                        <td align="center">
                            @if($risco->ativo)
                                <span class="badge badge-success">Ativo</span>
                            @else
                                <span class="badge badge-danger">Inativo</span>
                            @endif
                        </td>
                        <td align="center">
                            @if(!empty($permissoes['pode_editar']))
                                <button type="button"
                                        class="waves-effect waves-light btn mb-5 bg-gradient-primary btn-editar-risco"
                                        data-id="{{ $risco->id }}">
                                    <i class="fa fa-edit"></i>
                                </button>
                            @endif

                            @if(!empty($permissoes['pode_excluir']))
                                <button type="button"
                                        class="waves-effect waves-light btn mb-5 bg-gradient-danger btn-excluir-risco"
                                        data-url="{{ route('sst.riscos.delete', $risco->id) }}">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" align="center">Nenhum risco ocupacional encontrado.</td>
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
