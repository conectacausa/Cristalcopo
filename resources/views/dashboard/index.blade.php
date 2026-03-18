<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Cristalcopo</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f7fb;
            color: #1f2937;
        }

        .topbar {
            background: #111827;
            color: #ffffff;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .topbar h1 {
            margin: 0;
            font-size: 20px;
        }

        .topbar a {
            color: #ffffff;
            text-decoration: none;
            background: #dc2626;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 14px;
        }

        .container {
            max-width: 1100px;
            margin: 32px auto;
            padding: 0 20px;
        }

        .welcome {
            background: #ffffff;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
            margin-bottom: 24px;
        }

        .welcome h2 {
            margin: 0 0 8px;
            font-size: 24px;
        }

        .welcome p {
            margin: 0;
            color: #6b7280;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .card {
            background: #ffffff;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
        }

        .card h3 {
            margin: 0 0 10px;
            font-size: 16px;
            color: #374151;
        }

        .card .value {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .card .text {
            color: #6b7280;
            font-size: 14px;
        }

        .session-box {
            margin-top: 24px;
            background: #ffffff;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
        }

        .session-box h3 {
            margin-top: 0;
            margin-bottom: 16px;
        }

        .session-box table {
            width: 100%;
            border-collapse: collapse;
        }

        .session-box th,
        .session-box td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }

        .session-box th {
            width: 220px;
            color: #374151;
        }

        @media (max-width: 900px) {
            .grid {
                grid-template-columns: 1fr;
            }

            .topbar {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="topbar">
        <h1>Cristalcopo - Dashboard</h1>
        <a href="{{ route('auth.logout') }}">Sair</a>
    </div>

    <div class="container">
        <div class="welcome">
            <h2>Bem-vindo ao sistema</h2>
            <p>Se você está vendo esta tela, o login, a sessão e a permissão de acesso ao dashboard estão funcionando.</p>
        </div>

        <div class="grid">
            <div class="card">
                <h3>Status da Sessão</h3>
                <div class="value">Ativa</div>
                <div class="text">Usuário autenticado com sucesso.</div>
            </div>

            <div class="card">
                <h3>Tela Atual</h3>
                <div class="value">Dashboard</div>
                <div class="text">Acesso liberado pela permissão vinculada.</div>
            </div>

            <div class="card">
                <h3>Ambiente</h3>
                <div class="value">{{ app()->environment() }}</div>
                <div class="text">Ambiente atual da aplicação.</div>
            </div>
        </div>

        <div class="session-box">
            <h3>Dados da Sessão</h3>

            <table>
                <tr>
                    <th>ID do usuário</th>
                    <td>{{ auth()->user()->id ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Nome</th>
                    <td>{{ auth()->user()->nome_completo ?? '-' }}</td>
                </tr>
                <tr>
                    <th>CPF</th>
                    <td>{{ auth()->user()->cpf ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Situação</th>
                    <td>{{ (auth()->user()->situacao ?? false) ? 'Ativo' : 'Inativo' }}</td>
                </tr>
                <tr>
                    <th>Permissão ID</th>
                    <td>{{ auth()->user()->permissao_id ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Rota</th>
                    <td>{{ request()->path() }}</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
