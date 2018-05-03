<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use NFePHP\CTe\Make;
use NFePHP\CTe\Tools;
use NFePHP\DA\CTe\Dacte;
use NFePHP\DA\CTe\Dacce;
use NFePHP\DA\Legacy\FilesFolders;
use NFePHP\CTe\Complements;
use NFePHP\CTe\Common\Standardize;
use NFePHP\Common\Certificate;
use NFePHP\Common\Soap\SoapCurl;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Format;
use Auth;

class CteController extends Controller
{
    public $xml = false;
    public $tools = false;
    public $chave = false;
    public $conf = false;
    public $NotasnaOBS = false;
    public $uNotas = '';
    public $file = null;

    public function __construct($config=true){
        $filial = Auth::user()->getFilial();
        if($config) $this->config($filial);
    }

    private function config($data){
        $this->conf = [
            "atualizacao" => date('Y-m-d h:i:s'),
            "tpAmb" => 2,
            "razaosocial" => $data->SOCIAL,
            "cnpj" => $data->CNPJ,
            "siglaUF" => $data->UF,
            "cUF" => $data->CUF,
            "schemes" => "PL_CTe_300",
            "versao" => '3.00',
            "proxyConf" => [
                "proxyIp" => "",
                "proxyPort" => "",
                "proxyUser" => "",
                "proxyPass" => ""
            ]
        ];

        //monta o config.json
        $configJson = json_encode($this->conf);

        //carrega o conteudo do certificado.
        //$content = file_get_contents( storage_path('certificados/certificado.pfx') );
        $content = file_get_contents( storage_path('certificados/KADOSHI.pfx') );

        //intancia a classe tools
        //$this->tools = new Tools($configJson, Certificate::readPfx($content, '123456'));
        $this->tools = new Tools($configJson, Certificate::readPfx($content, 'gleydson'));
        $this->tools->model('57');
    }

    public function montaChave($cUF, $ano, $mes, $cnpj, $mod, $serie, $numero, $tpEmis, $codigo = ''){
        if ($codigo == '') {
            $codigo = $numero;
        }

        $forma = "%02d%02d%02d%s%02d%03d%09d%01d%08d";
        $chave = sprintf(
            $forma, $cUF, $ano, $mes, $cnpj, $mod,
            $serie, $numero, $tpEmis, $codigo
        );

        $this->chave = $chave . $this->calculaDV($chave);
    }

    public function calculaDV($chave43){
        $multiplicadores = [2, 3, 4, 5, 6, 7, 8, 9];
        $somaPonderada = 0;
        $iCount = 42;

        while ($iCount >= 0) {
            for ($mCount = 0; $mCount < count($multiplicadores) && $iCount >= 0; $mCount++) {
                $num = (int) substr($chave43, $iCount, 1);
                $peso = (int) $multiplicadores[$mCount];
                $somaPonderada += $num * $peso;
                $iCount--;
            }
        }

        $resto = fmod($somaPonderada, 11);
        $cDV = 11 - $resto;

        if($cDV > 9) $cDV = 0;
        return (string) $cDV;
    }

    public function enviar(){
        //Envia lote e autoriza
        $axmls[] = $this->xml;
        $chave = $this->chave;
        $lote = substr(str_replace(',', '', number_format(microtime(true) * 1000000, 0)), 0, 15);
        $res = $this->tools->sefazEnviaLote($axmls, $lote);
        $data_cte = substr($chave, 2, 4);

        //Salvando o retorno do envio do CTe
        $filename_send = "/CTe/retorno/{$data_cte}/{$chave}-env.xml";
        Storage::put($filename_send, $res);

        //Converte resposta
        $stdCl = new Standardize($res);

        //Output array
        $arr = $stdCl->toArray();

        //print_r($arr);
        //Output object
        $std = $stdCl->toStd();
        if ($std->cStat != 103) {
            //103 - Lote recebido com Sucesso
            //processa erros
            //print_r($arr);
        }

        //Consulta Recibo
        $res = $this->tools->sefazConsultaRecibo($std->infRec->nRec);
        $stdCl = new Standardize($res);
        $arr = $stdCl->toArray();
        $std = $stdCl->toStd();

        if ($std->protCTe->infProt->cStat == 100) {
            //Autorizado o uso do CT-e
            //adicionar protocolo
            $response = $this->tools->sefazConsultaChave($this->chave);

            //Salvando os dados da consulta
            $filename_con = "/CTe/retorno/{$data_cte}/{$chave}-con.xml";
            Storage::put($filename_con, $response);

            //Adicionando o protocolo do envio
            $ctefile = file_get_contents( $this->file );
            $auth = Complements::toAuthorize($ctefile, $response);

            //Salva CT-e com protocolo
            $filename = "/CTe/aprovadas/{$data_cte}/{$chave}-protcte.xml";
            Storage::put($filename, $auth);

            $arr['status'] = 'OK';
        } else {
            $arr['status'] = 'ERRO';
        }

        return $arr;
    }

    public function montaCTe($filial, $codigo){
        $data = $this->getData($filial, $codigo);

        // PARAMETROS DEFAULT
        $_cstICMSSN = 'SN';
        $_ctrc = $data['ctrc'];
        $_emitente = $data['filial'];
        $_remetente = $data['remetente'];
        $_consignatario = $data['consignatario'];
        $_destinatario = $data['destinatario'];
        $_seguradora = $data['seguradora'];
        $_veiculos = $data['veiculos'];

        //CAPTURANDO PERCENTUAIS
        $sql = "
        SELECT
            COALESCE(P.COD7,0) SERIECT,  P.STRING_03 NUMEROSERIE, COALESCE(P.ALFA5, '') SENHA,  P.COD8 FORMATO, COALESCE(P.COD9, 0) FORMAEMISSAO, COALESCE(P.STRING_04,'') LOGOMARCA,
            COALESCE(P.STRING_05,'') CAMINHO, COALESCE(P.COD6, 0) AMBIENTE, COALESCE(P.ALFA1,'08') MODELO,  P.ALFA2, COALESCE(P.ALFA3, 'Z') UNIDADE,  P.STRING_06,
            P.ALFA4, COALESCE(P.COD10, 6353) CFOPCT,  COALESCE(P.COD16,0) EMITIR, COALESCE(P.COD11, 0) SUGERIR,  COALESCE(P.COD12, 0) IMPRIMIR, COALESCE(P.COD13, 0) MINIMO,
            P.TEXTO,  COALESCE(P.COD14, 0) RECIBO, COALESCE(P.COD15, 0) REGRA,  COALESCE(P.COD17,0) NOTA,  COALESCE(P.COD18, 0) REENTREGA, COALESCE(P.VALOR1, 0) TOTIMP,
            COALESCE(P.COD19, 0) ALTERARFAT, COALESCE(P.COD20, 0) ANEXO,  COALESCE(P.COD21, 1) TAMPAPEL, COALESCE(P.VALOR2, 0) VALREDBC,  COALESCE(P.VALOR3, 0) FATOR, COALESCE(P.ALFA6, '') NBS,
            COALESCE(IB.NACIONAL, 0) NACIONAL, COALESCE(IB.ESTADUAL, 0) ESTADUAL,  COALESCE(IB.MUNICIPAL, 0) MUNICIPAL, COALESCE(COD23, 1) USAIBPT,  COALESCE(P.ALFA7, '2.00') VERSAO,  STRING_09,
            COALESCE(P.COD22,0) NOTASOBS
        FROM PARAMETROS P
            LEFT JOIN IBPT IB ON IB.NCM=P.ALFA6
        WHERE CODIGO ='CTRC_' || ?";
        $impostos = DB::select($sql, [Auth::user()->minhaFilial()])[0];

        $_UPercFed = $impostos->NACIONAL;
        $_UPercEst = $impostos->ESTADUAL;
        $_UPercMun = $impostos->MUNICIPAL;

        //VALIDANDO INFORMAÇÕES NESCESSARIAS
        if(is_null($_ctrc)) trigger_error('Código do CTe inválido ou não encontrado!');
        if(is_null($_consignatario)) trigger_error('Consignátario não encontrado! - ' . $_ctrc->CONSIGNATARIO);
        if(is_null($_emitente)) trigger_error('Emitente não encontrado! - ' . $_ctrc->EMITENTE);
        if(is_null($_remetente)) trigger_error('Remetente não encontrado! - ' . $_ctrc->REMETENTE);

        $arr = $this->conf;
        $cte = new Make();

        if(!is_null($_ctrc) && !empty($_ctrc->EMISSAO)) $dhEmi = Format::date(trim("{$_ctrc->EMISSAO} {$_ctrc->HORA_EMISSAO}"), 'Y-m-d\TH:i:s-03:00');
        else $dhEmi = date("Y-m-d\TH:i:s-03:00");

        $numeroCTE = $codigo;
        $infCte = new \stdClass();
        $infCte->Id = '';
        $infCte->versao = '3.00';

        $cte->taginfCTe($infCte);
        $this->chave = $_ctrc->NR_CTE;
        $data_cte = substr($this->chave, 2, 4);

        /*$this->montaChave(
            $_emitente->CODIGO_UF, Format::date($_ctrc->EMISSAO, 'y'),
            Format::date($_ctrc->EMISSAO, 'm'), $_emitente->CNPJ, $_ctrc->MODELO,
            $_ctrc->SERIE, $_ctrc->CODIGO, $_ctrc->TIPO
        );*/

        $cDV = substr($this->chave, -1); //Digito Verificador

        /**
         * [PREENCHENDO OS DADOS DE ACORDO COM O SELECT]
         * Preenchimento de dados do IDE do CTe
        */

        //SETANDO O VALOR DEFAULT
        $_ctrc->FINALIDADE = empty($_ctrc->FINALIDADE) ? '0' : $_ctrc->FINALIDADE;
        $_ctrc->TIPOSERVICO = empty($_ctrc->TIPOSERVICO) ? '0' : $_ctrc->TIPOSERVICO;

        $ide = new \stdClass();
        $ide->cUF = $_emitente->CODIGO_UF; // Codigo da UF da tabela do IBGE
        $ide->cMunEnv = $_emitente->COD_CIDADE; // Utilizar a tabela do IBGE. Informar 9999999 para as operações com o exterior.
        $ide->xMunEnv = $_emitente->CIDADE; // Informar PAIS/Municipio para as operações com o exterior.
        $ide->UFEnv = $_emitente->UF; // Informar 'EX' para operações com o exterior.

        $ide->cMunIni = empty($_ctrc->CID_COLETA) ? $_ctrc->COD_COLETA : $_ctrc->CID_COLETA; // Utilizar a tabela do IBGE. Informar 9999999 para as operações com o exterior.
        $ide->xMunIni = $_ctrc->COLETA; // Informar 'EXTERIOR' para operações com o exterior.
        $ide->UFIni = $this->CUFtoUF(substr($ide->cMunIni, 0, 2)); // Informar 'EX' para operações com o exterior.

        $ide->cMunFim = empty($_ctrc->CID_ENTREGA) ? $_ctrc->COD_ENTREGA : $_ctrc->CID_ENTREGA; // Utilizar a tabela do IBGE. Informar 9999999 para operações com o exterior.
        $ide->xMunFim = $_ctrc->ENTREGA; // Informar 'EXTERIOR' para operações com o exterior.
        $ide->UFFim = $this->CUFtoUF(substr($ide->cMunFim, 0, 2)); // Informar 'EX' para operações com o exterior.

        $ide->modal = '01'; // Preencher com:01-Rodoviário; 02-Aéreo; 03-Aquaviário;04-
        $ide->tpCTe = strval($_ctrc->FINALIDADE); // 0- CT-e Normal; 1 - CT-e de Complemento de Valores;
        $ide->indGlobalizado = $_ctrc->TIPO_DOC == 2 ? true : false;

        $ide->cCT = $numeroCTE; // Codigo numerico que compoe a chave de acesso
        $ide->CFOP = $_ctrc->CFOP; // Codigo fiscal de operacoes e prestacoes
        $ide->natOp = $_ctrc->NATUREZA; // Natureza da operacao

        $ide->mod = $_ctrc->MODELO; // Modelo do documento fiscal: 57 para identificação do CT-e
        $ide->serie = '1'; // Serie do CTe

        $ide->nCT = $numeroCTE; // Numero do CTe
        $ide->dhEmi = $dhEmi; // Data e hora de emissão do CT-e: Formato AAAA-MM-DDTHH:MM:DD

        $ide->tpImp = '1'; //$_ctrc->IMPRESSOES; // Formato de impressao do DACTE: 1-Retrato; 2-Paisagem.

        $ide->tpEmis = '1'; // Forma de emissao do CTe: 1-Normal; 4-EPEC pela SVC; 5-Contingência
        $ide->cDV = $cDV; // Codigo verificador
        $ide->tpAmb = '2'; // 1- Producao; 2-homologacao

        // 2 -CT-e de Anulação; 3 - CT-e Substituto
        $ide->procEmi = '0'; // Descricao no comentario acima
        $ide->verProc = '3.0'; // versao do aplicativo emissor

        //$ide->refCTE = '';             // Chave de acesso do CT-e referenciado
        $ide->modal = '01'; // Preencher com:01-Rodoviário; 02-Aéreo; 03-Aquaviário;04-
        $ide->tpServ = strval($_ctrc->TIPOSERVICO); // 0- Normal; 1- Subcontratação; 2- Redespacho;

        $ide->retira = '1'; // Indicador se o Recebedor retira no Aeroporto; Filial,
        // Porto ou Estação de Destino? 0-sim; 1-não

        $ide->xDetRetira = ''; // Detalhes da retira
        $ide->indIEToma = $_consignatario->CONDICAO_TRIBUTARIA;
        $ide->dhCont = ''; // Data e Hora da entrada em contingência; no formato AAAAMM-DDTHH:MM:SS
        $ide->xJust = '';  // Justificativa da entrada em contingência
        $cte->tagide($ide);

        // TOMAR 3
        // Indica o "papel" do tomador: 0-Remetente; 1-Expedidor; 2-Recebedor; 3-Destinatário
        $toma3 = new \stdClass();

        if($_ctrc->TIPOSERVICO == 2):
            $xconsignatario = $_ctrc->EXPEDIDOR;
            $toma3->toma = '1';
        elseif($_ctrc->TIPOSERVICO == 1):
            $xconsignatario = $_ctrc->REDESPACHO;
            $toma3->toma = '2';
        elseif($_ctrc->TIPO == 0):
            $xconsignatario = $_ctrc->REMETENTE;
            $toma3->toma = '0';
        else:
            $xconsignatario = $_ctrc->DESTINATARIO;
            $toma3->toma = '3';
        endif;

        $cte->tagtoma3($toma3);

        // TOMAR 4
        $toma4 = new \stdClass(); // DADOS DO TOMADOR
        $enderToma = new \stdClass(); // ENDERECO TOMADOR

        if($xconsignatario != $_ctrc->CONSIGNATARIO):
            $toma4->toma  = 4; // 4-Outros; informar os dados cadastrais do tomador quando ele for outros
            $toma4->xNome = $_consignatario->SOCIAL;
            $toma4->xFant = $_consignatario->FANTASIA;
            $toma4->email = $_consignatario->EMAIL;
            $toma4->fone  = preg_replace('/[^0-9]/', '', $_consignatario->TELEFONES);

            if(strlen($_consignatario->CNPJ_CPF) > 11){
                $toma4->CNPJ = trim(preg_replace('/[^0-9]/', '', $_consignatario->CNPJ_CPF));
                $toma4->CPF = '';
            } else {
                $toma4->CPF = trim(preg_replace('/[^0-9]/', '', $_consignatario->CNPJ_CPF));
                $toma4->CNPJ = '';
            }

            if($_consignatario->CONDICAO_TRIBUTARIA != 9){
                $toma4->IE = $_consignatario->CONDICAO_TRIBUTARIA == 1 ? $_consignatario->RG_INSC : 'ISENTO';
            }

            $enderToma->CEP     = preg_replace('/[^0-9]/', '', $_consignatario->CEP); // CEP
            $enderToma->xLgr    = $_consignatario->LOGRAD . ' ' . $_consignatario->ENDERECO; // Logradouro
            $enderToma->nro     = $_consignatario->NUMERO; // Numero
            $enderToma->xBairro = $_consignatario->BAIRRO; // Bairro
            $enderToma->xMun    = $_consignatario->CIDADE; // Nome do município (Informar EXTERIOR para operações com o exterior.
            $enderToma->cMun    = $_consignatario->CMUN; // Codigo do municipio do IBEGE Informar 9999999 para operações com o exterior
            $enderToma->UF      = $_consignatario->UF; // Sigla UF (Informar EX para operações com o exterior.)
            $enderToma->xPais   = $_consignatario->NMPAIS; // Nome do pais
            $enderToma->cPais   = $_consignatario->COD_PAIS; // Codigo do país ( Utilizar a tabela do BACEN )
            $enderToma->xCpl  = $_consignatario->COMPLEMENTO; // Complemento

            $cte->tagtoma4($toma4);
            $cte->tagenderToma($enderToma);
        endif;

        //EMITENTE
        $emit = new \stdClass();
        $emit->CNPJ = trim(preg_replace('/[^0-9]/', '', $_emitente->CNPJ)); // CNPJ do emitente
        $_emitente->INSC_ESTADUAL = trim(preg_replace('/[^0-9]/', '', $_emitente->INSC_ESTADUAL)); // INSCRIÇÃO ESTADUAL

        if(empty($_emitente->INSC_ESTADUAL)) $emit->IE = 'ISENTO'; // Inscricao estadual
        else $emit->IE = $_emitente->INSC_ESTADUAL; // Inscricao estadual

        $emit->IEST = ""; // Inscricao estadual
        $emit->xNome = $_emitente->SOCIAL; // Razao social
        $emit->xFant = $_emitente->FANTASIA; // Nome fantasia
        $cte->tagemit($emit);

        //ENDEREÇO EMITENTE
        $enderEmit = new \stdClass();
        $enderEmit->xLgr    = $_emitente->LOGRAD . ' ' . $_emitente->ENDERECO; // Logradouro
        $enderEmit->nro     = $_emitente->NUMERO; // Numero
        $enderEmit->xCpl    = $_emitente->COMPLEMENTO; // Complemento
        $enderEmit->xBairro = $_emitente->BAIRRO; // Bairro
        $enderEmit->cMun    = $_emitente->COD_CIDADE; // Código do município (utilizar a tabela do IBGE)
        $enderEmit->xMun    = trim($_emitente->CIDADE); // Nome do municipio
        $enderEmit->UF      = $_emitente->UF; // CEP
        $enderEmit->CEP     = preg_replace('/[^0-9]/', '', $_emitente->CEP); // Sigla UF
        $enderEmit->fone    = preg_replace('/[^0-9]/', '', $_emitente->TELEFONES); // Fone
        $cte->tagenderEmit($enderEmit);


        // REMETENTE
        $rem = new \stdClass();
        if($_remetente->COD_PAIS == 1058) $CNPJCPF = trim(preg_replace('/[^0-9]/', '', $_ctrc->REMETENTE));
        else $CNPJCPF = '00000000000000';

        $rem->CNPJ = strlen($CNPJCPF) > 11 ? $CNPJCPF : ''; // CNPJ
        $rem->CPF = strlen($CNPJCPF) <= 11 ? $CNPJCPF : ''; // CPF

        $rem->xNome = $_remetente->SOCIAL;
        $rem->xFant = $_remetente->FANTASIA; // Nome fantasia
        $rem->email = $_remetente->EMAIL; // Email
        $rem->IE = $_remetente->CONDICAO_TRIBUTARIA == 1 ? $_remetente->RG_INSC : 'ISENTO'; // Inscricao estadual
        $rem->fone = preg_replace('/[^0-9]/', '', $_remetente->TELEFONES); // Fone
        $cte->tagrem($rem);

        // ENDEREÇO DO REMETENTE
        $enderReme = new \stdClass();
        $enderReme->xLgr    = $_remetente->LOGRAD . ' ' . $_remetente->ENDERECO;
        $enderReme->nro     = $_remetente->NUMERO;
        $enderReme->xCpl    = $_remetente->COMPLEMENTO;
        $enderReme->xBairro = $_remetente->BAIRRO;
        $enderReme->cMun    = $_remetente->CMUN;
        $enderReme->xMun    = trim($_remetente->CIDADE);
        $enderReme->UF      = $_remetente->UF;
        $enderReme->fone    = preg_replace('/[^0-9]/', '', $_remetente->TELEFONES);
        $enderReme->cPais   = $_remetente->COD_PAIS;
        $enderReme->xPais   = $_remetente->NMPAIS;
        $enderReme->CEP = preg_replace('/[^0-9]/', '', $_remetente->CEP);
        $cte->tagenderReme($enderReme);


        // DESTINATÁRIO
        $dest = new \stdClass();
        if($_destinatario->COD_PAIS == 1058) $CNPJCPF = trim(preg_replace('/[^0-9]/', '', $_ctrc->DESTINATARIO));
        else $CNPJCPF = '00000000000000';

        $dest->CNPJ = strlen($CNPJCPF) > 11 ? $CNPJCPF : ''; // CNPJ
        $dest->CPF = strlen($CNPJCPF) <= 11 ? $CNPJCPF : ''; // CPF

        $dest->xNome = $_destinatario->SOCIAL;
        $dest->xFant = $_destinatario->FANTASIA; // Nome fantasia
        $dest->email = $_destinatario->EMAIL; // Email
        $dest->IE = ($_destinatario->CONDICAO_TRIBUTARIA == 1 ? $_destinatario->RG_INSC : 'ISENTO'); // Inscricao estadual
        $dest->fone = preg_replace('/[^0-9]/', '', $_destinatario->TELEFONES); // Fone
        $dest->ISUF = ''; // Inscrição na SUFRAMA
        $cte->tagdest($dest);

        // ENDEREÇO DO DESTINATARIO
        $enderDest = new \stdClass();
        $enderDest->xLgr    = $_destinatario->LOGRAD . ' ' . $_destinatario->ENDERECO;
        $enderDest->nro     = $_destinatario->NUMERO;
        $enderDest->xCpl    = $_destinatario->COMPLEMENTO;
        $enderDest->xBairro = $_destinatario->BAIRRO;
        $enderDest->cMun    = $_destinatario->CMUN;
        $enderDest->xMun    = trim($_destinatario->CIDADE);
        $enderDest->UF      = $_destinatario->UF;
        $enderDest->fone    = preg_replace('/[^0-9]/', '', $_destinatario->TELEFONES);
        $enderDest->cPais   = $_destinatario->COD_PAIS;
        $enderDest->xPais   = $_destinatario->NMPAIS;
        $enderDest->CEP = preg_replace('/[^0-9]/', '', $_destinatario->CEP);
        $cte->tagenderDest($enderDest);

        // VPREST
        $vPrest = new \stdClass();
        $vPrest->vTPrest = floatval(Format::format($_ctrc->VALOR_FRETE, 'numeric')); // Valor total da prestacao do servico

        if(empty($_ctrc->VALOR_RECEBER)) $vPrest->vRec = Format::format($_ctrc->VALOR_FRETE, 'float', 2); // Valor a receber
        else $vPrest->vRec = Format::format($_ctrc->VALOR_RECEBER, 'float', 2); // Valor a receber
        $cte->tagvPrest($vPrest);

        /*$comp = new \stdClass();
        $comp->xNome = 'FRETE VALOR'; // Nome do componente
        $comp->vComp = '3334.32';  // Valor do componente
        $cte->tagComp($comp);*/

        // IMPOSTO
        $icms = new \stdClass();

        /////acrescentado em 13/05/13 para atender a Lei n� 12.741/12////////
        /////alterado em 28/01/15 para atender as altera��es da Lei 12.741/12//////
        if(floatval($_emitente->IMPOSTO) > 0):
            $xTtIMP = floatval($_ctrc->VALOR_FRETE) * floatval($_emitente->IMPOSTO) / 100;
            $UTtFed = 0;
            $UTtEst = 0;
            $UTtMun = 0;
        else:
            $UTtFed = floatval($_ctrc->VALOR_FRETE) * $_UPercFed / 100;
            $UTtEst = floatval($_ctrc->VALOR_FRETE) * $_UPercEst / 100;
            $UTtMun = floatval($_ctrc->VALOR_FRETE) * $_UPercMun / 100;
            $xTtIMP =  $UTtFed + $UTtEst + $UTtMun;
        endif;

        if($_emitente->OPTANTE_SIMPLES == 1 || is_null($_ctrc->SITUACAO_TRIBUTARIA)):
            $icms->cst = $_cstICMSSN;
            $icms->ICMSSN = 1;
            $icms->vTotTrib = 0;
        else:
            switch($_ctrc->SITUACAO_TRIBUTARIA):
                case 0:
                    $icms->cst   = '00';
                    $icms->vBC   = floatval($_ctrc->BASE_ICMS);
                    $icms->pICMS = floatval($_ctrc->ALIQUOTA_ICMS);
                    $icms->vICMS = floatval($_ctrc->TOTAL_ICMS);
                break;
                case 20:
                    $icms->cst    = '20';
                    $icms->vBC    = floatval($_ctrc->BASE_ICMS);
                    $icms->pICMS  = floatval($_ctrc->ALIQUOTA_ICMS);
                    $icms->vICMS  = floatval($_ctrc->TOTAL_ICMS);
                    $icms->pRedBC = floatval($_ctrc->REDUCAO_ICMS);
                break;
                case 40:
                case 41:
                case 45:
                case 51:
                   switch($_ctrc->SITUACAO_TRIBUTARIA):
                      case 40: $SituTrib = '40'; break;
                      case 41: $SituTrib = '41'; break;
                      case 45: $SituTrib = '45'; break;
                      case 51: $SituTrib = '51'; break;
                   endswitch;
                   $icms->cst = $SituTrib;
                break;
                case 60:
                    $icms->cst        = '60';
                    $icms->vBCSTRet   = 0;
                    $icms->pICMSSTRet = 0;
                break;
                case 90:
                    $icms->cst = '90';
                    if($_ctrc->BASE_ICMS > 0):
                       $icms->vBC   = floatval($_ctrc->BASE_ICMS);
                       $icms->pICMS = floatval($_ctrc->ALIQUOTA_ICMS);
                       $icms->vICMS = floatval($_ctrc->TOTAL_ICMS);
                    else:
                       $icms->vBC   = 0;
                       $icms->pICMS = 0;
                       $icms->vICMS = 0;
                    endif;
                break;
            endswitch;

            if(!isset($icms->vICMSSTRet)) $icms->vICMSSTRet = 0;
            if(!isset($icms->vCred)) $icms->vCred = 0;
            //////Nota técnica 2013/005//////////////////
            $icms->vTotTrib = number_format($xTtIMP, 2);
        endif;

         ///partilha de icms - Nota t�cnica 2015/003///////
        if($_ctrc->FCPUFDEST > 0 || $_ctrc->ICMSUFDEST > 0 || $_ctrc->ICMSUFREMET > 0):
            $icms->vICMSUFFim = floatval($_ctrc->ICMSUFDEST); //xICMSPARTILHADO*pICMSInterPart/100;
            $icms->vICMSUFIni = floatval($_ctrc->ICMSUFREMET); //xICMSPARTILHADO-vICMSUFFim;
        else:
            $icms->vICMSUFFim = 0;
            $icms->vICMSUFIni = 0;
        endif;

        $cte->tagicms($icms);
        $cte->taginfCTeNorm();  // Grupo de informações do CT-e Normal e Substituto

        $infCarga = new \stdClass();
        if($_ctrc->FINALIDADE != 1):
             $infCarga->vCarga  = floatval($_ctrc->TOTAL_MERC);
             $infCarga->proPred = $_ctrc->ESPECIE;

             if(floatval($_ctrc->LARG_CUB) > 0 && floatval($_ctrc->ALT_CUB) > 0 && floatval($_ctrc->COMP_CUB) > 0 ):
             $infCarga->xOutCat = '"Medidas: ' . Format::money($_ctrc->ALT_CUB) . 'X' . Format::money($_ctrc->LARG_CUB) . 'X' . Format::money($_ctrc->COMP_CUB) . '"';
             endif;

             $infQ = new \stdClass();
             $infQ->cUnid = '03'; // Código da Unidade de Medida: ( 00-M3; 01-KG; 02-TON; 03-UNIDADE; 04-LITROS; 05-MMBTU
             $infQ->tpMed = 'VOLUMES'; // Tipo de Medida
             // ( PESO BRUTO; PESO DECLARADO; PESO CUBADO; PESO AFORADO; PESO AFERIDO; LITRAGEM; CAIXAS e etc)
             $infQ->qCarga = number_format($_ctrc->VOLUMES, 4);  // Quantidade (15 posições; sendo 11 inteiras e 4 decimais.)
             $cte->taginfQ($infQ);


             $infQ->cUnid = '01'; // Código da Unidade de Medida: ( 00-M3; 01-KG; 02-TON; 03-UNIDADE; 04-LITROS; 05-MMBTU
             $infQ->tpMed = 'PESO BRUTO'; // Tipo de Medida
             // ( PESO BRUTO; PESO DECLARADO; PESO CUBADO; PESO AFORADO; PESO AFERIDO; LITRAGEM; CAIXAS e etc)
             $infQ->qCarga = number_format($_ctrc->PESO, 4);  // Quantidade (15 posições; sendo 11 inteiras e 4 decimais.)
             $cte->taginfQ($infQ);

             if($_ctrc->CUBAGEM > $_ctrc->PESO):
                $infQ->cUnid = '01'; // Código da Unidade de Medida: ( 00-M3; 01-KG; 02-TON; 03-UNIDADE; 04-LITROS; 05-MMBTU
                $infQ->tpMed = 'PESO CUBADO'; // Tipo de Medida
                // ( PESO BRUTO; PESO DECLARADO; PESO CUBADO; PESO AFORADO; PESO AFERIDO; LITRAGEM; CAIXAS e etc)
                $infQ->qCarga = number_format($_ctrc->CUBAGEM, 4);  // Quantidade (15 posições; sendo 11 inteiras e 4 decimais.)
                $cte->taginfQ($infQ);
             endif;

             if($_ctrc->LARG_CUB > 0 && $_ctrc->ALT_CUB > 0 && $_ctrc->COMP_CUB > 0):
                 $infQ->cUnid = '00'; // Código da Unidade de Medida: ( 00-M3; 01-KG; 02-TON; 03-UNIDADE; 04-LITROS; 05-MMBTU
                 $infQ->tpMed = 'CUBAGEM'; // Tipo de Medida
                 // ( PESO BRUTO; PESO DECLARADO; PESO CUBADO; PESO AFORADO; PESO AFERIDO; LITRAGEM; CAIXAS e etc)
                 $infQ->qCarga = floatval($_ctrc->ALT_CUB) * floatval($_ctrc->LARG_CUB) * floatval($_ctrc->COMP_CUB);
                 $infQ->qCarga = number_format($infQ->qCarga, 4);  // Quantidade (15 posições; sendo 11 inteiras e 4 decimais.)
                 $cte->taginfQ($infQ);
             endif;

             //DADOS DA SEGURADORA
             if(!empty($_ctrc->SEGURADORA)):
                 $vAPOL = $_ctrc->APOLICE . ',';
                 $seg = new \stdClass();

                 while(strrpos(',', $vAPOL) > 0):
                    $seg->xSeg  = $_seguradora->DESCR;
                    $seg->nApol = substr($vAPOL, 1, strrpos(',', $vAPOL)-1);
                    $seg->vAPOL = substr($vAPOL, strlen($vAPOL)+2, strlen(vAPOL));

                    if(!empty($_ctrc->AVERBACAO) && $_ctrc->AVERBACAO != ''):
                        $seg->nAver = str_pad($_ctrc->AVERBACAO, 20, '0', STR_PAD_LEFT);
                    endif;

                    $seg->vCarga = $_ctrc->AVERBADO;
                 endwhile;
             endif;

             $infCarga->xOutCat = '';
             $infCarga->vCargaAverb = '';
             $cte->taginfCarga($infCarga);
        endif;

        $cte->taginfDoc();

        //RODO
        $rodo = new \stdClass();
        $rodo->RNTRC = $_emitente->RNTRC; //CDTab.MinhaFilialRNTRC.Value;
        $cte->tagrodo($rodo);

        if($infCte->versao < 3.00) $rodo->dPrev = $_ctrc->PREVISAO; //não existe mais essa tag na versão 3.00 do CTe

        //CIOT
        /* PARA INFORMAR O MOTORISTA
        if(!empty($_ctrc->MOTORISTA)):
            xNome = CDTab.MotoristaNOME.Value;
            CPF   = CDTab.MotoristaCPF.Value;
        endif;*/

        if(!empty($_ctrc->VEICULO) && $_ctrc->VEICULO != ''):
            $veic = new \stdClass();
            $veic->cInt    = $_veiculos->CODIGO;
            $veic->placa   = trim(preg_replace('/-/', '', $_veiculos->PLACA));
            $veic->xNome   = $_veiculos->NOME_PROP;
            $veic->CNPJ    = $_veiculos->CPF_PROP;
            $veic->CPF     = $_veiculos->CPF_PROP;
            $veic->IE      = $_veiculos->RG;
            $veic->RNTRC   = $_veiculos->RNTRC;
            $veic->UF      = $_veiculos->UF;
            $veic->capKG   = $_veiculos->CAPACIDADE;
            $veic->RENAVAM = $_veiculos->RENAVAM;
            $veic->UF      = $_veiculos->UF;

            if($veic->CNPJ == $_emitente->CNPJ) $veic->tpProp = 'P';
            else $veic->tpProp = 'T';
            // P- Próprio; T- terceiro.
            //Será próprio quando o proprietário, co- proprietário ou arrendatário do veículo for o Emitente do CT-e, caso contrário será caracterizado como de propriedade de Terceiro

            $veic->tpVeic = '0'; // 0 - Tração; 1 - Reboque
            $veic->tpCar  = '02'; // 00 - Não aplicável; 01 - Aberta; 02 - Fechada/Baú; 03 - Granelera; 04 - Porta Container; 05 - Sider Ou seja
            $veic->tpRod  = '06'; //00 - não aplicável; 01 - Truck; 02 - Toco; 03 - Cavalo Mecânico; 04 - VAN; 05 - Utilitário; 06 - Outros.
        endif;

        define('moNF011AAvulsa', '01'); // NF Modelo 01/1A e Avulsa;
        define('moNFProduto', '04'); // NF de Produtor
        if(in_array($_ctrc->FINALIDADE, [0,1,3])):
            $sql  = 'SELECT NF.*, RM.SOCIAL REMETE, DS.SOCIAL DESTINO';
            $sql .= ' FROM NOTAS_FISCAIS NF';
            $sql .= ' JOIN CLIENTE RM ON ' . ($_remetente->COD_PAIS == 1058 ? 'RM.CNPJ_CPF' : 'RM.CODIGO') . '=NF.REMETENTE';
            $sql .= ' JOIN CLIENTE DS ON ' . ($_destinatario->COD_PAIS == 1058 ? 'DS.CNPJ_CPF' : 'DS.CODIGO') . '=NF.DESTINATARIO';
            $sql .= ' WHERE NF.FILIAL=' . $_ctrc->FILIAL . ' AND NF.REMETENTE=\'' . $_ctrc->REMETENTE  . '\' AND NF.CTRC= ' . $_ctrc->CODIGO;
            $NF = DB::select($sql);

            foreach($NF as $nota):
                if(!empty($nota->CHAVE) && $nota->CHAVE != ''):
                    $infNFe = new \stdClass();
                    $infNFe->PIN = '';
                    $infNFe->dPrev = '';

                    if($this->NotasnaOBS) $this->uNotas .= $nota->NUMERO . ',';
                    $infNFe->chave = $nota->CHAVE;
                    $cte->taginfNFe($infNFe);
                else:
                    $infNF = new \stdClass();
                    $infNF->nRoma  = '';
                    $infNF->nPed   = $nota->NUMERO;
                    $infNF->nDoc   = $nota->NUMERO;
                    $infNF->serie  = $nota->SERIE;
                    $infNF->mod    = moNF011AAvulsa;

                    if(!empty($nota->CFOP)) $infNF->nCFOP = $nota->CFOP;
                    else $infNF->nCFOP  = 5202;

                    $infNF->dEmi  = $nota->EMISSAO;
                    $infNF->vBC   = floatval($nota->BASE_ICMS);
                    $infNF->vBCST = 0;
                    $infNF->vICMS = floatval($nota->VALOR_ICMS);
                    $infNF->vProd = floatval($nota->TOTAL);
                    $infNF->vNF   = floatval($nota->TOTAL);
                    $infNF->nPeso = $nota->PESO;
                    $cte->taginfNF($infNF);
                endif;
            endforeach;

            $sql ='SELECT * FROM DOC_CTRC WHERE FILIAL=' . $_ctrc->FILIAL . ' AND CTRC=' . $_ctrc->CODIGO;
            $DOC = DB::select($sql);

            foreach($DOC as $doc):
                $infOutros = new \stdClass();
                if($doc->TIPO == 99):
                    $infOutros->tpDoc = '99'; // 00 - Declaração; 10 - Dutoviário; 99 - Outros
                    $infOutros->descOutros = $doc->DESCRICAO;
                else:
                    $infOutros->tpDoc = '00'; // 00 - Declaração; 10 - Dutoviário; 99 - Outros
                    $infOutros->descOutros = '';
                endif;

                $infOutros->dPrev = '';
                $infOutros->nDoc = $doc->NUMERO;
                $infOutros->dEmi = $doc->EMISSAO;
                $infOutros->vDocFisc = $doc->VALOR;
                $cte->taginfOutros($infOutros);
            endforeach;
        endif;

        $infModal = new \stdClass();
        $infModal->versaoModal = $infCte->versao;
        $cte->taginfModal($infModal);

        //Monta CT-e
        $cte->montaCTe();
        $chave = $cte->chCTe;
        $filename = "CTe/xml/{$data_cte}/{$chave}-cte.xml";
        $this->xml = $cte->getXML();

        //Assina
        $this->xml = $this->tools->signCTe($this->xml);
        $this->saveFile($filename);

        return $this->xml;
    }

    private function saveFile($pathfile){
        Storage::put($pathfile, $this->xml);
        $this->file = storage_path('app/' . $pathfile);
    }

    public function getData($filial, $codigo){
        //SETANDO COMO DEFAULT VALORES
        $rt = [
            'remetente'=>null,
            'consignatario'=>null,
            'destinatario'=>null,
            'seguraora'=>null,
            'veiculos'=>null
        ];

        //CAPTURANDO DADOS DA FILIAL
        $qry = DB::table('FILIAIS AS F')->where('F.CODIGO', $filial);
        $qry = $qry->leftJoin('CIDADES AS C', 'C.DESCRICAO', '=', 'F.CIDADE');
        $qry = $qry->leftJoin('PARAMETROS AS P', function($join){
            $join->on('P.ALFA1', '=', 'F.UF');
            $join->where('P.CODIGO', '=', 'ESTADOS');
        });

        $qry = $qry->select('F.*', 'P.COD3 AS CODIGO_UF', 'C.CODIGO AS COD_CIDADE');
        $rt['filial'] = count($qry->get()) > 0 ? $qry->get()->toArray()[0] : null;

        //CAPTURANDO O CTRC
        $qry = DB::table('CTRC')->whereRaw('CTRC.CODIGO = ? AND CTRC.FILIAL = ?', [$codigo, $filial]);
        $qry = $qry->leftJoin('CIDADES AS cC', 'cC.DESCRICAO', '=', 'CTRC.COLETA');
        $qry = $qry->leftJoin('CIDADES AS cE', 'cE.DESCRICAO', '=', 'CTRC.ENTREGA');
        $qry = $qry->leftJoin('PARAMETROS AS P', function($join){
            $join->on('P.COD1', '=', 'CTRC.CFOP');
            $join->where('P.CODIGO', '=', 'ESTADOS');
        });

        $qry = $qry->select('P.DESCR AS NATUREZA', 'cC.CODIGO AS COD_COLETA', 'cE.CODIGO AS COD_ENTREGA', 'CTRC.*');
        $rt['ctrc'] = count($qry->get()) > 0 ? $qry->get()->toArray()[0] : null;

        if(!is_null($rt['ctrc'])){
            //CAPTURANDO O REMETENTE
            $qry = DB::table('CLIENTE AS C')->where('C.CNPJ_CPF', $rt['ctrc']->REMETENTE);
            $qry = $qry->join('CIDADES AS CD', 'CD.DESCRICAO', '=', 'C.CIDADE');
            $qry = $qry->join('PARAMETROS AS P', function($join){
                $join->where('P.CODIGO', 'PAISES');
            });

            $qry = $qry->select('C.*', 'CD.CODIGO AS CMUN', 'P.COD3 AS COD_PAIS', 'P.DESCR AS NMPAIS');
            $rt['remetente'] = count($qry->get()) > 0 ? $qry->get()->toArray()[0] : null;

            //CAPTURANDO O DESTINATARIO
            $qry = DB::table('CLIENTE AS C')->where('C.CNPJ_CPF', $rt['ctrc']->DESTINATARIO);
            $qry = $qry->join('CIDADES AS CD', 'CD.DESCRICAO', '=', 'C.CIDADE');
            $qry = $qry->join('PARAMETROS AS P', function($join){
                $join->where('P.CODIGO', 'PAISES');
            });

            $qry = $qry->select('C.*', 'CD.CODIGO AS CMUN', 'P.COD3 AS COD_PAIS', 'P.DESCR AS NMPAIS');
            $rt['destinatario'] = count($qry->get()) > 0 ? $qry->get()->toArray()[0] : null;

            //CAPTURANDO O CONSIGNATARIO
            $qry = DB::table('CLIENTE AS C')->where('C.CNPJ_CPF', $rt['ctrc']->CONSIGNATARIO);
            $qry = $qry->join('CIDADES AS CD', 'CD.DESCRICAO', '=', 'C.CIDADE');
            $qry = $qry->join('PARAMETROS AS P', function($join){
                $join->where('P.CODIGO', 'PAISES');
            });

            $qry = $qry->select('C.*', 'CD.CODIGO AS CMUN', 'P.COD3 AS COD_PAIS', 'P.DESCR AS NMPAIS');
            $rt['consignatario'] = count($qry->get()) > 0 ? $qry->get()->toArray()[0] : null;

            //CAPTURANDO SEGURADORA
            $qry = DB::table('PARAMETROS AS P')->where('P.CODIGO', 'SEGURADORAS')->where('P.COD1', $rt['ctrc']->SEGURADORA);
            $rt['seguradora'] = count($qry->get()) > 0 ? $qry->get()->toArray()[0] : null;

            //CAPTURANDO VEICULO
            $qry = DB::table('VEICULOS AS V')->where('V.PLACA', $rt['ctrc']->VEICULO);
            $rt['veiculos'] = count($qry->get()) > 0 ? $qry->get()->toArray()[0] : null;
        }

        return $rt;
    }

    public function CUFtoUF($codigo){
        $qry = DB::table('PARAMETROS')->whereRaw('CODIGO = ? AND COD3 = ?', ['ESTADOS', $codigo]);
        $rt = $qry->get(['ALFA1'])->toArray();
        $rt = count($rt) <= 0 ? '' : $rt[0]->ALFA1;

        return $rt;
    }

    public function cancelar($chave, $justificativa, $protocolo){
        $response = $this->tools->sefazCancela($chave, $justificativa, $protocolo);
        $data_cte = substr($chave, 2, 4);

        $stdCl = new Standardize($response);
        //nesse caso $std irá conter uma representação em stdClass do XML retornado
        $std = $stdCl->toStd();

        $cStat = $std->infEvento->cStat;
        if($cStat == '101' || $cStat == '135' || $cStat == '155') {
            //SUCESSO PROTOCOLAR A SOLICITAÇÂO ANTES DE GUARDAR
            $xml = Complements::toAuthorize($this->tools->lastRequest, $response);

            //grave o XML protocolado e prossiga com outras tarefas de seu aplicativo
            Storage::put("CTe/retorno/{$data_cte}/{$chave}-CancCTe-procEvento.xml", $xml);

            $std->error = false;
        } else {
            $std->error = true;
        }

        return $std;
    }

    public function print($path, $cancelado=false, $render=false){
        $xml = $path;

        if(!file_exists($xml)){
            $rt['titulo'] = 'Operação Cancelada';
            $rt['msg'] = 'Arquivo XML não encontrado!';
        } else {
            $docxml = file_get_contents($path);
            $dacte = new Dacte($docxml, 'P', 'A4', 'images/logo.jpg', 'I');
            $id = $dacte->monta('', 'A4', 'C', false, $cancelado ? 1 : 0);
            if($render != true) $rt['response'] = $dacte->printDACTE($id . '.pdf', 'I');
            else $rt['response'] = $dacte->render();
        }

        return $rt;
    }

    public function printCCe($path, $render=false){
        $xml = $path;
        $aEnd = array(
            'razao' => 'QQ Comercio e Ind. Ltda',
            'logradouro' => 'Rua vinte e um de março',
            'numero' => '200',
            'complemento' => 'sobreloja',
            'bairro' => 'Nova Onda',
            'CEP' => '99999-999',
            'municipio' => 'Onda',
            'UF' => 'MG',
            'telefone' => '33333-3333',
            'email' => 'qq@gmail.com'
        );

        if(!file_exists($xml)){
            $rt['titulo'] = 'Operação Cancelada';
            $rt['msg'] = 'Arquivo XML não encontrado!';
        } else {
            $docxml = file_get_contents($path);
            $dacce = new Dacce($docxml, 'L', 'A4', '', 'I', $aEnd);
            $id = $dacce->monta();

            if($render != true) $rt['response'] = $dacce->printDACCE($id . '.pdf', 'I');
            else $rt['response'] = $dacce->render();
        }

        return $rt;
    }

    public function correcao(array $infCorrecao, string $chave, int $nSeqEvento) : \stdClass{
        try{
            $response = $this->tools->sefazCCe($chave, $infCorrecao, $nSeqEvento);
            $stdCl = new Standardize($response);

            //nesse caso $std irá conter uma representação em stdClass do XML retornado
            $std = $stdCl->toStd();

            $cStat = $std->infEvento->cStat;
            if ($cStat == '101' || $cStat == '135' || $cStat == '155') {
              //SUCESSO PROTOCOLAR A SOLICITAÇÂO ANTES DE GUARDAR
              $xml = Complements::toAuthorize($this->tools->lastRequest, $response);
              //grave o XML protocolado e prossiga com outras tarefas de seu aplicativo
              Storage::put("CTe/cce/{$chave}-CCe-{$nSeqEvento}-procEvento.xml", $xml);
              $std->status = 'OK';
              $std->xml = $xml;
          } else {
              $std->status = 'ERRO';
              $std->msg = $std->infEvento->xMotivo;
          }

        } catch(\Exception $e) {
            $std = new \stdClass();
            $std->status = 'ERRO';
            $std->msg = $e->getMessage() . ' - ' . $e->getFile() . '(' . $e->getLine() . ').';
        }

        return $std;
    }
}
