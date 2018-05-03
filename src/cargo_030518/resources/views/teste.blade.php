<?php
use App\Http\Controllers\PrintController;

$print = new PrintController('A4', 'L');
$print->DefaultPage('Extrato de Cargas');

//DADOS PARA PRINTAR O TABLE
$body = [];
$header = [
    'DOC' => 'Doc',
    'CODIGO' => 'Código',
    'DATACAD' => 'Emissão',
    'REMETENTE' => 'Remetente',
    'DESTINATARIO' => 'Destinatário',
    'COLETA' => 'Coleta',
    'ENTREGA' => 'Entrega',
    'TIPO' => 'Tipo Fre',
    'PREVISAO' => 'Previsão',
    'PESO' => 'Peso',
    'VOLUMES' => 'Volumes',
    'MERCADORIA' => 'Mercadoria'
];

for($i=0;$i<400;$i++){
    $body[] = [
        'DOC'=>'CTe',
        'CODIGO'=>1568,
        'DATACAD'=>'03/05/18',
        'REMETENTE'=>'JOAO ANTONIO ROMEO',
        'DESTINATARIO'=>'JOAO ANTONIO ROMEO',
        'COLETA'=>'SAO PAULO',
        'ENTREGA'=>'SAO PAULO',
        'TIPO'=>'FOB',
        'PREVISAO'=>'18/04/18',
        'PESO'=>'0,00',
        'VOLUMES'=>'1',
        'MERCADORIA'=>'0,00'
    ];
}

$print->ConfigTable = [
    'Zebra' => true,
    'Border' => false,
    'AlignTable' => [1=>'R', 9=>'R', 10=>'R', 11=>'R'],
    'Size' => [9, 12, 15, 60, 60, 25, 25, 10, 15, 20, 20, 20]
];

$print->Table($header, $body);
$print->Print();
