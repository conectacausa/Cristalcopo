<div class="table-responsive">
    <table class="table">
        <thead class="bg-primary">
            <tr align="center">
                <th>Colaborador</th>
                <th>Cargo</th>
                <th>Setor</th>
                <th>Filial</th>
                <th>Admissão</th>
                <th>Desligamento</th>
                <th>Nascimento</th>
                <th width="180">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dados as $colaborador)
                <tr>
                    {{-- Colaborador --}}
                    <td>
                        <div class="fw-600">
                            {{ $colaborador->nome_completo }}
                        </div>
                        <div class="text-muted">
                            CPF: {{ \Illuminate\Support\Str::mask($colaborador->cpf, '***.***.***-**', 0) }}
                        </div>
                    </td>

                    {{-- Cargo --}}
                    <td>
                        <div class="fw-500">
                            {{ $colaborador->cargo_nome ?? '-' }}
                        </div>
                    </td>

                    {{-- Setor --}}
                    <td>
                        <div class="fw-500">
                            {{ $colaborador->setor_nome ?? '-' }}
                        </div>
                    </td>

                    {{-- Filial --}}
                    <td>
                        <div class="fw-500">
                            {{ $colaborador->filial_nome ?? '-' }}
                        </div>
                    </td>

                    {{-- Admissão --}}
                    <td align="center">
                        <div class="fw-500">
                            {{ $colaborador->data_admissao 
                                ? \Carbon\Carbon::parse($colaborador->data_admissao)->format('d/m/Y') 
                                : '-' 
                            }}
                        </div>
                    </td>

                    {{-- Desligamento --}}
                    <td align="center">
                        <div class="fw-500">
                            {{ $colaborador->data_desligamento 
                                ? \Carbon\Carbon::parse($colaborador->data_desligamento)->format('d/m/Y') 
                                : '-' 
                            }}
                        </div>
                    </td>

                    {{-- Nascimento --}}
                    <td align="center">
                        <div class="fw-500">
                            {{ $colaborador->data_nascimento 
                                ? \Carbon\Carbon::parse($colaborador->data_nascimento)->format('d/m/Y') 
                                : '-' 
                            }}
                        </div>
                    </td>

                    {{-- Ações --}}
                    <td align="center">
                        <div class="d-flex justify-content-center gap-1">

                            @if($permissoes['pode_editar'])
                                <a href="{{ route('colaboradores.edit', $colaborador->id) }}"
                                   class="waves-effect waves-light btn btn-sm bg-gradient-primary">
                                    <i class="fa fa-edit"></i>
                                </a>
                            @endif

                            @if($permissoes['pode_excluir'])
                                <button 
                                    class="waves-effect waves-light btn btn-sm bg-gradient-danger btn-delete"
                                    data-id="{{ $colaborador->id }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            @endif

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" align="center">
                        Nenhum registro encontrado.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Paginação --}}
<div class="d-flex justify-content-end mt-3">
    {{ $dados->links() }}
</div>

{{-- Script Exclusão --}}
<script>
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;

            swal({
                title: "Tem certeza?",
                text: "Esta ação não poderá ser desfeita!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sim, excluir!",
                cancelButtonText: "Cancelar"
            }, function (isConfirm) {
                if (isConfirm) {
                    fetch(`/colaboradores/delete/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(res => {
                        if (res.success) {
                            toastr.success(res.message);
                            location.reload();
                        } else {
                            toastr.error(res.message);
                        }
                    })
                    .catch(() => {
                        toastr.error('Erro ao excluir colaborador.');
                    });
                }
            });
        });
    });
</script>
