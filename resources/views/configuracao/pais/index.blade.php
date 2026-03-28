@extends('layouts.app')

@section('title', 'Conectta RH - Países')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Configuração de Países</h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item">Configuração</li>
                                <li class="breadcrumb-item active" aria-current="page">País</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                @if($permissoes['can_create'])
                    <button type="button" class="waves-effect waves-light btn mb-5 bg-gradient-success w-200" id="btn-novo-pais">
                        Adicionar novo país
                    </button>
                @endif
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
                            <div class="form-group mb-0">
                                <label class="form-label" for="filtro_descricao">Descrição</label>
                                <input
                                    type="text"
                                    id="filtro_descricao"
                                    class="form-control"
                                    placeholder="Digite o nome do país"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">Países</h4>
                        </div>
                        <div class="box-body">
                            <div id="table-paises">
                                @include('configuracao.pais.partials.table', [
                                    'dados' => collect([]),
                                    'permissoes' => $permissoes,
                                ])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

@include('configuracao.pais.partials.modal-form')
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    let request = null;
    let debounce = null;

    function limparFormulario() {
        $('#pais_id').val('');
        $('#nome').val('');
        $('#iso2').val('');
        $('#iso3').val('');
        $('#pais-modal-title').text('Adicionar novo país');
        $('#pais-form-action').val('{{ route('configuracao.pais.store') }}');
        $('#pais-submit-text').text('Salvar');
    }

    function carregarTabela(url = '{{ route('configuracao.pais.list') }}') {
        if (request) {
            request.abort();
        }

        request = $.ajax({
            url: url,
            method: 'GET',
            data: {
                descricao: $('#filtro_descricao').val()
            },
            success: function (response) {
                $('#table-paises').html(response);
            },
            error: function () {
                toastr.error('Erro ao carregar países.');
            }
        });
    }

    carregarTabela();

    $('#filtro_descricao').on('keyup', function () {
        clearTimeout(debounce);
        debounce = setTimeout(function () {
            carregarTabela();
        }, 300);
    });

    $(document).on('click', '#table-paises .pagination a', function (e) {
        e.preventDefault();
        carregarTabela($(this).attr('href'));
    });

    $('#btn-novo-pais').on('click', function () {
        limparFormulario();
        $('#modal-pais').modal('show');
    });

    $(document).on('click', '.btn-editar-pais', function () {
        const id = $(this).data('id');

        $.ajax({
            url: '{{ url('/configuracao/pais/edit') }}/' + id,
            method: 'GET',
            success: function (response) {
                const data = response.data;

                $('#pais_id').val(data.id);
                $('#nome').val(data.nome);
                $('#iso2').val(data.iso2);
                $('#iso3').val(data.iso3);

                $('#pais-modal-title').text('Editar país');
                $('#pais-form-action').val('{{ url('/configuracao/pais/update') }}/' + data.id);
                $('#pais-submit-text').text('Atualizar');

                $('#modal-pais').modal('show');
            },
            error: function () {
                toastr.error('Erro ao carregar país.');
            }
        });
    });

    $('#form-pais').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $('#pais-form-action').val(),
            method: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                $('#modal-pais').modal('hide');
                toastr.success(response.message);
                carregarTabela();
            },
            error: function (xhr) {
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    const errors = xhr.responseJSON.errors;
                    const primeiraChave = Object.keys(errors)[0];
                    toastr.error(errors[primeiraChave][0]);
                    return;
                }

                toastr.error(xhr.responseJSON?.message ?? 'Erro ao salvar país.');
            }
        });
    });

    $(document).on('click', '.btn-excluir-pais', function () {
        const url = $(this).data('url');

        swal({
            title: 'Excluir país?',
            text: 'Esta ação fará a exclusão lógica do registro.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, excluir',
            cancelButtonText: 'Cancelar'
        }, function (isConfirm) {
            if (!isConfirm) {
                return;
            }

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                },
                success: function (response) {
                    toastr.success(response.message);
                    carregarTabela();
                },
                error: function (xhr) {
                    toastr.error(xhr.responseJSON?.message ?? 'Erro ao excluir país.');
                }
            });
        });
    });
});
</script>
@endsection
