<?php

namespace App\Services\Aprovacao;

use App\Models\AprovacaoFluxo;
use App\Models\AprovacaoLog;
use App\Models\AprovacaoSolicitacao;
use App\Models\AprovacaoSolicitacaoEtapa;
use App\Models\AprovacaoSolicitacaoEtapaAprovador;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CriarSolicitacaoAprovacaoService
{
    public function executar(array $dados): AprovacaoSolicitacao
    {
        return DB::transaction(function () use ($dados) {
            $fluxo = AprovacaoFluxo::with(['etapas.aprovadores.colaborador'])
                ->where('id', $dados['fluxo_id'])
                ->where('situacao', 'ativo')
                ->firstOrFail();

            if ($fluxo->etapas->isEmpty()) {
                throw new \Exception('O fluxo informado não possui etapas cadastradas.');
            }

            $solicitacao = AprovacaoSolicitacao::create([
                'fluxo_id'                    => $fluxo->id,
                'tipo_referencia'            => $dados['tipo_referencia'],
                'referencia_id'              => $dados['referencia_id'],
                'titulo'                     => $dados['titulo'] ?? null,
                'descricao'                  => $dados['descricao'] ?? null,
                'status'                     => 'em_aprovacao',
                'modo_aprovacao_snapshot'    => $fluxo->modo_aprovacao,
                'solicitante_colaborador_id' => $dados['solicitante_colaborador_id'],
                'etapa_atual'                => 1,
                'aberto_em'                  => now(),
            ]);

            $primeiraEtapaId = null;

            foreach ($fluxo->etapas as $index => $etapaFluxo) {
                $etapaSolicitacao = AprovacaoSolicitacaoEtapa::create([
                    'solicitacao_id'                         => $solicitacao->id,
                    'fluxo_etapa_id'                        => $etapaFluxo->id,
                    'nome_etapa_snapshot'                   => $etapaFluxo->nome_etapa,
                    'ordem'                                 => $etapaFluxo->ordem,
                    'tipo_aprovacao_snapshot'               => $etapaFluxo->tipo_aprovacao_etapa,
                    'quantidade_minima_aprovacao_snapshot'  => $etapaFluxo->quantidade_minima_aprovacao,
                    'status'                                => $index === 0 ? 'liberada' : 'aguardando',
                    'liberada_em'                           => $index === 0 ? now() : null,
                ]);

                if ($index === 0) {
                    $primeiraEtapaId = $etapaSolicitacao->id;
                }

                foreach ($etapaFluxo->aprovadores as $aprovadorFluxo) {
                    AprovacaoSolicitacaoEtapaAprovador::create([
                        'solicitacao_etapa_id'   => $etapaSolicitacao->id,
                        'colaborador_id'         => $aprovadorFluxo->colaborador_id,
                        'nome_aprovador_snapshot'=> optional($aprovadorFluxo->colaborador)->nome,
                        'status'                 => 'pendente',
                    ]);
                }
            }

            AprovacaoLog::create([
                'solicitacao_id'       => $solicitacao->id,
                'solicitacao_etapa_id' => $primeiraEtapaId,
                'colaborador_id'       => $dados['solicitante_colaborador_id'] ?? Auth::id(),
                'evento'               => 'abertura',
                'descricao'            => 'Solicitação de aprovação criada com sucesso.',
                'ip'                   => request()->ip(),
                'user_agent'           => request()->userAgent(),
                'session_id'           => session()->getId(),
                'cookie_hash'          => hash('sha256', session()->getId() ?: uniqid('', true)),
                'payload_json'         => [
                    'fluxo_id'         => $fluxo->id,
                    'tipo_referencia'  => $dados['tipo_referencia'],
                    'referencia_id'    => $dados['referencia_id'],
                ],
                'created_at'           => now(),
            ]);

            return $solicitacao;
        });
    }
}
