<?php

function boleto_config() {

    $configarray = array(
     "FriendlyName" => array("Type" => "System", "Value"=>"Boleto Santander"),
     "banco" => array("FriendlyName" => "Banco", "Type" => "dropdown", "Options" => "Boleto Santander"),
     "taxa" => array("FriendlyName" => "Taxa", "Type" => "text", "Size" => "10"),
    "agencia" => array("FriendlyName" => "Agência", "Type" => "text", "Size" => "20", "Description" => "Agência (sem o dígito) com 4 dígitos (Somente Números)."),
	 "agencia_dv" => array("FriendlyName" => "Dígito da Agência", "Type" => "text", "Size" => "20", "Value" =>"0", "Description" => "Dígito da Agência (Somente Números) Padrão 0."),
	 "conta" => array("FriendlyName" => "Conta", "Type" => "text", "Size" => "20", "Description" => "Nº da conta, sem traços (Somente Números)."),
     "conta_cedente" => array("FriendlyName" => "Conta Cedente", "Type" => "text", "Size" => "20", "Description" => "Nº da conta (sem o dígito) (Somente Números)."),
     "conta_cedente_dv" => array("FriendlyName" => "Conta Cedente DV", "Type" => "text", "Size" => "20", "Description" => "Dígito da conta que foi informado no campo anterior (Somente Números)."),
     "convenio" => array("FriendlyName" => "Convênio", "Type" => "text", "Size" => "20", "Description" => "Mantenha em branco, caso seu banco não informe como deve preencher."),
     "contrato" => array("FriendlyName" => "Contrato", "Type" => "text", "Size" => "20", "Description" => "Mantenha em branco, caso seu banco não informe como deve preencher."),
	 "carteira" => array("FriendlyName" => "Carteira", "Type" => "text", "Size" => "20", "Description" => "Ex: 101, 102, 157, 158, 175 Dentre outros."),
	 "carteira_descricao" => array("FriendlyName" => "Descrição da Carteira", "Type" => "text", "Value" =>"COBRANÇA SIMPLES - CSR", "Size" => "20", "Description" => "COBRANÇA SIMPLES - CSR, RÁPIDA REGISTRO - ECR, etc"),
	 "aceite" => array("FriendlyName" => "Aceite", "Type" => "text", "Value" =>"NÃO", "Size" => "20", "Description" => "Aceite (SIM ou NÃO)"),
	 "especie" => array("FriendlyName" => "Especie", "Type" => "text", "Value" =>"R$", "Size" => "20", "Description" => "R$ ou REAL"),
	 "especie_doc" => array("FriendlyName" => "Espécie do Documento", "Type" => "text", "Value" =>"DM", "Size" => "20", "Description" => "DM, DMI, DS, DSI, DR, LC, NCC, NCE, NCI, NCR, NP, TM, TS, NS, RC, FT, ND, AP, ME, PC, CN, DD, WA, TK, CH, ND, OUT"),
	 "variacao_carteira" => array("FriendlyName" => "Variação da Carteira", "Type" => "text", "Value" =>"-019", "Size" => "20", "Description" => "APENAS PARA BANCO DO BRASIL, Variação da Carteira com traço (opcional)Padrão -019 "),
	 "formatacao_convenio" => array("FriendlyName" => "Formatação do Convênio", "Type" => "text", "Value" =>"7", "Size" => "20", "Description" => "Formatação do Convênio (8 p/Convênio c/8 dígitos, 7 p/Convênio c/7 dígitos, 6 p/Convênio c/6 dígitos). Padrão 7"),
	 "formatacao_nosso_numero" => array("FriendlyName" => "Formatação do Nosso Número", "Type" => "text", "Value" =>"2", "Size" => "20", "Description" => "Formatação do Nosso Número (Apenas p/Convênio c/6 dígitos: informe 1 para Nosso Número de até 5 dígitos ou 2 para Nosso Número de até 17 dígitos). Padrão 2"),
	 "tipo_cobranca" => array("FriendlyName" => "Tipo de Cobrança", "Type" => "text", "Value" =>"2", "Size" => "20", "Description" => "Tipo de cobrança (2- Sem registro; 3- Caucionada; 4,5,6 e 7- Com registro). Padrão 2"),
	 "modalidade_conta" => array("FriendlyName" => "Modalidade da Conta", "Type" => "text", "Value" =>"04", "Size" => "20", "Description" => "Modalidade da conta Padrão 04"),
	 "inicio_nosso_numero" => array("FriendlyName" => "Início do Nosso Número", "Type" => "text", "Value" =>"80", "Size" => "20", "Description" => "Início do Nosso Número (CEF somente) Carteira CR: 80, 81 ou 82  - Carteira SR: 90 Padrão 80"),
	 //"prz_venc" => array("FriendlyName" => "Prazo vencimento Dias", "Type" => "text",  "Size" => "2", "Description" => "Digite O prazo para vencimento em dias."),
	 "empresanomerazao" => array("FriendlyName" => "Razão Social", "Type" => "text", "Size" => "30"),	 
	 "empresanome" => array("FriendlyName" => "Nome Fantasia", "Type" => "text", "Size" => "30"),
	 "endereco" => array("FriendlyName" => "Endereço", "Type" => "text", "Size" => "30", "Description" => "Ex: Rua Minha rua, xxx - Meu Bairro"),
	 "cidade_uf" => array("FriendlyName" => "Cidade/Estado e CEP", "Type" => "text", "Size" => "30", "Description" => "Ex: Cidade/XX - CEP: XXXXX-XXX"),
	 "cpf_cnpj" => array("FriendlyName" => "CPF ou CNPJ", "Type" => "text", "Size" => "30", "Description" => "Ex: xxx.xxx.xxx-xx ou xx.xxx.xxx/xxxx-xx"),
	 "demonstrativo1" => array("FriendlyName" => "Demonstrativo Linha 1", "Type" => "text", "Size" => "30", "Description" => "Ex: ==============================================================="),
	 "demonstrativo2" => array("FriendlyName" => "Demonstrativo Linha 2", "Type" => "text", "Size" => "30", "Description" => "Ex: Nome Fantasia da Minha Empresa - Fatura Nº"),
	 "demonstrativo3" => array("FriendlyName" => "Demonstrativo Linha 3", "Type" => "text", "Size" => "30", "Description" => "Ex: ==============================================================="),
	 "instrucoes1" => array("FriendlyName" => "Instruções Linha 1", "Type" => "text", "Size" => "30", "Description" => "Ex: - Sr. Caixa, não receber após o vencimento"),
	 "instrucoes2" => array("FriendlyName" => "Instruções Linha 2", "Type" => "text", "Size" => "30", "Description" => "Ex: - Tel: (0xxXX) XXXX-XXXX"),
	 "instrucoes3" => array("FriendlyName" => "Instruções Linha 3", "Type" => "text", "Size" => "30", "Description" => "Ex: - Suporte: (0xxXX) XXXX-XXXX"),
	 "instrucoes4" => array("FriendlyName" => "Instruções Linha 4", "Type" => "text", "Size" => "30", "Description" => "Ex: - boleto@meusite.com.br - http://www.meusite.com.br/"),
     "url_logo" => array("FriendlyName" => "Url da Logomarca*", "Type" => "text", "Size" => "50", "Description" => "Insira aqui a url da imagem que deseja que apareça como logomarca no boleto."),	 
    );

	return $configarray;

}

function boleto_link($params) {

	$code = '<input type="button" value="'.$params['langpaynow'].'" onClick="window.location=\''.$params['systemurl'].'/modules/gateways/boleto/boleto.php?invoiceid='.$params['invoiceid'].'\'" />';

	return $code;

}

?>