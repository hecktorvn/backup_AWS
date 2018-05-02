<?php
use NFePHP\MDFe\Common\Standardize;

$MDFe = new MDFe();
$xml = $MDFe->getMDFe(2, 689);

$res = $MDFe->tools->sefazConsultaRecibo('359000005853745');
$stdCl = new Standardize($res);
$stdCl->add('status', 'OK');

$std = $stdCl->toStd();
echo json_encode($std);

//$enc = $MDFe->encerrar('2018-04-30', 35, '3518800', '35180321456829000173580010000001561000001568', '935180000019675');
//var_dump($enc);

//MOSTRNADO XML
//echo $xml;

//IMPRIMINDO
//$docxml = $xml;
//$Damdfe = new Damdfe($docxml, 'P', 'A4', storage_path('app/public/logo.jpg'), 'I');
//$rt['response'] = $Damdfe->printMDFe('teste2.pdf', 'I');

//$MDFe->print();

//CONSULTANDO E EXIBINDO O RETORNO
//$send = $MDFe->enviar();
//$send->update('status', 'TESTE');
//$send = $MDFe->cancelar('Teste de cancelamento de MDFe');
//echo json_encode($send->toArray());
