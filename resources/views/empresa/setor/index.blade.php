@extends('layouts.app')

@section('title', 'Setor')

@section('content')

<div class="content-wrapper">
  <div class="container-full">

    <!-- Header -->
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Setor</h4>
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

        <!-- Botão Novo -->
        <button type="button"
                class="btn bg-gradient-success w-200"
                id="btnNovoSetor">
          Novo Setor
        </button>
      </div>
    </div>

    <!-- Conteúdo -->
    <section class="content">

      <!-- Filtros -->
      <div class="row">
        <div class="col-12">
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">Filtros</h4>
            </div>

            <div class="box-body">
              <div class="row">

                <!-- Nome -->
                <div class="col-md-7">
                  <div class="form-group">
                    <label class="form-label">Nome</label>
                    <input type="text"
                           id="filtro_nome"
                           class="form-control"
                           placeholder="Descrição do setor">
                  </div>
                </div>

                <!-- Filial -->
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

      <!-- Tabela -->
      <div class="row">
        <div class="col-12">
          <div class="box">

            <div class="box-header with-border">
              <h4 class="box-title">Setores</h4>
            </div>

            <div class="box-body">
              <div class="table-responsive">

                <table id="tabela_setores" class="table">
                  <thead class="bg-primary">
                    <tr align="center">
                      <th>Setor</th>
                      <th>Filial</th>
                      <th width="200">Ações</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- DataTable -->
                  </tbody>
                </table>

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

<script>
$(document).ready(function () {

    // 🔄 DataTable
    let tabela = $('#tabela_setores').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('empresa.setor.list') }}",
            data: function (d) {
                d.nome = $('#filtro_nome').val();
                d.filial = $('#filtro_filial').val();
            }
        },
        columns: [
            { data: 'setor', name: 'setor' },
            { data: 'filial', name: 'filial' },
            { data: 'acoes', name: 'acoes', orderable: false, searchable: false }
        ]
    });

    // 🔍 Filtros (reload padrão)
    $('#filtro_nome, #filtro_filial').on('change keyup', function () {
        tabela.ajax.reload();
    });

    // ➕ Novo setor
    $('#btnNovoSetor').click(function () {
        // abrir modal (vamos implementar depois)
        toastr.info('Abrir modal de cadastro');
    });

});


// 🗑️ Delete padrão com toastr + reload
function deletarSetor(id) {

    swal({
        title: "Tem certeza?",
        text: "Essa ação não poderá ser desfeita!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, excluir!"
    }, function() {

        $.ajax({
            url: "/empresa/setor/" + id,
            type: "DELETE",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function () {
                toastr.success('Setor excluído com sucesso!');
                $('#tabela_setores').DataTable().ajax.reload();
            },
            error: function () {
                toastr.error('Erro ao excluir setor');
            }
        });

    });
}
</script>

@endsection
