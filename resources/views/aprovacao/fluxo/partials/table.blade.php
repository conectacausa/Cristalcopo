<div class="table-responsive">
    <table class="table">
        <thead class="bg-primary">
            <tr align="center">
                <th>Nome Fluxo</th>
                <th>Referência</th>
                <th>Modo</th>
                <th width="200">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($fluxos as $fluxo)
                <tr>
                    <td>{{ $fluxo->nome_fluxo }}</td>
                    <td>{{ ucfirst($fluxo->tipo_referencia) }}</td>
                    <td>{{ ucfirst($fluxo->modo_aprovacao) }}</td>
                    <td align="center">
                        <div class="clearfix">
                            <a href="{{ route('aprovacao.fluxo.edit', $fluxo->id) }}"
                               class="waves-effect waves-light btn mb-5 bg-gradient-primary">
                                <i class="fa fa-edit"></i>
                            </a>

                            <form action="{{ route('aprovacao.fluxo.delete', $fluxo->id) }}"
                                  method="POST"
                                  style="display:inline-block;"
                                  onsubmit="return confirm('Deseja realmente excluir este fluxo de aprovação?');">
                                @csrf
                                <button type="submit"
                                        class="waves-effect waves-light btn mb-5 bg-gradient-danger">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">
                        Nenhum fluxo de aprovação cadastrado.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
