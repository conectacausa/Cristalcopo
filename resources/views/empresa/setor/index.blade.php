@extends('layouts.app')

@section('title', 'Setor')

@section('content')
<div class="content-wrapper">
    <div class="container-full">

        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Setor</h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#"><i class="mdi mdi-home-outline"></i></a>
                                </li>
                                <li class="breadcrumb-item">Empresa</li>
                                <li class="breadcrumb-item active">Setor</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <button
                    type="button"
                    class="waves-effect waves-light btn mb-5 bg-gradient-success w-200"
                    id="btnNovoSetor">
                    Novo Setor
                </button>
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
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label class="form-label">Nome</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="filtro_nome"
                                            placeholder="Descrição do setor">
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="form-label">Filial</label>
                                        <select id="filtro_filial" class="form-control">
                                            <option value="">Todas</option>
                                            @foreach($filiais as $filial)
                                                <option value="{{ $filial->id }}">
                                                    {{ $filial->nome }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            @include('empresa.setor.partials.table')
        </section>
    </div>
</div>

@include('empresa.setor.partials.modal')
@endsection

@section('scripts')
<script>
    let tabelaSetores;

    $(document).ready(function () {
        inicializarTabelaSetores();
        bindFiltros();
        bindFormularioSetor();
        bindBotaoNovo();
    });

    function inicializarTabelaSetores() {
        tabelaSetores = $('#tabela_setores').DataTable({
            processing: true,
            serverSide: false,
            searching: false,
            lengthChange: true,
            responsive: true,
            ajax: {
                url: "{{ route('empresa.setor.list') }}",
                type: "GET",
                data: function (d) {
                    d.nome = $('#filtro_nome').val();
                    d.filial = $('#filtro_filial').val();
                }
            },
            columns: [
                { data: 'setor', name: 'setor' },
                { data: 'filial', name: 'filial' },
                { data: 'acoes', name: 'acoes', orderable: false, searchable: false, className: 'text-center' }
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json"
            },
            drawCallback: function () {
                feather.replace();
            }
        });
    }

    function bindFiltros() {
        $('#filtro_nome').on('keyup', function () {
            reloadTabelaSetores();
        });

        $('#filtro_filial').on('change', function () {
            reloadTabelaSetores();
        });
    }

    function bindBotaoNovo() {
        $('#btnNovoSetor').on('click', function () {
            limparModalSetor();
            $('#setorModalLabel').text('Novo Setor');
            $('#setor_id').val('');
            $('#setorModal').modal('show');
        });
    }

    function bindFormularioSetor() {
        $('#formSetor').on('submit', function (e) {
            e.preventDefault();

            let id = $('#setor_id').val();
            let url = id
                ? "{{ url('empresa/setor/update') }}/" + id
                : "{{ route('empresa.setor.store') }}";

            let method = id ? "PUT" : "POST";

            $.ajax({
                url: url,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: method,
                    descricao: $('#descricao').val(),
                    id_filial: $('#id_filial').val()
                },
                beforeSend: function () {
                    $('#btnSalvarSetor').prop('disabled', true);
                },
                success: function (response) {
                    $('#btnSalvarSetor').prop('disabled', false);
                    $('#setorModal').modal('hide');

                    toastr.success(response.message || 'Registro salvo com sucesso.');
                    reloadTabelaSetores();
                    limparModalSetor();
                },
                error: function (xhr) {
                    $('#btnSalvarSetor').prop('disabled', false);

                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        let errors = xhr.responseJSON.errors;

                        Object.keys(errors).forEach(function (campo) {
                            toastr.error(errors[campo][0]);
                        });

                        return;
                    }

                    toastr.error(xhr.responseJSON?.message || 'Erro ao salvar registro.');
                }
            });
        });
    }

    function editarSetor(id) {
        limparModalSetor();

        $.ajax({
            url: "{{ url('empresa/setor/edit') }}/" + id,
            type: "GET",
            success: function (response) {
                $('#setorModalLabel').text('Editar Setor');
                $('#setor_id').val(response.id);
                $('#descricao').val(response.descricao);
                $('#id_filial').val(response.id_filial);

                $('#setorModal').modal('show');
            },
            error: function () {
                toastr.error('Erro ao carregar os dados do setor.');
            }
        });
    }

    function deletarSetor(id) {
        swal({
            title: "Tem certeza?",
            text: "Essa ação poderá ser desfeita apenas com reativação manual.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#00a65a",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, excluir",
            cancelButtonText: "Cancelar"
        }, function (isConfirm) {
            if (!isConfirm) {
                return;
            }

            $.ajax({
                url: "{{ url('empresa/setor') }}/" + id,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: "DELETE"
                },
                success: function (response) {
                    toastr.success(response.message || 'Registro excluído com sucesso.');
                    reloadTabelaSetores();
                },
                error: function (xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Erro ao excluir registro.');
                }
            });
        });
    }

    function reloadTabelaSetores() {
        if (tabelaSetores) {
            tabelaSetores.ajax.reload(null, false);
        }
    }

    function limparModalSetor() {
        $('#formSetor')[0].reset();
        $('#setor_id').val('');
    }
</script>
@endsection
