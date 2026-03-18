@extends('layouts.app')

@section('title', 'Cristalcopo - Filiais')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Filiais</h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ url('/') }}">
                                        <i class="mdi mdi-home-outline"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item">Empresa</li>
                                <li class="breadcrumb-item active" aria-current="page">Filiais</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <a href="{{ route('gestao.empresa.filiais.create') }}"
                   class="waves-effect waves-light btn mb-5 bg-gradient-success w-200">
                    Nova Empresa
                </a>
            </div>
        </div>

        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">Filtros</h4>
                        </div>
                        <div class="box-body">
                            <form id="form-filtro" method="GET" action="{{ route('gestao.empresa.filiais.index') }}">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Razão Social ou CNPJ</label>
                                            <input
                                                type="text"
                                                id="campo-busca"
                                                name="busca"
                                                class="form-control"
                                                placeholder="Digite a Razão Social ou CNPJ"
                                                value="{{ request('busca') }}"
                                                autocomplete="off"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">Empresa</h4>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-primary">
                                        <tr align="center">
                                            <th>Nome Fantasia</th>
                                            <th>CNPJ</th>
                                            <th width="200">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($filiais as $filial)
                                            <tr>
                                                <td>{{ $filial->nome_fantasia }}</td>
                                                <td>{{ \App\Helpers\DocumentoHelper::cnpj($filial->cnpj) }}</td>
                                                <td align="center">
                                                    <div class="clearfix d-flex justify-content-center gap-2">
                                                        <a href="{{ route('gestao.empresa.filiais.edit', $filial->id) }}"
                                                           class="waves-effect waves-light btn mb-5 bg-gradient-primary"
                                                           title="Editar">
                                                            <i class="fa fa-edit"></i>
                                                        </a>

                                                        <button
                                                            type="button"
                                                            class="waves-effect waves-light btn mb-5 bg-gradient-danger btn-excluir"
                                                            data-id="{{ $filial->id }}"
                                                            data-nome="{{ $filial->nome_fantasia }}"
                                                            title="Excluir"
                                                        >
                                                            <i class="fa fa-trash-o"></i>
                                                        </button>

                                                        <form
                                                            id="form-excluir-{{ $filial->id }}"
                                                            action="{{ route('gestao.empresa.filiais.destroy', $filial->id) }}"
                                                            method="POST"
                                                            style="display: none;"
                                                        >
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center">Nenhuma filial encontrada.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if(method_exists($filiais, 'links'))
                                <div class="mt-3">
                                    {{ $filiais->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const campoBusca = document.getElementById('campo-busca');
        const formFiltro = document.getElementById('form-filtro');

        let debounceTimer = null;

        if (campoBusca && formFiltro) {
            campoBusca.addEventListener('input', function () {
                clearTimeout(debounceTimer);

                debounceTimer = setTimeout(function () {
                    formFiltro.submit();
                }, 400);
            });
        }

        const botoesExcluir = document.querySelectorAll('.btn-excluir');

        botoesExcluir.forEach(function (botao) {
            botao.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const nome = this.getAttribute('data-nome');

                swal({
                    title: 'Confirmar exclusão?',
                    text: 'A filial "' + nome + '" será removida da listagem.',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2962FF',
                    confirmButtonText: 'Sim, excluir',
                    cancelButtonText: 'Cancelar',
                    closeOnConfirm: true
                }, function () {
                    const form = document.getElementById('form-excluir-' + id);
                    if (form) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection
