@extends('layouts.app')

@section('title', 'Cristalcopo - Formação')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto"><h4 class="page-title">Formação</h4></div>
                @if($permissoes['pode_gravar'])
                    <button type="button" class="waves-effect waves-light btn mb-5 bg-gradient-success w-200" id="btn-novo">Nova Formação</button>
                @endif
            </div>
        </div>

        @include('cargos.formacao.partials.filters')

        <section class="content">
            <div class="row"><div class="col-12"><div class="box"><div class="box-header with-border"><h4 class="box-title">Formações</h4></div><div class="box-body"><div id="table-wrapper">@include('cargos.formacao.partials.table', ['dados' => collect([]), 'permissoes' => $permissoes])</div></div></div></div></div>
        </section>
    </div>
</div>

@include('cargos.formacao.partials.modal-form')
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    let request = null, debounce = null;

    function limparFormulario() {
        $('#registro_id').val('');
        $('#descricao').val('');
        $('#form-action').val('{{ route('cargos.formacao.store') }}');
        $('#modal-title').text('Nova Formação');
        $('#submit-text').text('Salvar');
    }

    function carregarTabela(url = '{{ route('cargos.formacao.list') }}') {
        if (request) request.abort();
        request = $.ajax({
            url: url,
            type: 'GET',
            data: $('#form-filtros').serialize(),
            success: function (response) { $('#table-wrapper').html(response); },
            error: function () { toastr.error('Erro ao carregar formações.'); }
        });
    }

    carregarTabela();

    $('#busca').on('keyup', function () {
        clearTimeout(debounce);
        debounce = setTimeout(carregarTabela, 300);
    });

    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        carregarTabela($(this).attr('href'));
    });

    $('#btn-novo').on('click', function () {
        limparFormulario();
        $('#modal-registro').modal('show');
    });

    $(document).on('click', '.btn-editar', function () {
        const id = $(this).data('id');
        $.get('{{ url('/cargos/formacao/edit') }}/' + id, function (response) {
            $('#registro_id').val(response.data.id);
            $('#descricao').val(response.data.descricao);
            $('#form-action').val('{{ url('/cargos/formacao/update') }}/' + id);
            $('#modal-title').text('Editar Formação');
            $('#submit-text').text('Atualizar');
            $('#modal-registro').modal('show');
        }).fail(function () {
            toastr.error('Erro ao carregar formação.');
        });
    });

    $('#form-registro').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $('#form-action').val(),
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                $('#modal-registro').modal('hide');
                toastr.success(response.message);
                carregarTabela();
            },
            error: function (xhr) {
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    const chave = Object.keys(xhr.responseJSON.errors)[0];
                    toastr.error(xhr.responseJSON.errors[chave][0]);
                    return;
                }
                toastr.error(xhr.responseJSON?.message ?? 'Erro ao salvar.');
            }
        });
    });

    $(document).on('click', '.btn-excluir', function () {
        const url = $(this).data('url');

        swal({
            title: 'Excluir registro?',
            text: 'Esta ação fará a exclusão lógica do registro.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, excluir',
            cancelButtonText: 'Cancelar'
        }, function (isConfirm) {
            if (!isConfirm) return;

            $.ajax({
                url: url,
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', _method: 'DELETE' },
                success: function (response) {
                    toastr.success(response.message);
                    carregarTabela();
                },
                error: function (xhr) {
                    toastr.error(xhr.responseJSON?.message ?? 'Erro ao excluir.');
                }
            });
        });
    });
});
</script>
@endsection
