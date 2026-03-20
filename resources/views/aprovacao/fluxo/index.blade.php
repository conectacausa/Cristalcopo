<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Fluxos de Aprovação</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            gap: 10px;
            flex-wrap: wrap;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #0d6efd;
            color: #fff;
        }

        .btn-primary:hover {
            background: #0b5ed7;
        }

        .alert-success {
            background: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 20px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }

        table thead th {
            background: #f8f9fa;
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
            font-size: 14px;
        }

        table tbody td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
            vertical-align: top;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-success {
            background: #d1e7dd;
            color: #0f5132;
        }

        .badge-secondary {
            background: #e2e3e5;
            color: #41464b;
        }

        .empty {
            text-align: center;
            padding: 30px;
            color: #777;
        }

        .small {
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1 class="title">Fluxos de Aprovação</h1>

                <a href="{{ route('aprovacao.fluxo.create') }}" class="btn btn-primary">
                    Novo Fluxo
                </a>
            </div>

            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome do Fluxo</th>
                            <th>Slug</th>
                            <th>Tipo Referência</th>
                            <th>Modo</th>
                            <th>Situação</th>
                            <th>Etapas</th>
                            <th>Criado em</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fluxos as $fluxo)
                            <tr>
                                <td>{{ $fluxo->id }}</td>
                                <td>{{ $fluxo->nome_fluxo }}</td>
                                <td>{{ $fluxo->slug }}</td>
                                <td>{{ $fluxo->tipo_referencia }}</td>
                                <td>{{ ucfirst($fluxo->modo_aprovacao) }}</td>
                                <td>
                                    @if($fluxo->situacao === 'ativo')
                                        <span class="badge badge-success">Ativo</span>
                                    @else
                                        <span class="badge badge-secondary">Inativo</span>
                                    @endif
                                </td>
                                <td>{{ $fluxo->etapas()->count() }}</td>
                                <td>
                                    {{ optional($fluxo->created_at)->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="empty">
                                    Nenhum fluxo de aprovação cadastrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <p class="small" style="margin-top: 15px;">
                Tela inicial do módulo de fluxos de aprovação.
            </p>
        </div>
    </div>
</body>
</html>
