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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>

<body class="hold-transition theme-primary bg-img" style="background-image: url('{{ asset('assets/images/auth-bg/bg-2.jpg') }}')">

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
                                <form action="{{ route('auth.acesso.registrar') }}" method="post" id="form-primeiro-acesso" autocomplete="off">
                                    @csrf

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
                                                required
                                            >
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-transparent"><i class="ti-calendar"></i></span>
                                            <input
                                                type="text"
                                                name="data_nascimento"
                                                id="data_nascimento"
                                                class="form-control ps-15 bg-transparent"
                                                placeholder="Data Nascimento"
                                                value="{{ old('data_nascimento') }}"
                                                maxlength="10"
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
                                                id="senha"
                                                class="form-control ps-15 bg-transparent"
                                                placeholder="Senha"
                                                disabled
                                                required
                                            >
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-transparent"><i class="ti-lock"></i></span>
                                            <input
                                                type="password"
                                                name="senha_confirmacao"
                                                id="senha_confirmacao"
                                                class="form-control ps-15 bg-transparent"
                                                placeholder="Confirmar Senha"
                                                disabled
                                                required
                                            >
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <button type="submit" class="waves-effect waves-light btn bg-gradient-primary margin-top-10" id="btn-registrar" disabled>
                                                REGISTRAR
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <div class="text-center">
                                    <p class="mt-15 mb-0">
                                        Ja possui uma conta?
                                        <a href="{{ route('auth.login') }}" class="text-danger ms-5">Acesse aqui!</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/chat-popup.js') }}"></script>
    <script src="{{ asset('assets/icons/feather-icons/feather.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        let identidadeValidada = false;
        let ultimaChaveValidada = '';

        function somenteNumeros(valor) {
            return valor.replace(/\D/g, '');
        }

        function aplicarMascaraCPF(valor) {
            valor = somenteNumeros(valor);
            valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
            valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
            valor = valor.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            return valor;
        }

        function aplicarMascaraData(valor) {
            valor = somenteNumeros(valor);
            valor = valor.replace(/(\d{2})(\d)/, '$1/$2');
            valor = valor.replace(/(\d{2})(\d)/, '$1/$2');
            return valor.substring(0, 10);
        }

        function limparFormularioSenha() {
            $('#senha').val('');
            $('#senha_confirmacao').val('');
        }

        function desabilitarSenha() {
            identidadeValidada = false;
            $('#senha').prop('disabled', true);
            $('#senha_confirmacao').prop('disabled', true);
            $('#btn-registrar').prop('disabled', true);
            limparFormularioSenha();
        }

        function habilitarSenha() {
            identidadeValidada = true;
            $('#senha').prop('disabled', false);
            $('#senha_confirmacao').prop('disabled', false);
            $('#btn-registrar').prop('disabled', false);
        }

        function limparTelaIdentificacao() {
            $('#cpf').val('');
            $('#data_nascimento').val('');
            ultimaChaveValidada = '';
            desabilitarSenha();
            $('#cpf').focus();
        }

        function cpfCompleto() {
            return somenteNumeros($('#cpf').val()).length === 11;
        }

        function dataCompleta() {
            return $('#data_nascimento').val().length === 10;
        }

        function chaveAtual() {
            return $('#cpf').val() + '|' + $('#data_nascimento').val();
        }

        function validarIdentidade() {
            if (!cpfCompleto() || !dataCompleta()) {
                desabilitarSenha();
                return;
            }

            const chave = chaveAtual();

            if (chave === ultimaChaveValidada && identidadeValidada) {
                return;
            }

            $.ajax({
                url: "{{ route('auth.acesso.validar') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    cpf: $('#cpf').val(),
                    data_nascimento: $('#data_nascimento').val()
                },
                success: function(response) {
                    ultimaChaveValidada = chave;
                    habilitarSenha();
                },
                error: function(xhr) {
                    let mensagem = 'Não foi possível validar os dados informados.';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        mensagem = xhr.responseJSON.message;
                    }

                    limparTelaIdentificacao();
                    toastr.error(mensagem);
                }
            });
        }

        $(document).ready(function () {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-right',
                timeOut: '5000',
                extendedTimeOut: '1500',
                preventDuplicates: true
            };

            @if ($errors->any())
                @foreach ($errors->all() as $erro)
                    toastr.error(@json($erro));
                @endforeach
            @endif

            @if (session('success'))
                toastr.success(@json(session('success')));
            @endif

            @if (session('error'))
                toastr.error(@json(session('error')));
            @endif

            @if (session('warning'))
                toastr.warning(@json(session('warning')));
            @endif

            @if (session('info'))
                toastr.info(@json(session('info')));
            @endif

            $('#cpf').on('input', function () {
                $(this).val(aplicarMascaraCPF($(this).val()));
                ultimaChaveValidada = '';
                desabilitarSenha();
            });

            $('#data_nascimento').on('input', function () {
                $(this).val(aplicarMascaraData($(this).val()));
                ultimaChaveValidada = '';
                desabilitarSenha();
            });

            $('#cpf, #data_nascimento').on('blur', function () {
                validarIdentidade();
            });

            $('#form-primeiro-acesso').on('submit', function (e) {
                if (!identidadeValidada) {
                    e.preventDefault();
                    toastr.error('Valide o CPF e a data de nascimento antes de continuar.');
                    return;
                }

                const senha = $('#senha').val();
                const confirmacao = $('#senha_confirmacao').val();

                if (!senha || !confirmacao) {
                    e.preventDefault();
                    toastr.error('Informe e confirme a senha.');
                    return;
                }

                if (senha !== confirmacao) {
                    e.preventDefault();
                    toastr.error('As senhas não correspondem.');
                    return;
                }
            });
        });
    </script>
</body>
</html>
