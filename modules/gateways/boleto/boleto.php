<?php

require("../../../init.php");
$whmcs->load_function('gateway');
$whmcs->load_function('client');

$gwresult = mysql_query("SELECT * FROM tblpaymentgateways WHERE gateway='boleto'");
while($data = mysql_fetch_array($gwresult)) {
	$gVgwsetting = $data["setting"];
	$gVgwvalue = $data["value"];
	$GATEWAY["$gVgwsetting"]="$gVgwvalue";
}

$query = "SELECT * FROM tblinvoices WHERE id='$invoiceid'";
$result=mysql_query($query);
$data = mysql_fetch_array($result);
$id = $data["id"];
$userid = $data["userid"];
$date = $data["date"];
$duedate = $data["duedate"];
$subtotal = $data["subtotal"];
$credit = $data["credit"];
$tax = $data["tax"];
$taxrate = $data["taxrate"];
$total = $data["total"];

//if ((($_SESSION["uid"]!=$userid)OR(!$id))AND(!$_SESSION['adminid'])) {
//	echo "Invalid Access Attempt";
//	exit;
//}

$clientsdetails = getClientsDetails($userid);

$year = substr($duedate,0,4);
$month = substr($duedate,5,2);
$day = substr($duedate,8,2);

$banco = $GATEWAY["banco"];

$dias_de_prazo_para_pagamento = 0; // No need for this, since the due date will be the same as the invoice's
$taxa_boleto = $GATEWAY["taxa"]; // FIELD NAME IN ADMIN: Taxa do boleto
$data_venc = date("d/m/Y",mktime(0,0,0,$month,$day,$year));  // It has to be the same as the invoice due date
$valor_cobrado = $total;
$valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, '.', '');

$dadosboleto["nosso_numero"] = $invoiceid;  // It's the variable nosso_numero for all banks
$dadosboleto["numero_documento"] = $invoiceid;
$dadosboleto["data_vencimento"] = $data_venc;
$dadosboleto["data_documento"] = date("d/m/Y");
$dadosboleto["data_processamento"] = date("d/m/Y");
$dadosboleto["valor_boleto"] = $valor_boleto;

// DADOS DO SEU CLIENTE
$dadosboleto["sacado"] = $clientsdetails["firstname"]." ".$clientsdetails["lastname"]." - CPF/CNPJ: ".$clientsdetails["customfields1"]; 
$dadosboleto["endereco1"] = $clientsdetails["address1"]." - ".$clientsdetails["address2"];
$dadosboleto["endereco2"] = $clientsdetails["city"]."/".$clientsdetails["state"]." - CEP: ".$clientsdetails["postcode"]; 

// INFORMACOES PARA O CLIENTE
// The information below needs to be configurable in the admin, it's some optional information for client's receipt (top portion of the boleto and intructions for cashier in the boleto itself.
$dadosboleto["demonstrativo1"] = $GATEWAY["demonstrativo1"];   // FIELD DESCRIPTION: Linha 1 do Recibo do Sacado
$dadosboleto["demonstrativo2"] = $GATEWAY["demonstrativo2"]." ".$invoiceid;   // FIELD DESCRIPTION: Linha 2 do Recibo do Sacado
$dadosboleto["demonstrativo3"] = $GATEWAY["demonstrativo3"];  // FIELD DESCRIPTION: Linha 3 do Recibo do Sacado
$dadosboleto["instrucoes1"] = $GATEWAY["instrucoes1"];  // FIELD DESCRIPTION: Linha 1 das Instruções do Boleto
$dadosboleto["instrucoes2"] = $GATEWAY["instrucoes2"];  // FIELD DESCRIPTION: Linha 2 das Instruções do Boleto
$dadosboleto["instrucoes3"] = $GATEWAY["instrucoes3"];  // FIELD DESCRIPTION: Linha 3 das Instruções do Boleto
$dadosboleto["instrucoes4"] = $GATEWAY["instrucoes4"];  // FIELD DESCRIPTION: Linha 4 das Instruções do Boleto

// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["quantidade"] = "";
$dadosboleto["valor_unitario"] = "";
$dadosboleto["aceite"] = $GATEWAY["aceite"]; // FIELD NAME IN ADMIN: Aceite (SIM ou NÃO)		
$dadosboleto["uso_banco"] = ""; 	
$dadosboleto["especie"] = $GATEWAY["especie"];
$dadosboleto["especie_doc"] = $GATEWAY["especie_doc"]; // FIELD NAME IN ADMIN: Espécie Doc
$dadosboleto["carteira"] = $GATEWAY["carteira"];  // Each bank has its own. Needs to be configurable. FIELD NAME IN ADMIN: Carteira

// DADOS DA SUA CONTA
// This will be used for all banks
$dadosboleto["agencia"] = $GATEWAY["agencia"]; // FIELD NAME IN ADMIN: Agência (sem o dígito)
$dadosboleto["conta"] = $GATEWAY["conta_cedente"]; 	// FIELD NAME IN ADMIN: Nº da conta (sem o dígito)
$dadosboleto["conta_cedente_dv"] = $GATEWAY["conta_cedente_dv"]; // FIELD NAME IN ADMIN: Dígito da conta 

// DADOS PERSONALIZADOS - Personalized fields for each bank
// BANCO DO BRASIL - boleto_bb.php
$dadosboleto["convenio"] = $GATEWAY["convenio"];  // FIELD NAME IN ADMIN: Nº do Convênio (6, 7 ou 8 dígitos)
$dadosboleto["contrato"] = $GATEWAY["contrato"]; // FIELD NAME IN ADMIN: Nº do seu contrato
$dadosboleto["variacao_carteira"] = $GATEWAY["variacao_carteira"];  // FIELD NAME IN ADMIN: Variação da Carteira com traço (opcional)
// TIPO DO BOLETO
$dadosboleto["formatacao_convenio"] = $GATEWAY["formatacao_convenio"]; // // FIELD NAME IN ADMIN: Formatação do Convênio (8 p/Convênio c/8 dígitos, 7 p/Convênio c/7 dígitos, 6 p/Convênio c/6 dígitos)
$dadosboleto["formatacao_nosso_numero"] = $GATEWAY["formatacao_nosso_numero"]; // FIELD NAME IN ADMIN: Formatação do Nosso Número (Apenas p/Convênio c/6 dígitos: informe 1 para Nosso Número de até 5 dígitos ou 2 para Nosso Número de até 17 dígitos)

// DADOS PERSONALIZADOS - BANESTES - boleto_banestes.php 
$dadosboleto["tipo_cobranca"] = $GATEWAY["tipo_cobranca"];  // FIELD NAME IN ADMIN: Tipo de cobrança (2- Sem registro; 3- Caucionada; 4,5,6 e 7- Com registro)

// DADOS PERSONALIZADOS - BRADESCO - boleto_bradesco.php
$dadosboleto["agencia_dv"] = $GATEWAY["agencia_dv"]; // FIELD NAME IN ADMIN: Dígito da Agência
$dadosboleto["conta_cedente"] = $GATEWAY["conta_cedente"]; // Same as $dadosboleto["conta"] = $GATEWAY["conta_cedente"];
$dadosboleto["conta_dv"] = $GATEWAY["conta_cedente_dv"]; 	// Same as  $dadosboleto["conta_cedente_dv"] = $GATEWAY["conta_cedente_dv"];

// DADOS PERSONALIZADOS - CEF - boleto_cef.php
$dadosboleto["conta_cedente"] = $GATEWAY["conta_cedente"]; // Same as $dadosboleto["conta"] = $GATEWAY["conta_cedente"];
$dadosboleto["conta_cedente_dv"] = $GATEWAY["conta_cedente_dv"]; // Same as $dadosboleto["conta_dv"] = $GATEWAY["conta_cedente_dv"];
$dadosboleto["inicio_nosso_numero"] = $GATEWAY["inicio_nosso_numero"];  // It's not the invoice ID. It's the variable $dadosboleto["inicio_nosso_numero"] = "80"; in boleto_cef.php AND this is for CEF only - FIELD NAME IN ADMIN: Início do Nosso Número (CEF somente) Carteira CR: 80, 81 ou 82  - Carteira SR: 90

// Composição Nosso Numero - CEF SIGCB - boleto_cef_sigcb.php
$dadosboleto["nosso_numero1"] = "000"; // tamanho 3
$dadosboleto["nosso_numero_const1"] = "2"; //constanto 1 , 1=registrada , 2=sem registro
$dadosboleto["nosso_numero2"] = "000"; // tamanho 3
$dadosboleto["nosso_numero_const2"] = "4"; //constanto 2 , 4=emitido pelo proprio cliente
$dadosboleto["nosso_numero3"] = "000000019"; // tamanho 9

// DADOS PERSONALIZADOS - HSBC - boleto_hsbc.php
$dadosboleto["codigo_cedente"] = $GATEWAY["convenio"]; // FIELD NAME IN ADMIN: Código do Cedente (Somente 7 digitos) = $GATEWAY["convenio"];

// DADOS PERSONALIZADOS - NOSSA CAIXA - boleto_nossacaixa.php 
$dadosboleto["conta_cedente"] = $GATEWAY["conta_cedente"];  // Same as $dadosboleto["conta"] = $GATEWAY["conta_cedente"]; 
$dadosboleto["conta_cedente_dv"] = $GATEWAY["conta_cedente_dv"]; // Same as $dadosboleto["conta_dv"] = $GATEWAY["conta_cedente_dv"];
$dadosboleto["modalidade_conta"] = $GATEWAY["modalidade_conta"];  // FIELD NAME IN ADMIN: Modalidade da conta

// DADOS PERSONALIZADOS - SANTANDER BANESPA - boleto_santander_banespa.php
$dadosboleto["codigo_cliente"] = $GATEWAY["convenio"]; // FIELD NAME IN ADMIN: Código do Cedente = $GATEWAY["conta"];
$dadosboleto["ponto_venda"] = $GATEWAY["agencia"]; // FIELD NAME IN ADMIN: Ponto de Venda = Agência = $GATEWAY["agencia"];
$dadosboleto["carteira_descricao"] = $GATEWAY["carteira"]." - ".$GATEWAY["carteira_descricao"];  // FIELD NAME IN ADMIN: Descrição da Carteira = $GATEWAY["carteira_descricao"];

// DADOS PERSONALIZADOS - UNIBANCO - boleto_unibanco.php
$dadosboleto["codigo_cliente"] = $GATEWAY["convenio"]; // FIELD NAME IN ADMIN: Código do Cedente = $GATEWAY["convenio"];

// SEUS DADOS
$dadosboleto["identificacao"] = $GATEWAY["empresanome"]; // This could be the same variable for the admin company's name. It's the page title for the generated boleto
$dadosboleto["logoempresa"] = $GATEWAY['url_logo'];
$dadosboleto["cpf_cnpj"] = $GATEWAY["cpf_cnpj"];  // No need for this
$dadosboleto["endereco"] = $GATEWAY["endereco"]; // No need for this
$dadosboleto["cidade_uf"] = $GATEWAY["cidade_uf"]; // No need for this
$dadosboleto["cedente"] = $GATEWAY["empresanomerazao"];  // FIELD NAME IN ADMIN: Cedente (it needs to be configured in the admin)

require("$banco.php");

?>