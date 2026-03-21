@extends('layouts.app')

@section('title', 'Importar Colaboradores')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Importar Colaboradores</h4>
                </div>
                <a href="{{ asset('storage/modelos/modelo_importacao_colaboradores.xlsx') }}" class="btn btn-success">
                    Baixar Modelo
                </a>
            </div>
        </div>

        <section class="content">
            @include('configuracao.importar.partials.form')
            @include('configuracao.importar.partials.tabela_logs')
        </section>
    </div>
</div>
@endsection
