@extends('layouts.app')

@section('title', 'Cristalcopo - Nova Filial')

@section('content')
<div class="content-wrapper">
    <div class="container-full">

        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Nova Filial</h4>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">
                                    <i class="mdi mdi-home-outline"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item">Empresa</li>
                            <li class="breadcrumb-item active">Filiais</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="content">
            <form method="POST" action="{{ route('empresa.filiais.store') }}" id="form-filial">
                @csrf

                <div class="box">
                    <div class="box-header">
                        <h4 class="box-title">Adicionar Filial</h4>
                    </div>

                    <div class="box-body">

                        {{-- CNPJ + API --}}
                        <div class="row">
                            <div class="col-md-4">
                                <label>CNPJ</label>
                                <div class="input-group">
                                    <input type="text" name="cnpj" id="cnpj" class="form-control" required>
                                    <button type="button" id="btn-consulta-cnpj" class="btn btn-primary">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <label>Razão Social</label>
                                <input type="text" name="razao_social" id="razao_social" class="form-control">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label>Nome Fantasia</label>
                                <input type="text" name="nome_fantasia" id="nome_fantasia" class="form-control">
                            </div>
                        </div>

                        {{-- Tabs --}}
                        <div class="row mt-4">
                            <ul class="nav nav-tabs nav-fill">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#fiscal">Fiscal</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#contato">Contato</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#endereco">Endereço</a>
                                </li>
                            </ul>

                            <div class="tab-content">

                                {{-- FISCAL --}}
                                <div class="tab-pane active p-3" id="fiscal">

                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>Porte</label>
                                            <input type="text" id="porte_codigo" class="form-control">
                                            <input type="hidden" name="porte_id" id="porte_id">
                                        </div>

                                        <div class="col-md-4">
                                            <label>Descrição Porte</label>
                                            <input type="text" id="porte_descricao" class="form-control" readonly>
                                        </div>

                                        <div class="col-md-2">
                                            <label>Natureza</label>
                                            <input type="text" id="natureza_codigo" class="form-control">
                                            <input type="hidden" name="natureza_juridica_id" id="natureza_id">
                                        </div>

                                        <div class="col-md-4">
                                            <label>Descrição</label>
                                            <input type="text" id="natureza_descricao" class="form-control" readonly>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-4">
                                            <label>Data Abertura</label>
                                            <input type="date" name="data_abertura" id="data_abertura" class="form-control">
                                        </div>

                                        <div class="col-md-4">
                                            <label>Situação</label>
                                            <select name="situacao" class="form-control">
                                                <option value="1">Ativo</option>
                                                <option value="0">Inativo</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label>Tipo</label>
                                            <select name="tipo" class="form-control">
                                                <option value="matriz">Matriz</option>
                                                <option value="filial">Filial</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                {{-- CONTATO --}}
                                <div class="tab-pane p-3" id="contato">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Telefone 1</label>
                                            <input type="text" name="telefone1" id="telefone1" class="form-control">
                                        </div>

                                        <div class="col-md-6">
                                            <label>Telefone 2</label>
                                            <input type="text" name="telefone2" id="telefone2" class="form-control">
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label>Email</label>
                                            <input type="text" name="email" id="email" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                {{-- ENDEREÇO --}}
                                <div class="tab-pane p-3" id="endereco">

                                    <div class="row">
                                        <div class="col-md-10">
                                            <label>Logradouro</label>
                                            <input type="text" name="logradouro" id="logradouro" class="form-control">
                                        </div>

                                        <div class="col-md-2">
                                            <label>Número</label>
                                            <input type="text" name="numero" id="numero" class="form-control">
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-4">
                                            <label>Bairro</label>
                                            <input type="text" name="bairro" id="bairro" class="form-control">
                                        </div>

                                        <div class="col-md-3">
                                            <label>Cidade</label>
                                            <select name="cidade_id" id="cidade_id" class="form-control"></select>
                                        </div>

                                        <div class="col-md-2">
                                            <label>Estado</label>
                                            <select name="estado_id" id="estado_id" class="form-control"></select>
                                        </div>

                                        <div class="col-md-3">
                                            <label>País</label>
                                            <select name="pais_id" id="pais_id" class="form-control">
                                                @foreach($paises as $pais)
                                                    <option value="{{ $pais->id }}">{{ $pais->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-9">
                                            <label>Complemento</label>
                                            <input type="text" name="complemento" class="form-control">
                                        </div>

                                        <div class="col-md-3">
                                            <label>CEP</label>
                                            <input type="text" name="cep" id="cep" class="form-control">
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="box-footer text-end">
                        <button type="submit" class="btn btn-success">Salvar</button>
                    </div>

                </div>
            </form>
        </section>
    </div>
</div>
@endsection
