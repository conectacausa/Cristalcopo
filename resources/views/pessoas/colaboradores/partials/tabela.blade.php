<div class="table-responsive">
    <table class="table">
        <thead class="bg-primary">
            <tr align="center">
                <th width="90">Foto</th>
                <th>Colaborador</th>
                <th>Lotação</th>
                <th>Datas</th>
                <th width="180">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($colaboradores as $colaborador)
                <tr>
                    <td class="text-center align-middle">
                        <img
                            src="{{ $colaborador->foto ? asset($colaborador->foto) : asset('assets/images/avatar/avatar-1.png') }}"
                            alt="{{ $colaborador->nome_completo }}"
                            style="width: 56px; height: 56px; object-fit: cover; border-radius: 8px;"
                        >
                    </td>

                    <td class="align-middle">
                        <strong>{{ $colaborador->nome_completo }}</strong><br>
                        <small class="text-muted">Matrícula: {{ $colaborador->matricula ?? '-' }}</small>
                    </td>

                    <td class="align-middle">
                        <strong>{{ optional($colaborador->cargo)->titulo_cargo ?? '-' }}</strong><br>
                        <small class="text-muted">{{ optional($colaborador->setor)->descricao ?? '-' }}</small><br>
                        <small class="text-muted">{{ optional($colaborador->filial)->nome_fantasia ?? '-' }}</small>
                    </td>

                    <td class="align-middle">
                        <strong>Admissão: {{ optional($colaborador->admissao)->format('d/m/Y') ?? '-' }}</strong><br>
                        <small class="text-muted">Desligamento: {{ optional($colaborador->desligamento)->format('d/m/Y') ?? '-' }}</small><br>
                        <small class="text-muted">Nascimento: {{ optional($colaborador->data_nascimento)->format('d/m/Y') ?? '-' }}</small>
                    </td>

                    <td class="text-center align-middle">
                        <div class="clearfix">
                            @if($permissao->pode_editar)
                                <a href="#" class="waves-effect waves-light btn mb-5 bg-gradient-primary">
                                    <i class="fa fa-edit"></i>
                                </a>
                            @endif

                            @if($permissao->pode_excluir)
                                <form action="{{ route('pessoas.colaboradores.destroy', $colaborador->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="waves-effect waves-light btn mb-5 bg-gradient-danger btn-excluir-colaborador">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Nenhum colaborador encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $colaboradores->links() }}
</div>
