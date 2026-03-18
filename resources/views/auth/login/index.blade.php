<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sistema para Gestão de RH">
    <meta name="author" content="My Estrategia Empresarial">
    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}">

    <title>{{ config('app.name', 'Sistema') }}</title>

    <link rel="stylesheet" href="{{ asset('assets/css/vendors_css.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/skin_color.css') }}">
</head>

<body class="hold-transition theme-primary bg-img" style="background-image: url('{{ asset('assets/images/auth-bg/bg-1.jpg') }}')">

    <div class="container h-p100">
        <div class="row align-items-center justify-content-md-center h-p100">
            <div class="col-12">
                <div class="row justify-content-center g-0">
                    <div class="col-lg-5 col-md-5 col-12">
                        <div class="bg-white rounded10 shadow-lg">
                            <div class="content-top-agile p-20 pb-0 text-center">
                                <img src="{{ asset('assets/images/sistema/logo.png') }}" alt="Logo do Sistema" style="max-width: 220px; width: 100%;">
                            </div>

                            <div class="p-40">
                                @if ($errors->any())
                                    <div class="alert alert-danger mb-3">
                                        {{ $errors->first() }}
                                    </div>
                                @endif

                                <form action="{{ route('auth.login.autenticar') }}" method="post" autocomplete="on">
                                    @csrf

                                    <input type="hidden" name="returnurl" value="{{ $returnurl }}">

                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-transparent"><i class="ti-user"></i></span>
                                            <input
                                                type="text"
                                                name="cpf"
                                                id="cpf"
                                                class="form-control ps-15 bg-transparent"
                                                placeholder="CPF"
                                                value="{{ old('cpf') }}"
                                                maxlength="14"
                                                autocomplete="username"
                                                required
                                            >
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-transparent"><i class="ti-lock"></i></span>
                                            <input
                                                type="password"
                                                name="senha"
                                                class="form-control ps-15 bg-transparent"
                                                placeholder="Senha"
                                                autocomplete="current-password"
                                            >
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="checkbox">
                                                <input
                                                    type="checkbox"
                                                    id="basic_checkbox_1"
                                                    name="lembrar"
                                                    value="1"
                                                    {{ old('lembrar') ? 'checked' : '' }}
                                                >
                                                <label for="basic_checkbox_1">Lembrar</label>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="fog-pwd text-end">
                                                <a href="{{ route('auth.recuperar') }}" class="hover-warning">
                                                    <i class="ion ion-locked"></i> Recuperar Senha
                                                </a><br>
                                            </div>
                                        </div>

                                        <div class="col-12 text-center">
                                            <button type="submit" class="waves-effect waves-light btn mb-5 bg-gradient-primary mt-10">
                                                ENTRAR
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <div class="text-center">
                                    <p class="mt-15 mb-0">
                                        Não tem uma conta?
                                        <a href="{{ route('auth.acesso') }}" class="text-warning ms-5">Clique Aqui!</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/chat-popup.js') }}"></script>
    <script src="{{ asset('assets/icons/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/toastr.js') }}"></script>
    <script src="{{ asset('assets/js/pages/notification.js') }}"></script>

    <script>
        function aplicarMascaraCPF(valor) {
            valor = valor.replace(/\D/g, '');
            valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
            valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
            valor = valor.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            return valor;
        }

        document.addEventListener('DOMContentLoaded', function () {
            const cpfInput = document.getElementById('cpf');

            if (cpfInput) {
                cpfInput.addEventListener('input', function (e) {
                    e.target.value = aplicarMascaraCPF(e.target.value);
                });

                cpfInput.value = aplicarMascaraCPF(cpfInput.value);
            }
        });
    </script>
</body>
</html>
