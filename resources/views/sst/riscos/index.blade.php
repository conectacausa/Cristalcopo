@extends('layouts.app')

@section('title', 'Cristalcopo - Riscos Ocupacionais')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Riscos Ocupacionais</h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item">Segurança</li>
                                <li class="breadcrumb-item active" aria-current="page">Riscos</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                @if($permissoes['pode_gravar'])
                    <button type="button" class="waves-effect waves-light btn mb-5 bg-gradient-success w-200" id="btn-novo-risco">
                        Novo Risco
                    </button>
                @endif
            </div>
        </div>

        @include('sst.riscos.partials.filters')

        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">Riscos Ocupacionais</h4>
                        </div>
                        <div class="box-body">
                            <div id="table-riscos">
                                @include('sst.riscos.partials.table', [
                                    'dados' => collect([]),
                                    'permissoes' => $permissoes
                                ])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

@include('sst.riscos.partials.modal-form')
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        let request = null;
        let debounce = null;

        function limparFormulario() {
            $('#risco_id').val('');
            $('#descricao').val('');
            $('#grupo_risco').val('');
            $('#ativo').prop('checked', true);
            $('#risco-modal-title').text('Novo Risco Ocupacional');
            $('#risco-form-action').val('{{ route('sst.riscos.store') }}');
            $('#risco-submit-text').text('Salvar');
        }

        function carregarTabela(url = '{{ route('sst.riscos.list') }}') {
            if (request) {
                request.abort();
            }

            request = $.ajax({
                url: url,
                method: 'GET',
                data: $('#form-filtros-riscos').serialize(),
                success: function (response) {
                    $('#table-riscos').html(response);
                },
                error: function () {
                    toastr.error('Erro ao carregar riscos ocupacionais.');
                }
            });
        }

        carregarTabela();

        $('#busca').on('keyup', function () {
            clearTimeout(debounce);
            debounce = setTimeout(function () {
                carregarTabela();
            }, 300);
        });

        $('#ativo_filtro').on('change', function () {
            carregarTabela();
        });

        $(document).on('click', '#table-riscos .pagination a', function (e) {
            e.preventDefault();
            carregarTabela($(this).attr('href'));
        });

        $('#btn-novo-risco').on('click', function () {
            limparFormulario();
            $('#modal-risco').modal('show');
        });

        $(document).on('click', '.btn-editar-risco', function () {
            const id = $(this).data('id');

            $.ajax({
                url: '{{ url('/sst/riscos/edit') }}/' + id,
                method: 'GET',
                success: function (response) {
                    const data = response.data;

                    $('#risco_id').val(data.id);
                    $('#descricao').val(data.descricao);
                    $('#grupo_risco').val(data.grupo_risco ?? '');
                    $('#ativo').prop('checked', !!data.ativo);

                    $('#risco-modal-title').text('Editar Risco Ocupacional');
                    $('#risco-form-action').val('{{ url('/sst/riscos/update') }}/' + data.id);
                    $('#risco-submit-text').text('Atualizar');

                    $('#modal-risco').modal('show');
                },
                error: function () {
                    toastr.error('Erro ao carregar risco ocupacional.');
                }
            });
        });

        $('#form-risco').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: $('#risco-form-action').val(),
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    $('#modal-risco').modal('hide');
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

                    toastr.error(xhr.responseJSON?.message ?? 'Erro ao salvar risco ocupacional.');
                }
            });
        });

        $(document).on('click', '.btn-excluir-risco', function () {
            const url = $(this).data('url');

            swal({
                title: 'Excluir risco ocupacional?',
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
                        toastr.error(xhr.responseJSON?.message ?? 'Erro ao excluir risco ocupacional.');
                    }
                });
            });
        });
    });
</script>
@endsection
