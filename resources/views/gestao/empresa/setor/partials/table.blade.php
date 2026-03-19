<table class="table">
    <thead class="bg-primary">
        <tr align="center">
            <th>Setor</th>
            <th>Filial</th>
            <th width="200">Ações</th>
        </tr>
    </thead>
    <tbody>
        @forelse($dados as $item)
            <tr>
                <td>{{ $item->descricao }}</td>
                <td>
                    @forelse($item->filiais as $filial)
                        <div>{{ $filial->nome }}</div>
                    @empty
                        <div>-</div>
                    @endforelse
                </td>
                <td align="center">
                    <div class="clearfix">
                        <button
                            class="waves-effect waves-light btn mb-5 bg-gradient-primary"
                            onclick='editar(@json($item->id), @json($item->descricao), @json($item->filiais->pluck("id")->values()))'>
                            <i class="fa fa-edit"></i>
                        </button>

                        <button
                            class="waves-effect waves-light btn mb-5 bg-gradient-danger"
                            onclick="excluir({{ $item->id }})">
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center">Nenhum setor encontrado.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-3">
    {{ $dados->links() }}
</div>
