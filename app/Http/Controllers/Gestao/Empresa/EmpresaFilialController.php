Chat, agora nós vamos criar o create com as informações abaixo: 

1) Layout da página esta abaixo. 
2) Quando o usuário for digitando o CNPJ incluir a mascará de forma automatica. 
3) Se o usuário clicar na lupa, fazer a consulta na API e preencher os campos com as informações. Neste processo, verificar se porte existe se não existir criar, verificar se CNAE existe, se não existir criar, se naturza juridica existe, se não existir criar. Vericiar se cidade, estado e pais existem, se não existir criar. Enquanto consulta a API colocar um loading no lugar da lupa para evitar que usuário clique duas vezes. 
4) Quando digitar o código do porte no campo a descrição deve atualizar com a descrição correspondente. 
5) Quando digitar o código natureza no campo, a descrição deve atualizar com a descrição correspondente. 
6) No campo Código CNAE o usuário deve digitar uma subclasse colocar mascará conforme ele digita. Quando finalizar de digitar buscar a descrição e preencher o campo de descrição cnae. Quando usuário clicar em Adicionar deve salvar na tabela de vinculos e mostrar na tabela abaixo. 
7) Na tabela organizar os CNAEs por ordem de subclasse porem o cnae prioriátio deve ser o primeiro a aparecer na tela. 
8) Na tabela quando um checkbox prioritário estiver setado os outros devem ficar disabled. Só pode ter um prioritário por filial. Quando marca ou desmarca o checkbox ja atualiza no banco. 
9) Nos campos de telefone conforme usuário digita os números incluir a mascará automaticamente. 
10) No campo de cidade mostrar somente as cidades vinculadas ao estado selecionado. 
11) No campo estado mostrar somente os estados vinculados ao pais selecionado. 
12) No campo CEP conforme usuário for digitando colocar mascará. 
13) Incluir botão de salvar no final da página. 
14) Depois de Salvar direcionar para editar com o id recem salvo aberto mostrar um toastr com suceso ou com erro, se tiver erro mostrar qual é o erro. 

---
Consulta na API
# só números
curl -s https://api.opencnpj.org/00000000000000 | jq

Exemplo de Resposta: 
{
  "cnpj": "00000000000000",
  "razao_social": "EMPRESA EXEMPLO LTDA",
  "nome_fantasia": "EXEMPLO",
  "situacao_cadastral": "Ativa",
  "data_situacao_cadastral": "2000-01-01",
  "matriz_filial": "Matriz",
  "data_inicio_atividade": "2000-01-01",
  "cnae_principal": "0000000",
  "cnaes_secundarios": [
    "0000001",
    "0000002"
  ],
  "cnaes_secundarios_count": 2,
  "natureza_juridica": "Sociedade Empresária Limitada",
  "logradouro": "RUA EXEMPLO",
  "numero": "123",
  "complemento": "SALA 1",
  "bairro": "BAIRRO EXEMPLO",
  "cep": "00000000",
  "uf": "SP",
  "municipio": "SAO PAULO",
  "email": "contato@exemplo.com",
  "telefones": [
    {
      "ddd": "11",
      "numero": "900000000",
      "is_fax": false
    }
  ],
  "capital_social": "1000,00",
  "porte_empresa": "Microempresa (ME)",
  "opcao_simples": null,
  "data_opcao_simples": null,
  "opcao_mei": null,
  "data_opcao_mei": null,
  "QSA": [
    {
      "nome_socio": "SOCIO PJ EXEMPLO",
      "cnpj_cpf_socio": "00000000000000",
      "qualificacao_socio": "Sócio Pessoa Jurídica",
      "data_entrada_sociedade": "2000-01-01",
      "identificador_socio": "Pessoa Jurídica",
      "faixa_etaria": "Não se aplica"
    },
    {
      "nome_socio": "SOCIA PF EXEMPLO",
      "cnpj_cpf_socio": "***000000**",
      "qualificacao_socio": "Administrador",
      "data_entrada_sociedade": "2000-01-01",
      "identificador_socio": "Pessoa Física",
      "faixa_etaria": "31 a 40 anos"
    }
  ]
}


---
Código da Página: 

<!DOCTYPE html>
<html lang="{lingua do site}">
<head>
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="assets/images/favicon.ico">

    <title>{nome site} | {nome tela}</title>
  
	<!-- Vendors Style-->
	<link rel="stylesheet" href="assets/css/vendors_css.css">
	  
	<!-- Style-->  
	<link rel="stylesheet" href="assets/css/style.css">
	<link rel="stylesheet" href="assets/css/skin_color.css">	

</head>
<body class="hold-transition light-skin sidebar-mini theme-primary fixed">
	
<div class="wrapper">
	<div id="loader"></div>

  {Incluir aqui o arquivo de header}
  
  {Incluir menu aqui}
    
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	  <div class="container-full">
		<!-- Content Header (Page header) -->	  
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h4 class="page-title">{nome da tela}</h4>
					<div class="d-inline-block align-items-center">
						<nav>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
								<li class="breadcrumb-item">{módulo}</li>
								<li class="breadcrumb-item">{nome tela}</li>
							</ol>
						</nav>
					</div>
				</div>
			</div>
		</div>

		<!-- Main content -->
		<section class="content">
			<!-- Formulário -->
			<div class="row">
				<div class="col-12">
					<div class="box">
						<div class="box-header with-border">
						  <h4 class="box-title">Adicionar Filial</h4>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
									  <label class="form-label">CNPJ</label>
									  <div class="input-group">
										<input type="text" class="form-control" placeholder="CNPJ" required> 
										<button class="btn btn-primary btn-sm" type="button">
											<i class="fa fa-search"></i>
										</button> 
									</div>
									</div>
								</div>
								<div class="col-md-8">
									<div class="form-group">
									  <label class="form-label">Razão Social</label>
									  <input type="text" class="form-control" placeholder="Razão Social">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
									  <label class="form-label">Nome Fantasia</label>
									  <input type="text" class="form-control" placeholder="Nome Fantasia">
									</div>
								</div>
							</div>
							
							<div class="row">
								<ul class="nav nav-tabs nav-fill" role="tablist">
									<li class="nav-item"> 
										<a class="nav-link active" data-bs-toggle="tab" href="#fiscal" role="tab">
											<span>
												<i class="fa fa-institution"></i>
											</span> 
											<span class="hidden-xs-down ms-15">Fiscal</span>
										</a>
									</li>
									<li class="nav-item"> 
										<a class="nav-link" data-bs-toggle="tab" href="#contato" role="tab">
											<span>
												<i class="fa fa-phone"></i>
											</span> 
											<span class="hidden-xs-down ms-15">Contato</span>
										</a>
									</li>
									<li class="nav-item"> 
										<a class="nav-link" data-bs-toggle="tab" href="#endereco" role="tab">
											<span>
												<i class="fa fa-map"></i>
											</span> 
											<span class="hidden-xs-down ms-15">Endereço</span>
										</a>
									</li>
								</ul>
								<div class="tab-content tabcontent-border">
									<div class="tab-pane active" id="fiscal" role="tabpanel">
										<div class="p-15">
											<div class="row">
												<div class="col-md-2">
													<div class="form-group">
													  <label class="form-label">Porte</label>
													  <input type="text" class="form-control" placeholder="Código">
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
													  <label class="form-label">Descrição Porte</label>
													  <input type="text" class="form-control" placeholder="Descrição Porte" readonly>
													</div>
												</div>
												<div class="col-md-2">
													<div class="form-group">
													  <label class="form-label">Natureza Juridica</label>
													  <input type="text" class="form-control" placeholder="Código">
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
													  <label class="form-label">Descrição Natureza Juridica</label>
													  <input type="text" class="form-control" placeholder="Descrição Porte" readonly>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group">
													  <label class="form-label">Data Abertura</label>
													  <input type="date" class="form-control" placeholder="Data Abertura">
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
													  <label class="form-label">Situação</label>
														<select class="form-control select2" style="width: 100%;">
														  <option>Ativo</option>
														  <option>Inativo</option>
														</select>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
													  <label class="form-label">Tipo</label>
														<select class="form-control select2" style="width: 100%;">
														  <option>Matriz</option>
														  <option>Filial</option>
														</select>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-2">
													<div class="form-group">
													  <label class="form-label">Código CNAE</label>
													  <input type="text" class="form-control" placeholder="Código">
													</div>
												</div>
												<div class="col-md-8">
													<div class="form-group">
													  <label class="form-label">Descrição CNAE</label>
													  <input type="text" class="form-control" placeholder="Descrição CNAE" readonly>
													</div>
												</div>
												<div class="col-md-2">
													<div class="form-group">
													  <label class="form-label"></label>
													  <button type="button" class="waves-effect waves-light btn bg-gradient-success w-150">Adicionar</button>
													</div>
												</div>									
											</div>
											<div class="row">
											{tabela}
											</div>
										</div>
									</div>
									<div class="tab-pane" id="contato" role="tabpanel">
										<div class="p-15">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
													  <label class="form-label">Telefone</label>
													  <input type="text" class="form-control" placeholder="Telefone">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
													  <label class="form-label">Telefone</label>
													  <input type="text" class="form-control" placeholder="Telefone">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
													  <label class="form-label">E-mail</label>
													  <input type="text" class="form-control" placeholder="E-mail">
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane" id="endereco" role="tabpanel">
										<div class="p-15">
											<div class="row">
												<div class="col-md-10">
													<div class="form-group">
													  <label class="form-label">Logradouro</label>
													  <input type="text" class="form-control" placeholder="Logradouro">
													</div>
												</div>
												<div class="col-md-2">
													<div class="form-group">
													  <label class="form-label">Número</label>
													  <input type="text" class="form-control" placeholder="Número">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group">
													  <label class="form-label">Bairro</label>
													  <input type="text" class="form-control" placeholder="Bairro">
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
													  <label class="form-label">Cidade</label>
													<select class="form-control select2" style="width: 100%;">
													  <option selected="selected">Cidade</option>
													  <option>Lista de Cidades</option>
													</select>
													</div>
												</div>
												<div class="col-md-2">
													<div class="form-group">
													  <label class="form-label">UF</label>
													  <select class="form-control select2" style="width: 100%;">
														<option selected="selected">UF</option>
														<option>Lista de UF</option>
													  </select>
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
													  <label class="form-label">País</label>
													  <select class="form-control select2" style="width: 100%;">
														<option selected="selected">País</option>
														<option>Lista de Paises</option>
													  </select>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-9">
													<div class="form-group">
													  <label class="form-label">Complemento</label>
													  <input type="text" class="form-control" placeholder="Complemento">
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
													  <label class="form-label">CEP</label>
													  <input type="text" class="form-control" placeholder="CEP">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- /.content -->
	  
	  </div>
  </div>
  <!-- /.content-wrapper -->
 
   {Incluir footer aqui}
</div>
<!-- ./wrapper -->
	
	
	<!-- Vendor JS -->
	<script src="assets/js/vendors.min.js"></script>
	<script src="assets/js/pages/chat-popup.js"></script>
    <script src="assets/icons/feather-icons/feather.min.js"></script>	
	<script src="assets/vendor_components/sweetalert/sweetalert.min.js"></script>
    <script src="assets/vendor_components/sweetalert/jquery.sweet-alert.custom.js"></script>
	<script src="assets/js/template.js"></script>
	<script src="assets/vendor_components/select2/dist/js/select2.full.js"></script>

	<script src="assets/js/pages/advanced-form-element.js"></script>


</body>
</html>
