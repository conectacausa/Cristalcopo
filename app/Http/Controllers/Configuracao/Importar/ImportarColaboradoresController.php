<?php

namespace App\Http\Controllers\Configuracao\Importar;

use App\Http\Controllers\Controller;
use App\Models\Colaborador;
use App\Models\Pessoas\Colaboradores\ColaboradorImportacao;
use App\Models\Pessoas\Colaboradores\ColaboradorImportacaoLinha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportarColaboradoresController extends Controller
{
    public function index()
    {
        $logs = ColaboradorImportacao::query()
            ->latest()
            ->paginate(25);

        return view('configuracao.importar.index', compact('logs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'arquivo' => ['required', 'file', 'mimes:xlsx,csv,xls'],
        ]);

        $arquivo = $request->file('arquivo');
        $path = $arquivo->store('importacoes/colaboradores');

        $importacao = ColaboradorImportacao::create([
            'arquivo_nome' => $arquivo->getClientOriginalName(),
            'arquivo_path' => $path,
            'tipo_arquivo' => $arquivo->getClientOriginalExtension(),
            'status' => 'processando',
            'colaborador_id' => auth()->id(),
        ]);

        $spreadsheet = IOFactory::load(Storage::path($path));
        $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        if (count($sheet) < 2) {
            $importacao->update([
                'status' => 'erro',
                'total_erro' => 1,
            ]);

            return back()->with('error', 'Arquivo sem dados para importação.');
        }

        $header = array_map(fn($v) => trim((string) $v), $sheet[1]);

        $totalInseridas = 0;
        $totalAtualizadas = 0;
        $totalErro = 0;
        $totalProcessadas = 0;

        DB::beginTransaction();

        try {
            foreach ($sheet as $linhaNumero => $linha) {
                if ($linhaNumero === 1) {
                    continue;
                }

                $dados = [];
                foreach ($header as $coluna => $nomeCampo) {
                    $dados[$nomeCampo] = $linha[$coluna] ?? null;
                }

                if (empty($dados['matricula'])) {
                    continue;
                }

                $totalProcessadas++;

                $matricula = trim((string) $dados['matricula']);
                $colaborador = Colaborador::where('matricula', $matricula)->first();

                $dadosTratados = [
                    'matricula' => $matricula,
                    'codigo_importacao' => $dados['codigo_importacao'] ?? null,
                    'nome_completo' => $dados['nome_completo'] ?? null,
                    'nome_social' => $dados['nome_social'] ?? null,
                    'cpf' => preg_replace('/\D/', '', (string) ($dados['cpf'] ?? '')),
                    'email' => $dados['email'] ?? null,
                    'telefone' => $dados['telefone'] ?? null,
                    'celular' => $dados['celular'] ?? null,
                    'regime' => $dados['regime'] ?? null,
                    'forma_trabalho' => $dados['forma_trabalho'] ?? null,
                    'admissao' => $dados['admissao'] ?: null,
                    'desligamento' => $dados['desligamento'] ?: null,
                    'data_nascimento' => $dados['data_nascimento'] ?: null,
                    'pcd' => strtoupper((string) ($dados['pcd'] ?? 'NAO')) === 'SIM',
                    'afastado' => strtoupper((string) ($dados['afastado'] ?? 'NAO')) === 'SIM',
                    'menor_aprendiz' => strtoupper((string) ($dados['menor_aprendiz'] ?? 'NAO')) === 'SIM',
                    'raca_cor' => $dados['raca_cor'] ?? null,
                    'nacionalidade' => $dados['nacionalidade'] ?? null,
                    'naturalidade' => $dados['naturalidade'] ?? null,
                    'genero' => $dados['genero'] ?? null,
                    'calcula_headcount' => strtoupper((string) ($dados['calcula_headcount'] ?? 'SIM')) === 'SIM',
                    'estabilidade' => $dados['estabilidade'] ?? null,
                    'logradouro' => $dados['logradouro'] ?? null,
                    'numero_casa' => $dados['numero_casa'] ?? null,
                    'complemento' => $dados['complemento'] ?? null,
                    'bairro' => $dados['bairro'] ?? null,
                    'cep' => $dados['cep'] ?? null,
                ];

                $dadosAntes = $colaborador ? $colaborador->toArray() : null;

                if (!$colaborador) {
                    $colaborador = Colaborador::create($dadosTratados);
                    $acao = 'inserido';
                    $totalInseridas++;
                } else {
                    $alteracoes = [];

                    foreach ($dadosTratados as $campo => $valor) {
                        if ($colaborador->{$campo} != $valor) {
                            $alteracoes[$campo] = [
                                'anterior' => $colaborador->{$campo},
                                'novo' => $valor,
                            ];
                            $colaborador->{$campo} = $valor;
                        }
                    }

                    $colaborador->save();
                    $acao = 'atualizado';
                    $totalAtualizadas++;
                }

                ColaboradorImportacaoLinha::create([
                    'importacao_id' => $importacao->id,
                    'linha' => $linhaNumero,
                    'matricula' => $matricula,
                    'colaborador_id' => $colaborador->id,
                    'acao' => $acao,
                    'dados_entrada' => $dados,
                    'dados_anteriores' => $dadosAntes,
                    'dados_novos' => $colaborador->fresh()->toArray(),
                    'alteracoes' => isset($alteracoes) ? $alteracoes : null,
                    'mensagem' => $acao === 'inserido' ? 'Colaborador criado.' : 'Colaborador atualizado.',
                    'sucesso' => true,
                ]);
            }

            $importacao->update([
                'status' => 'finalizado',
                'total_linhas' => max(count($sheet) - 1, 0),
                'total_processadas' => $totalProcessadas,
                'total_inseridas' => $totalInseridas,
                'total_atualizadas' => $totalAtualizadas,
                'total_erro' => $totalErro,
            ]);

            DB::commit();

            return back()->with('success', 'Importação concluída com sucesso.');
        } catch (\Throwable $e) {
            DB::rollBack();

            $importacao->update([
                'status' => 'erro',
                'total_erro' => $totalErro + 1,
            ]);

            return back()->with('error', 'Erro ao importar colaboradores: ' . $e->getMessage());
        }
    }
}
