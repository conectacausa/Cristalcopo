@extends('layouts.app')

@section('title', 'Cristalcopo - Ciclo de Avaliação')

@section('content')
@php
    $editing = $ciclo->exists;
    $route = $editing ? route('avaliacoes.desempenho.ciclos.update', $ciclo->id) : route('avaliacoes.desempenho.ciclos.store');
@endphp
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">{{ $editing ? 'Editar ciclo' : 'Novo ciclo de avaliação' }}</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item">Avaliações</li>
                        <li class="breadcrumb-item"><a href="{{ route('avaliacoes.desempenho.ciclos.index') }}">Desempenho</a></li>
                        <li class="breadcrumb-item active">{{ $editing ? $ciclo->nome : 'Novo ciclo' }}</li>
                    </ol>
                </div>
            </div>
        </div>

        <section class="content">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ $route }}">
                @csrf
                @if($editing)
                    @method('PUT')
                @endif

                <div class="box">
                    <div class="box-header with-border"><h4 class="box-title">Dados gerais do ciclo</h4></div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6"><div class="form-group"><label class="form-label">Nome</label><input type="text" name="nome" class="form-control" value="{{ old('nome', $ciclo->nome) }}" required></div></div>
                            <div class="col-md-3"><div class="form-group"><label class="form-label">Tipo</label><select name="tipo_avaliacao" class="form-control select2"><option value="90" @selected(old('tipo_avaliacao', $ciclo->tipo_avaliacao)==='90')>90°</option><option value="180" @selected(old('tipo_avaliacao', $ciclo->tipo_avaliacao)==='180')>180°</option><option value="360" @selected(old('tipo_avaliacao', $ciclo->tipo_avaliacao)==='360')>360°</option></select></div></div>
                            <div class="col-md-3"><div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control select2"><option value="rascunho" @selected(old('status', $ciclo->status)==='rascunho')>Rascunho</option><option value="agendada" @selected(old('status', $ciclo->status)==='agendada')>Agendada</option><option value="ativa" @selected(old('status', $ciclo->status)==='ativa')>Ativa</option><option value="encerrada" @selected(old('status', $ciclo->status)==='encerrada')>Encerrada</option><option value="cancelada" @selected(old('status', $ciclo->status)==='cancelada')>Cancelada</option></select></div></div>
                        </div>
                        <div class="row">
                            <div class="col-md-12"><div class="form-group"><label class="form-label">Descrição</label><textarea name="descricao" class="form-control" rows="3">{{ old('descricao', $ciclo->descricao) }}</textarea></div></div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><div class="form-group"><label class="form-label">Data início</label><input type="date" name="data_inicio" class="form-control" value="{{ old('data_inicio', optional($ciclo->data_inicio)->format('Y-m-d')) }}" required></div></div>
                            <div class="col-md-3"><div class="form-group"><label class="form-label">Data fim</label><input type="date" name="data_fim" class="form-control" value="{{ old('data_fim', optional($ciclo->data_fim)->format('Y-m-d')) }}" required></div></div>
                            <div class="col-md-3"><div class="form-group"><label class="form-label">Forma de liberação</label><select name="forma_liberacao" class="form-control select2"><option value="manual" @selected(old('forma_liberacao', $ciclo->forma_liberacao)==='manual')>Manual</option><option value="automatica" @selected(old('forma_liberacao', $ciclo->forma_liberacao)==='automatica')>Automática</option></select></div></div>
                        </div>
                    </div>
                </div>

                <div class="box">
                    <div class="box-header with-border"><h4 class="box-title">Configurações de participação</h4></div>
                    <div class="box-body">
                        <div class="row">
                            @foreach([
                                'permite_autoavaliacao' => 'Permitir autoavaliação',
                                'permite_avaliacao_gestor' => 'Permitir avaliação do gestor',
                                'permite_avaliacao_pares' => 'Permitir avaliação de pares',
                                'permite_avaliacao_subordinados' => 'Permitir avaliação de subordinados',
                                'anonimato' => 'Aplicar anonimato',
                                'permite_edicao_ate_prazo_final' => 'Permitir edição até o prazo final',
                                'permite_resposta_parcial' => 'Permitir resposta parcial',
                            ] as $field => $label)
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">{{ $label }}</label>
                                        <select name="{{ $field }}" class="form-control select2">
                                            <option value="1" @selected((int) old($field, $ciclo->{$field}) === 1)>Sim</option>
                                            <option value="0" @selected((int) old($field, $ciclo->{$field}) === 0)>Não</option>
                                        </select>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="box">
                    <div class="box-header with-border"><h4 class="box-title">Lembretes</h4></div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3"><div class="form-group"><label class="form-label">Lembrete ativo</label><select name="lembrete_ativo" class="form-control select2"><option value="1" @selected((int) old('lembrete_ativo', $ciclo->lembrete_ativo)===1)>Sim</option><option value="0" @selected((int) old('lembrete_ativo', $ciclo->lembrete_ativo)===0)>Não</option></select></div></div>
                            <div class="col-md-3"><div class="form-group"><label class="form-label">Frequência</label><select name="lembrete_frequencia" class="form-control select2"><option value="">Selecione</option><option value="diario" @selected(old('lembrete_frequencia', $ciclo->lembrete_frequencia)==='diario')>Diário</option><option value="semanal" @selected(old('lembrete_frequencia', $ciclo->lembrete_frequencia)==='semanal')>Semanal</option><option value="personalizado" @selected(old('lembrete_frequencia', $ciclo->lembrete_frequencia)==='personalizado')>Personalizado</option></select></div></div>
                            <div class="col-md-3"><div class="form-group"><label class="form-label">Intervalo em dias</label><input type="number" min="1" max="365" name="lembrete_intervalo_dias" class="form-control" value="{{ old('lembrete_intervalo_dias', $ciclo->lembrete_intervalo_dias) }}"></div></div>
                            <div class="col-md-3"><div class="form-group"><label class="form-label">Horário padrão</label><input type="time" name="lembrete_horario" class="form-control" value="{{ old('lembrete_horario', $ciclo->lembrete_horario) }}"></div></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><label class="form-label d-block">Canais</label><label class="me-3"><input type="checkbox" name="lembrete_canais[]" value="email" @checked(in_array('email', old('lembrete_canais', $ciclo->lembrete_canais ?? []), true))> E-mail</label><label><input type="checkbox" name="lembrete_canais[]" value="interna" @checked(in_array('interna', old('lembrete_canais', $ciclo->lembrete_canais ?? []), true))> Notificação interna</label></div>
                            <div class="col-md-4"><div class="form-group"><label class="form-label">Parar após resposta</label><select name="lembrete_parar_ao_responder" class="form-control select2"><option value="1" @selected((int) old('lembrete_parar_ao_responder', $ciclo->lembrete_parar_ao_responder)===1)>Sim</option><option value="0" @selected((int) old('lembrete_parar_ao_responder', $ciclo->lembrete_parar_ao_responder)===0)>Não</option></select></div></div>
                            <div class="col-md-4"><div class="form-group"><label class="form-label">Enviar lembrete final</label><select name="lembrete_final_antes_encerramento" class="form-control select2"><option value="1" @selected((int) old('lembrete_final_antes_encerramento', $ciclo->lembrete_final_antes_encerramento)===1)>Sim</option><option value="0" @selected((int) old('lembrete_final_antes_encerramento', $ciclo->lembrete_final_antes_encerramento)===0)>Não</option></select></div></div>
                        </div>
                    </div>
                </div>

                @include('avaliacoes.desempenho.partials.publico-alvo')

                <div class="mt-3 mb-4">
                    <button class="btn btn-success">Salvar ciclo</button>
                    <a href="{{ route('avaliacoes.desempenho.ciclos.index') }}" class="btn btn-secondary">Voltar</a>
                    @if($editing)
                        <a href="{{ route('avaliacoes.desempenho.pilares.create', $ciclo->id) }}" class="btn btn-primary">Novo pilar</a>
                        <a href="{{ route('avaliacoes.desempenho.perguntas.create', $ciclo->id) }}" class="btn btn-info">Nova pergunta</a>
                    @endif
                </div>
            </form>

            @if($editing)
                <div class="row">
                    <div class="col-md-6">
                        <div class="box">
                            <div class="box-header with-border"><h4 class="box-title">Estrutura do questionário</h4></div>
                            <div class="box-body">
                                @forelse($ciclo->pilares as $pilar)
                                    <div class="border rounded p-2 mb-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div><strong>{{ $pilar->ordem }}. {{ $pilar->nome }}</strong> <small>({{ number_format((float) $pilar->peso, 2, ',', '.') }}%)</small></div>
                                            <div>
                                                <a href="{{ route('avaliacoes.desempenho.pilares.edit', $pilar->id) }}" class="btn btn-xs btn-primary">Editar</a>
                                                <a href="{{ route('avaliacoes.desempenho.grupos.create', $pilar->id) }}" class="btn btn-xs btn-info">Novo grupo</a>
                                            </div>
                                        </div>
                                        @foreach($pilar->grupos as $grupo)
                                            <div class="ms-3 mt-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>Grupo {{ $grupo->ordem }} - {{ $grupo->nome }}</span>
                                                    <span>
                                                        <a href="{{ route('avaliacoes.desempenho.grupos.edit', $grupo->id) }}" class="btn btn-xs btn-primary">Editar</a>
                                                        <a href="{{ route('avaliacoes.desempenho.subgrupos.create', $grupo->id) }}" class="btn btn-xs btn-info">Novo subgrupo</a>
                                                    </span>
                                                </div>
                                                @foreach($grupo->subgrupos as $subgrupo)
                                                    <div class="ms-3 mt-1 d-flex justify-content-between align-items-center">
                                                        <span>Subgrupo {{ $subgrupo->ordem }} - {{ $subgrupo->nome }}</span>
                                                        <a href="{{ route('avaliacoes.desempenho.subgrupos.edit', $subgrupo->id) }}" class="btn btn-xs btn-primary">Editar</a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                @empty
                                    <p class="text-muted">Nenhum pilar cadastrado.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="box">
                            <div class="box-header with-border"><h4 class="box-title">Perguntas e execução inicial</h4></div>
                            <div class="box-body">
                                <h5>Perguntas</h5>
                                @forelse($ciclo->perguntas as $pergunta)
                                    <div class="border rounded p-2 mb-2 d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $pergunta->ordem }}. {{ \Illuminate\Support\Str::limit($pergunta->enunciado, 60) }}</strong><br>
                                            <small>{{ $pergunta->tipo_resposta }} | Grupo: {{ $pergunta->grupo?->nome ?? 'Sem grupo' }}</small>
                                        </div>
                                        <a href="{{ route('avaliacoes.desempenho.perguntas.edit', $pergunta->id) }}" class="btn btn-xs btn-primary">Editar</a>
                                    </div>
                                @empty
                                    <p class="text-muted">Nenhuma pergunta cadastrada.</p>
                                @endforelse

                                <hr>
                                <h5>Fase 2 preparada</h5>
                                <ul>
                                    <li>A estrutura de avaliações, avaliadores e respostas já está criada no banco.</li>
                                    <li>O acompanhamento exibirá quantas avaliações foram geradas para o ciclo.</li>
                                    <li>A definição automática de avaliadores e a coleta de respostas ficam para a próxima fase.</li>
                                </ul>
                                <div class="alert alert-info mb-0">Avaliações geradas neste ciclo: <strong>{{ $ciclo->avaliacoes->count() }}</strong>.</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </section>
    </div>
</div>
@endsection

@section('scripts')
<script>
window.adicionarLinhaPublico = function(button) {
    const container = button.closest('.box').querySelector('.publico-container');
    const index = container.querySelectorAll('.publico-item').length;
    container.insertAdjacentHTML('beforeend', `
        <div class="row publico-item align-items-end mb-2">
            <div class="col-md-5"><label class="form-label">Tipo</label><select name="publico_alvo[${index}][tipo]" class="form-control"><option value="filial">Filial</option><option value="setor">Setor</option><option value="cargo">Cargo</option><option value="colaborador">Colaborador</option></select></div>
            <div class="col-md-5"><label class="form-label">Referência</label><input type="number" min="1" class="form-control" name="publico_alvo[${index}][referencia_id]"></div>
            <div class="col-md-2"><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.publico-item').remove()">Remover</button></div>
        </div>
    `);
};
</script>
@endsection
