<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Cristalcopo - Configuração de Aprovação</title>

    <link rel="stylesheet" href="{{ asset('assets/css/vendors_css.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/skin_color.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed">

<div class="wrapper">
    <div id="loader"></div>

    @include('layouts.includes.header')
    @include('layouts.includes.menu')

    <div class="content-wrapper">
        <div class="container-full">

            <!-- HEADER -->
            <div class="content-header">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <h4 class="page-title">Configuração de Fluxos de Aprovação</h4>
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ url('/dashboard') }}">
                                        <i class="mdi mdi-home-outline"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item">Configuração</li>
                                <li class="breadcrumb-item active">Fluxos</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- CONTEÚDO -->
            <section class="content">

                <!-- NOVA CONFIGURAÇÃO -->
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Nova Configuração</h4>
                    </div>

                    <form method="POST" action="{{ route('aprovacao.configuracao.store') }}">
                        @csrf

                        <div class="box-body">
                            <div class="row">

                                <div class="col-md-4">
                                    <label>Tipo de Referência *</label>
                                    <input type="text"
                                           name="tipo_referencia"
                                           class="form-control"
                                           placeholder="Ex: cargo, filial"
                                           required>
                                </div>

                                <div class="col-md-4">
                                    <label>Fluxo *</label>
                                    <select name="fluxo_id" class="form-select" required>
                                        <option value="">Selecione</option>
                                        @foreach($fluxos as $fluxo)
                                            <option value="{{ $fluxo->id }}">
                                                {{ $fluxo->nome_fluxo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label>Ativo</label>
                                    <select name="ativo" class="form-select">
                                        <option value="1">Sim</option>
                                        <option value="0">Não</option>
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="box-footer text-end">
                            <button class="btn bg-gradient-success">
                                Salvar Configuração
                            </button>
                        </div>
                    </form>
                </div>

                <!-- LISTAGEM -->
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Configurações Cadastradas</h4>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="bg-primary">
                                    <tr>
                                        <th>Tipo Referência</th>
                                        <th>Fluxo</th>
                                        <th>Ativo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($configuracoes as $config)
                                        <tr>
                                            <td>{{ $config->tipo_referencia }}</td>
                                            <td>{{ $config->fluxo->nome_fluxo ?? '-' }}</td>
                                            <td>
                                                @if($config->ativo)
                                                    <span class="badge bg-success">Sim</span>
                                                @else
                                                    <span class="badge bg-danger">Não</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                Nenhuma configuração cadastrada.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </section>

        </div>
    </div>

    @include('layouts.includes.footer')
</div>

<!-- JS -->
<script src="{{ asset('assets/js/vendors.min.js') }}"></script>
<script src="{{ asset('assets/js/template.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: "4000"
    };
</script>

@if(session('success'))
<script>
    toastr.success(@json(session('success')));
</script>
@endif

@if(session('error'))
<script>
    toastr.error(@json(session('error')));
</script>
@endif

@if ($errors->any())
<script>
    @foreach ($errors->all() as $error)
        toastr.error(@json($error));
    @endforeach
</script>
@endif

</body>
</html>
