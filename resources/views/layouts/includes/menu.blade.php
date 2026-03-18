@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;

    $usuario = Auth::user();
    $permissaoId = $usuario?->permissao_id;
    $currentPath = request()->path();

    $normalizarCaminho = function ($caminho) {
        return trim($caminho ?? '', '/');
    };

    $modulosMenu = collect();

    if (!empty($permissaoId)) {
        $modulosMenu = DB::table('gestao_modulo as m')
            ->select('m.id', 'm.nome_modulo', 'm.ordem')
            ->whereExists(function ($query) use ($permissaoId) {
                $query->select(DB::raw(1))
                    ->from('gestao_tela as t')
                    ->join('vinculo_permissao_x_tela as vpt', 'vpt.tela_id', '=', 't.id')
                    ->whereColumn('t.modulo_id', 'm.id')
                    ->where('vpt.permissao_id', $permissaoId);
            })
            ->orderBy('m.ordem')
            ->orderBy('m.nome_modulo')
            ->get()
            ->map(function ($modulo) use ($permissaoId, $normalizarCaminho, $currentPath) {
                $grupos = DB::table('gestao_grupo_tela as g')
                    ->select('g.id', 'g.nome_grupo', 'g.icone', 'g.ordem')
                    ->where('g.modulo_id', $modulo->id)
                    ->whereExists(function ($query) use ($permissaoId) {
                        $query->select(DB::raw(1))
                            ->from('vinculo_tela_x_grupo as vtg')
                            ->join('gestao_tela as t', 't.id', '=', 'vtg.tela_id')
                            ->join('vinculo_permissao_x_tela as vpt', 'vpt.tela_id', '=', 't.id')
                            ->whereColumn('vtg.grupo_id', 'g.id')
                            ->where('vpt.permissao_id', $permissaoId);
                    })
                    ->orderBy('g.ordem')
                    ->orderBy('g.nome_grupo')
                    ->get()
                    ->map(function ($grupo) use ($permissaoId, $normalizarCaminho, $currentPath) {
                        $grupo->telas = DB::table('gestao_tela as t')
                            ->select('t.id', 't.nome_tela', 't.slug', 't.icone', 't.ordem')
                            ->join('vinculo_tela_x_grupo as vtg', 'vtg.tela_id', '=', 't.id')
                            ->join('vinculo_permissao_x_tela as vpt', 'vpt.tela_id', '=', 't.id')
                            ->where('vtg.grupo_id', $grupo->id)
                            ->where('vpt.permissao_id', $permissaoId)
                            ->orderBy('t.ordem')
                            ->orderBy('t.nome_tela')
                            ->distinct()
                            ->get()
                            ->map(function ($tela) use ($normalizarCaminho, $currentPath) {
                                $tela->is_active = $normalizarCaminho($tela->slug) === $normalizarCaminho($currentPath);
                                return $tela;
                            });

                        $grupo->is_open = $grupo->telas->contains(function ($tela) {
                            return $tela->is_active;
                        });

                        return $grupo;
                    });

                $telasIndividuais = DB::table('gestao_tela as t')
                    ->select('t.id', 't.nome_tela', 't.slug', 't.icone', 't.ordem')
                    ->join('vinculo_permissao_x_tela as vpt', 'vpt.tela_id', '=', 't.id')
                    ->leftJoin('vinculo_tela_x_grupo as vtg', 'vtg.tela_id', '=', 't.id')
                    ->where('t.modulo_id', $modulo->id)
                    ->where('vpt.permissao_id', $permissaoId)
                    ->whereNull('vtg.id')
                    ->orderBy('t.ordem')
                    ->orderBy('t.nome_tela')
                    ->distinct()
                    ->get()
                    ->map(function ($tela) use ($normalizarCaminho, $currentPath) {
                        $tela->is_active = $normalizarCaminho($tela->slug) === $normalizarCaminho($currentPath);
                        return $tela;
                    });

                $modulo->grupos = $grupos;
                $modulo->telas_individuais = $telasIndividuais;

                return $modulo;
            });
    }
@endphp

<aside class="main-sidebar">
    <section class="sidebar position-relative">
        <div class="multinav">
            <div class="multinav-scroll" style="height: 100%;">
                <ul class="sidebar-menu" data-widget="tree">

                    @forelse($modulosMenu as $modulo)

                        @if($modulo->id != 1)
                            <li class="header">{{ $modulo->nome_modulo }}</li>
                        @endif

                        @foreach($modulo->telas_individuais as $tela)
                            <li title="{{ $tela->nome_tela }}" class="{{ $tela->is_active ? 'active' : '' }}">
                                <a href="{{ url($tela->slug) }}">
                                    <i data-feather="{{ !empty($tela->icone) ? $tela->icone : 'circle' }}"></i>
                                    <span>{{ $tela->nome_tela }}</span>
                                </a>
                            </li>
                        @endforeach

                        @foreach($modulo->grupos as $grupo)
                            @if($grupo->telas->count() > 0)
                                <li class="treeview {{ $grupo->is_open ? 'menu-open active' : '' }}">
                                    <a href="#">
                                        <i data-feather="{{ !empty($grupo->icone) ? $grupo->icone : 'grid' }}"></i>
                                        <span>{{ $grupo->nome_grupo }}</span>
                                        <span class="pull-right-container">
                                            <i class="fa fa-angle-right pull-right"></i>
                                        </span>
                                    </a>

                                    <ul class="treeview-menu" style="{{ $grupo->is_open ? 'display: block;' : '' }}">
                                        @foreach($grupo->telas as $tela)
                                            <li class="{{ $tela->is_active ? 'active' : '' }}">
                                                <a href="{{ url($tela->slug) }}">
                                                    <i class="icon-Commit">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    {{ $tela->nome_tela }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endforeach

                    @empty
                        <li class="header">MENU</li>
                        <li>
                            <a href="javascript:void(0);">
                                <i data-feather="slash"></i>
                                <span>Nenhuma tela vinculada</span>
                            </a>
                        </li>
                    @endforelse

                </ul>
            </div>
        </div>
    </section>
</aside>
