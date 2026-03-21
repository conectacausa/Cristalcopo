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
                    <button type="button" class="waves-effect waves-light btn mb-5 bg-gradient-success w-200">
                        Novo Cargo
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
                            <form id="form-filtros-cargos">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Cargo ou CBO</label>
                                            <input
                                                type="text"
                                                name="busca"
                                                id="busca"
                                                class="form-control"
                                                placeholder="Digite o cargo, código CBO ou descrição do CBO"
                                            >
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Filiais</label>
                                            <select name="filiais[]" id="filiais" class="form-control select2" multiple>
                                                @foreach($filiais as $filial)
                                                    <option value="{{ $filial->id }}">{{ $filial->nome_fantasia }}</option>
                                                @endforeach
                                            </select>
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
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: 'Selecione uma ou mais filiais',
            allowClear: true
        });

        let request = null;
        let debounce = null;

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
    });
</script>
@endsection
