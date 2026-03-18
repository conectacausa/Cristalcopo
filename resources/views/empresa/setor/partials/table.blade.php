<table class="table">
<thead class="bg-primary">
<tr align="center">
<th>Setor</th>
<th>Filial</th>
<th width="200">Ações</th>
</tr>
</thead>

<tbody>
@foreach($dados as $item)
<tr>
<td>{{ $item->nome }}</td>

<td>
@foreach($item->filiais as $f)
<div>{{ $f->nome }}</div>
@endforeach
</td>

<td align="center">
<button onclick="editar('{{ $item->id }}','{{ $item->nome }}',@json($item->filiais->pluck('id')))" class="btn bg-gradient-primary">
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

{{ $dados->links() }}
