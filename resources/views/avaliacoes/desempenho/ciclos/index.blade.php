@extends('layouts.app')

@section('title', 'Cristalcopo - Avaliação de Desempenho')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Avaliação de Desempenho</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item">Avaliações</li>
                        <li class="breadcrumb-item active">Ciclos</li>
                    </ol>
                </div>
                <a href="{{ route('avaliacoes.desempenho.ciclos.create') }}" class="btn btn-success">Novo ciclo</a>
            </div>
        </div>

        <section class="content">
            <div class="box">
                <div class="box-header with-border">
                    <form method="GET" action="{{ route('avaliacoes.desempenho.ciclos.index') }}">
                        <div class="row">
                            <div class="col-md-10">
                                <input type="text" name="busca" class="form-control" placeholder="Buscar por nome do ciclo" value="{{ request('busca') }}">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary w-100">Filtrar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-primary">
                            <tr>
                                <th>Nome</th>
                                <th>Tipo</th>
                                <th>Status</th>
                                <th>Período</th>
                                <th>Público</th>
                                <th>Estrutura</th>
                                <th>Execução</th>
                                <th width="180">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ciclos as $ciclo)
                                <tr>
                                    <td>
                                        <strong>{{ $ciclo->nome }}</strong><br>
                                        <small>{{ \Illuminate\Support\Str::limit($ciclo->descricao, 80) }}</small>
                                    </td>
                                    <td>{{ $ciclo->tipo_avaliacao }}°</td>
                                    <td><span class="badge badge-info">{{ ucfirst($ciclo->status) }}</span></td>
                                    <td>{{ optional($ciclo->data_inicio)->format('d/m/Y') }} até {{ optional($ciclo->data_fim)->format('d/m/Y') }}</td>
                                    <td>{{ $ciclo->resumo_publico }}</td>
                                    <td>{{ $ciclo->pilares_count }} pilares / {{ $ciclo->perguntas_count }} perguntas</td>
                                    <td>{{ $ciclo->avaliacoes_count }} avaliações geradas</td>
                                    <td>
                                        <a href="{{ route('avaliacoes.desempenho.ciclos.edit', $ciclo->id) }}" class="btn btn-primary btn-sm">Gerenciar</a>
                                        <form action="{{ route('avaliacoes.desempenho.ciclos.destroy', $ciclo->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Deseja remover este ciclo?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center">Nenhum ciclo cadastrado.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $ciclos->links() }}
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
