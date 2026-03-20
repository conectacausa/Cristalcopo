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
                            <a href="javascript:void(0)"
                               class="waves-effect waves-light btn mb-5 bg-gradient-primary">
                                <i class="fa fa-edit"></i>
                            </a>

                            <button type="button"
                                    class="waves-effect waves-light btn mb-5 bg-gradient-danger"
                                    disabled>
                                <i class="fa fa-trash-o"></i>
                            </button>
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
