@extends('layouts.app')

@section('title', 'Cristalcopo - Cargos')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<style>
    .cargo-cbo {
        display: block;
        font-size: 12px;
        color: #6c757d;
        margin-top: 3px;
    }

    .badge {
        font-size: 11px;
        padding: 7px 10px;
    }

    .select2-container {
        width: 100% !important;
    }

    .resumo-texto {
        font-size: 12px;
        color: #6c757d;
        margin-top: 4px;
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Cargos</h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#"><i class="mdi mdi-home-outline"></i></a>
                                </li>
                                <li class="breadcrumb-item">Cadastros</li>
                                <li class="breadcrumb-item active" aria-current="page">Cargos</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                @if($permissoes['pode_gravar'])
                    <button type="button"
                            class="waves-effect waves-light btn mb-5 bg-gradient-success w-200"
                            id="btn-novo-cargo">
                        Novo Cargo
                    </button>
                @endif
            </div>
        </div>

        <section class="content">
            @include('cargos.cargos.partials.filters')

            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">Cargos</h4>
                        </div>
                        <div class="box-body">
                            <div id="table-cargos">
                                @include('cargos.cargos.partials.table', [
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

@include('cargos.cargos.partials.modal-form')
@include('cargos.cargos.partials.modal-show')
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: 'Selecione',
            allowClear: true,
            width: '100%'
        });

        let request = null;
        let debounce = null;

        function limparFormulario() {
            $('#cargo_id').val('');
            $('#form-cargo')[0].reset();

            $('#cargo_cbo_id').val('').trigger('change');
            $('#filiais_modal').val(null).trigger('change');
            $('#setores_modal').val(null).trigger('change');

            $('#conta_base_jovem_aprendiz').prop('checked', false);

            $('#cargo-modal-title').text('Novo Cargo');
            $('#cargo-form-action').val('{{ route('cargos.cargos.store') }}');
            $('#cargo-submit-text').text('Salvar');
        }

        function carregarTabela(url = '{{ route('cargos.cargos.list') }}') {
            if (request) {
                request.abort();
            }

            request = $.ajax({
                url: url,
                method: 'GET',
                data: $('#form-filtros-cargos').serialize(),
                success: function (response) {
                    $('#table-cargos').html(response);
                },
                error: function () {
                    toastr.error('Erro ao carregar cargos.');
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

        $('#filiais').on('change', function () {
            carregarTabela();
        });

        $(document).on('click', '#table-cargos .pagination a', function (e) {
            e.preventDefault();
            carregarTabela($(this).attr('href'));
        });

        $('#btn-novo-cargo').on('click', function () {
            limparFormulario();
            $('#modal-cargo').modal('show');
        });

        $(document).on('click', '.btn-editar-cargo', function () {
            limparFormulario();

            const id = $(this).data('id');

            $.ajax({
                url: '{{ url('/cargos/edit') }}/' + id,
                method: 'GET',
                success: function (response) {
                    const data = response.data;

                    $('#cargo_id').val(data.id);
                    $('#titulo_cargo').val(data.titulo_cargo);
                    $('#codigo_importacao').val(data.codigo_importacao);
                    $('#cargo_cbo_id').val(data.cargo_cbo_id).trigger('change');
                    $('#filiais_modal').val(data.filiais).trigger('change');
                    $('#setores_modal').val(data.setores).trigger('change');
                    $('#conta_base_jovem_aprendiz').prop('checked', !!data.conta_base_jovem_aprendiz);

                    $('#cargo-modal-title').text('Editar Cargo');
                    $('#cargo-form-action').val('{{ url('/cargos/update') }}/' + data.id);
                    $('#cargo-submit-text').text('Atualizar');

                    $('#modal-cargo').modal('show');
                },
                error: function () {
                    toastr.error('Erro ao carregar dados do cargo.');
                }
            });
        });

        $('#form-cargo').on('submit', function (e) {
            e.preventDefault();

            const action = $('#cargo-form-action').val();

            $.ajax({
                url: action,
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    $('#modal-cargo').modal('hide');
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

                    const message = xhr.responseJSON?.message ?? 'Erro ao salvar cargo.';
                    toastr.error(message);
                }
            });
        });

        $(document).on('click', '.btn-excluir-cargo', function () {
            const url = $(this).data('url');

            swal({
                title: 'Excluir cargo?',
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
                        const message = xhr.responseJSON?.message ?? 'Erro ao excluir cargo.';
                        toastr.error(message);
                    }
                });
            });
        });

        $(document).on('click', '.btn-visualizar-cargo', function () {
            const id = $(this).data('id');

            $.ajax({
                url: '{{ url('/cargos/show') }}/' + id,
                method: 'GET',
                success: function (response) {
                    const data = response.data;

                    $('#show_titulo_cargo').text(data.titulo_cargo ?? '-');
                    $('#show_codigo_importacao').text(data.codigo_importacao ?? '-');
                    $('#show_cbo').text(
                        ((data.codigo_cbo ?? '-') + ' - ' + (data.descricao_cbo ?? '-')).trim()
                    );
                    $('#show_status').text(data.status_aprovacao ?? '-');
                    $('#show_jovem_aprendiz').text(data.conta_base_jovem_aprendiz ? 'Sim' : 'Não');
                    $('#show_filiais').text((data.filiais ?? []).join(', ') || '-');
                    $('#show_setores').text((data.setores ?? []).join(', ') || '-');
                    $('#show_aprovacao_solicitacao_id').text(data.aprovacao_solicitacao_id ?? '-');

                    $('#modal-show-cargo').modal('show');
                },
                error: function () {
                    toastr.error('Erro ao carregar detalhes do cargo.');
                }
            });
        });
    });
</script>
@endsection
