@php
    $dados = $dados ?? collect();
    $permissoes = $permissoes ?? [
        'pode_editar' => false,
        'pode_excluir' => false,
    ];
@endphp

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
                <th width="120">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dados as $colaborador)
                <tr>
                    <td>
                        <div class="fw-600">{{ $colaborador->nome_completo ?? '-' }}</div>
                        <div class="text-muted">
                            @if(!empty($colaborador->matricula))
                                Matrícula: {{ $colaborador->matricula }}<br>
                            @endif
                            CPF:
                            @php
                                $cpf = preg_replace('/\D/', '', $colaborador->cpf ?? '');
                            @endphp
                            @if(strlen($cpf) === 11)
                                {{ substr($cpf, 0, 3) }}.{{ substr($cpf, 3, 3) }}.{{ substr($cpf, 6, 3) }}-{{ substr($cpf, 9, 2) }}
                            @else
                                {{ $colaborador->cpf ?? '-' }}
                            @endif
                        </div>
                    </td>
                    <td>{{ $colaborador->cargo_nome ?? '-' }}</td>
                    <td>{{ $colaborador->setor_nome ?? '-' }}</td>
                    <td>{{ $colaborador->filial_nome ?? '-' }}</td>
                    <td align="center">{{ !empty($colaborador->data_admissao) ? \Carbon\Carbon::parse($colaborador->data_admissao)->format('d/m/Y') : '-' }}</td>
                    <td align="center">{{ !empty($colaborador->data_desligamento) ? \Carbon\Carbon::parse($colaborador->data_desligamento)->format('d/m/Y') : '-' }}</td>
                    <td align="center">{{ !empty($colaborador->data_nascimento) ? \Carbon\Carbon::parse($colaborador->data_nascimento)->format('d/m/Y') : '-' }}</td>
                    <td align="center">
                        @if(!empty($permissoes['pode_excluir']))
                            <button type="button"
                                    class="waves-effect waves-light btn btn-sm bg-gradient-danger btn-delete"
                                    data-url="{{ route('pessoas.colaboradores.destroy', $colaborador->id) }}"
                                    title="Excluir">
                                <i class="fa fa-trash"></i>
                            </button>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" align="center">Nenhum registro encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(method_exists($dados, 'links'))
    <div class="d-flex justify-content-end mt-3">
        {{ $dados->links() }}
    </div>
@endif

<script>
document.querySelectorAll('.btn-delete').forEach(function (button) {
    button.addEventListener('click', function () {
        const url = this.dataset.url;

        swal({
            title: 'Tem certeza?',
            text: 'Esta ação fará a exclusão lógica do registro.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }, function (isConfirm) {
            if (!isConfirm) {
                return;
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    _method: 'DELETE'
                })
            })
            .then(function (response) {
                return response.json();
            })
            .then(function (res) {
                if (res.success) {
                    toastr.success(res.message);
                    window.location.reload();
                } else {
                    toastr.error(res.message || 'Erro ao excluir colaborador.');
                }
            })
            .catch(function () {
                toastr.error('Erro ao excluir colaborador.');
            });
        });
    });
});
</script>
