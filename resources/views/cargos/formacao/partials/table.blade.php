<div class="table-responsive">
    <table class="table">
        <thead class="bg-primary">
            <tr>
                <th>Descrição</th>
                <th width="180" class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($dados) && method_exists($dados, 'count') && $dados->count())
                @foreach($dados as $item)
                    <tr>
                        <td>{{ $item->descricao }}</td>
                        <td class="text-center">
                            @if(!empty($permissoes['pode_editar']))
                                <button type="button" class="waves-effect waves-light btn mb-5 bg-gradient-primary btn-editar" data-id="{{ $item->id }}">
                                    <i class="fa fa-edit"></i>
                                </button>
                            @endif
                            @if(!empty($permissoes['pode_excluir']))
                                <button type="button" class="waves-effect waves-light btn mb-5 bg-gradient-danger btn-excluir" data-url="{{ route('cargos.formacao.delete', $item->id) }}">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr><td colspan="2" class="text-center">Nenhum registro encontrado.</td></tr>
            @endif
        </tbody>
    </table>
</div>

@if(isset($dados) && method_exists($dados, 'links'))
    <div class="mt-15">{{ $dados->links() }}</div>
@endif
