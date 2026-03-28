<div class="table-responsive">
    <table class="table">
        <thead class="bg-primary">
            <tr align="center">
                <th align="left">Nome do País</th>
                <th>ISO2</th>
                <th>ISO3</th>
                <th width="180">Ações</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($dados) && method_exists($dados, 'count') && $dados->count())
                @foreach($dados as $pais)
                    <tr>
                        <td>{{ $pais->nome }}</td>
                        <td align="center">{{ $pais->iso2 }}</td>
                        <td align="center">{{ $pais->iso3 }}</td>
                        <td align="center">
                            @if(!empty($permissoes['can_edit']))
                                <button type="button"
                                        class="waves-effect waves-light btn mb-5 bg-gradient-primary btn-editar-pais"
                                        data-id="{{ $pais->id }}">
                                    <i class="fa fa-edit"></i>
                                </button>
                            @endif

                            @if(!empty($permissoes['can_delete']))
                                <button type="button"
                                        class="waves-effect waves-light btn mb-5 bg-gradient-danger btn-excluir-pais"
                                        data-url="{{ route('configuracao.pais.delete', $pais->id) }}">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" align="center">Nenhum país encontrado.</td>
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
