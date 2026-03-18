<table class="table">
    <thead class="bg-primary">
        <tr align="center">
            <th>Código</th>
            <th>Descrição</th>
            <th width="200">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dados as $item)
        <tr>
            <td>{{ $item->codigo_cbo }}</td>
            <td>{{ $item->descricao_cbo }}</td>
            <td align="center">
                <button onclick="editar('{{ $item->id }}','{{ $item->codigo_cbo }}','{{ $item->descricao_cbo }}')" class="btn bg-gradient-primary">
                    <i class="fa fa-edit"></i>
                </button>
                <button onclick="excluir({{ $item->id }})" class="btn bg-gradient-danger">
                    <i class="fa fa-trash-o"></i>
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-3">
    {{ $dados->links() }}
</div>
