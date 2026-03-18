@php
    $usuario = auth()->user();

    $fotoUsuario = !empty($usuario?->foto)
        ? asset($usuario->foto)
        : asset('assets/images/avatar/avatar-1.png');
@endphp

<header class="main-header">
    <div class="d-flex align-items-center logo-box justify-content-start">
        <a href="{{ url('/auth/redirect') }}" class="logo">
            <div class="logo-mini w-30">
                <span class="light-logo">
                    <img src="{{ asset('assets/images/logo-letter.png') }}" alt="logo">
                </span>
                <span class="dark-logo">
                    <img src="{{ asset('assets/images/logo-letter.png') }}" alt="logo">
                </span>
            </div>

            <div class="logo-lg">
                <span class="light-logo">
                    <img src="{{ asset('assets/images/logo-dark-text.png') }}" alt="logo">
                </span>
                <span class="dark-logo">
                    <img src="{{ asset('assets/images/logo-light-text.png') }}" alt="logo">
                </span>
            </div>
        </a>
    </div>

    <nav class="navbar navbar-static-top">
        <div class="app-menu">
            <ul class="header-megamenu nav">
                <li class="btn-group nav-item">
                    <a href="#"
                       class="waves-effect waves-light nav-link push-btn btn-outline no-border btn-primary-light"
                       data-toggle="push-menu"
                       role="button">
                        <i data-feather="align-left"></i>
                    </a>
                </li>
            </ul>
        </div>

        <div class="navbar-custom-menu r-side">
            <ul class="nav navbar-nav">
                <li class="dropdown notifications-menu btn-group">
                    <label class="switch">
                        <a class="waves-effect waves-light btn-outline no-border nav-link svg-bt-icon btn-info-light">
                            <input type="checkbox" data-mainsidebarskin="toggle" id="toggle_left_sidebar_skin">
                            <span class="switch-on">
                                <i data-feather="moon"></i>
                            </span>
                            <span class="switch-off">
                                <i data-feather="sun"></i>
                            </span>
                        </a>
                    </label>
                </li>

                <li class="btn-group nav-item d-lg-inline-flex d-none">
                    <a href="#"
                       data-provide="fullscreen"
                       class="waves-effect waves-light nav-link btn-outline no-border full-screen btn-warning-light"
                       title="Full Screen">
                        <i data-feather="maximize"></i>
                    </a>
                </li>

                <li class="dropdown user user-menu">
                    <a href="#"
                       class="waves-effect waves-light dropdown-toggle no-border p-5"
                       data-bs-toggle="dropdown"
                       title="User">
                        <img class="avatar avatar-pill" src="{{ $fotoUsuario }}" alt="Usuário">
                    </a>

                    <ul class="dropdown-menu animated flipInX">
                        <li class="user-body">
                            <a class="dropdown-item" href="{{ url('/colaborador/perfil') }}">
                                <i class="ti-user text-faded me-2"></i> Perfil
                            </a>

                            <a class="dropdown-item" href="{{ url('/colaborador/configuracao') }}">
                                <i class="ti-settings text-faded me-2"></i> Configuração
                            </a>

                            <div class="dropdown-divider"></div>

                           <a class="dropdown-item" href="{{ url('/auth/logout') }}">
                                <i class="ti-lock text-faded me-2"></i> Sair
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
