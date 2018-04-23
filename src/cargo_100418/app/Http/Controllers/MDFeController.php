<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use NFePHP\MDFe\Make;
use NFePHP\MDFe\Tools;

class MDFeController extends Controller
{
  public $xml = false;
  public $tools = false;
  public $chave = false;
  public $conf = false;
  public $mdfe = false;
  public function __construct(){
      $filial = Auth::user()->getFilial();
      $this->config($filial);
      $this->mdfe = new Make();
      //$this->chave = $this->mdfe->montaChave($cUF, $ano, $mes, $cnpj, $mod, $serie, $numero, $tpEmis, $codigo);
      $chave = montaChave(
          $this->conf['cUF'], date('y', strtotime($dhEmi)), date('m', strtotime($dhEmi)), $this->conf['cnpj'], $this->tools->model(), '1', $numeroMDFE, '1', $numeroMDFE
      );
  }
  private function config($data){
    $this->conf = [
        "atualizacao" => date('Y-m-d h:i:s'),
        "tpAmb" => 2,
        "razaosocial" => $data->SOCIAL,
        "cnpj" => $data->CNPJ,
        "cUF" => $data->CUF,
        "siglaUF" => $data->UF,
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
    $this->tools->model('58');
  }

  public function montaMDFe($filial, $codigo){
    $chave = $this->chave;
    $resp = $this->mdfe->taginfMDFe($chave, $versao = '3.00');
    $cDV = substr($chave, -1);
    $resp = $this->mdfe->tagide(
            $cUF = '31',
            $tpAmb = '2',
            $tpEmit = '1',
            $tpTransp = '1',
            $mod,
            $serie,
            $nMDF = $numeroMDFe,
            $cMDF = $codigo,
            $cDV,
            $modal = '1',
            $dhEmi,
            $tpEmis,
            $procEmi = '0',
            $verProc = '2.0',
            $ufIni = 'MG',
            $ufFim = 'DF',
            $dhIniViagem = '2017-12-12T10:24:00-03:00'
        );
    $resp = $this->mdfe->tagInfMunCarrega(
        $cMunCarrega = '3106200',
        $xMunCarrega = 'BELO HORIZONTE'
    );
    $resp = $this->mdfe->tagInfPercurso($ufPer = 'GO');
    $resp = $this->mdfe->tagemit(
            $cnpj = '09204054000143',
            $numIE = '0010526120088',
            $xNome = 'NOME DO CLIENTE',
            $xFant = 'FANTASIA'
        );
    $resp = $this->mdfe->tagenderEmit(
            $xLgr = 'R. ONTINENTINO',
            $nro = '1313',
            $xCpl = '',
            $xBairro = 'CAICARAS',
            $cMun = '3106200',
            $xMun = 'Belo Horizonte',
            $cep = '30770180',
            $siglaUF = 'MG',
            $fone = '31988998899',
            $email = 'email@hotmail.com'
        );
    $resp = $this->mdfe->tagInfMunDescarga(
            $nItem = 0,
            $cMunDescarga = '5300108',
            $xMunDescarga = 'BRASILIA'
        );
    $resp = $this->mdfe->tagInfCTe(
            $nItem = 0,
            $chCTe = '31171009204054000143570010000015441090704345',
            $segCodBarra = ''
        );
    $resp = $this->mdfe->tagSeg(
            $nApol = '1321321321',
            $nAver = $numeroMDFe
        );
    $resp = $this->mdfe->tagInfResp(
            $respSeg = '1',
            $CNPJ = '',
            $CPF = ''
        );
    $resp = $this->mdfe->tagInfSeg(
            $xSeg = 'SOMPRO',
            $CNPJ = '11095658000140'
        );
    $resp = $this->mdfe->tagTot(
            $qCTe = '1',
            $qNFe = '',
            $qMDFe = '',
            $vCarga = '157620.00',
            $cUnid = '01',
            $qCarga = '2323.0000'
        );
    $resp = $this->mdfe->tagautXML(
            $cnpj = '',
            $cpf = '09835787667'
        );
    $resp = $this->mdfe->taginfAdic(
            $infAdFisco = 'Inf. Fisco',
            $infCpl = 'Inf. Complementar do contribuinte'
        );
    $resp = $this->mdfe->tagInfModal($versaoModal = '3.00');
    $resp = $this->mdfe->tagRodo(
            $rntrc = '10167059'
        );
    $resp = $this->mdfe->tagInfContratante(
            $CPF = '09835783624'
        );
    $resp = $this->mdfe->tagCondutor(
            $xNome = 'fjaklsdjksdjf faksdj',
            $cpf = '31199690898'
        );
    $resp = $this->mdfe->tagVeicTracao(
            $cInt = '', // Código Interno do Veículo
            $placa = 'ABC1234', // Placa do veículo
            $tara = '10000',
            $capKG = '500',
            $capM3 = '60',
            $tpRod = '06',
            $tpCar = '02',
            $UF = 'MG',
            $propRNTRC = ''
        );
    $resp = $this->mdfe->montaMDFe();
  }

  public function getData($filial, $codigo){
      //SETANDO COMO DEFAULT VALORES
      $rt = [
          'remetente'=>null,
          'origem'=>null,
          'destino'=>null,
          'seguradora'=>null,
          'rota'=>null,
          'veiculo'=>null,
          'carreta'=>null
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
}
