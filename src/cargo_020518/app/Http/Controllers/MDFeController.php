<?php
namespace App\Http\Controllers;

use DB;
use Illuminate\Support\Facades\Storage;
use NFePHP\DA\MDFe\Damdfe;
use NFePHP\MDFe\Complements;
use NFePHP\MDFe\Common\Standardize;
use NFePHP\Common\DOMImproved as Dom;
use NFePHP\Common\Exception;
use NFePHP\Common\UFList;


class MDFeController extends NFeController{
    public $mdfe = false;
    private $manifesto = null;

    public function __construct($config=true){
        $this->start_confg($config, 'MDFe');
        $this->tools->model('58');
        $this->mdfe = $this->make;
    }

    public function montaMDFe($filial, $codigo) : string{
        date_default_timezone_set('America/Sao_Paulo');
        $data = $this->getData($filial, $codigo);
        $mdfe = $data['manifesto'];
        $chave = $mdfe->NR_MDFE ?? null;
        $cDV = substr($chave, -1, 1);

        $cUF = UFList::getCodeByUF($mdfe->UF_ORIGEM); // 41-PR, 42-SC
        $tpAmb = $this->conf['tpAmb'];  // 1-Producao(versao fiscal), 2-Homologacao(versao de teste)
        $mod = $this->make->mod;  // Modelo do documento fiscal: 58 para identificação do MDF-e
        $serie = 1;   // Serie do MDFe
        $tpEmis = 1;  // Forma de emissao do MDFe: 1-Normal; 2- Contingencia
        $numeroMDFe = $mdfe->MANIFESTO;
        $cMDF = str_pad($mdfe->MANIFESTO, 8, '0', STR_PAD_LEFT);
        $dhEmi = date('Y-m-d\TH:i:sP', strtotime($mdfe->DATACAD));

        //INICIANDO O MDFe
        $resp = $this->mdfe->taginfMDFe($chave, $versao = '3.00');
        $resp = $this->mdfe->tagide(
            $cUF, // Codigo da UF da tabela do IBGE: 41-PR
            $tpAmb, // 1-Producao(versao fiscal), 2-Homologacao(versao de teste)
            $tpEmit = 1, // 1-Prestador de serviço de transporte 2-Transportador de Carga Própria
            $mod, // Modelo do documento fiscal: 58 para identificação do MDF-e
            $serie, // Serie do MDFe
            $nMDF = $numeroMDFe, // Numero do Manifesto
            $cMDF, // Código aleatório gerado pelo emitente
            $cDV, // Digito Verificador
            $modal = 1,  // 1-Rodoviário; 2-Aéreo; 3-Aquaviário; 4-Ferroviário.
            $dhEmi, // Data e hora de emissão do Manifesto (Formato AAAA-MM-DDTHH:MM:DD TZD)
            $tpEmis = 1, // 1-Normal; 2-Contingência
            $procEmi = 0, // 0-Aplicativo do Contribuinte; 3-Aplicativo fornecido pelo Fisco
            $verProc = 1, // Informar a versão do aplicativo emissor de MDF-e.
            $UFIni = $mdfe->UF_ORIGEM, // Sigla da UF do Carregamento
            $UFFim = $mdfe->UF // Sigla da UF do Descarregamento
        );

        $resp = $this->mdfe->tagInfMunCarrega(
            $cMunCarrega = $mdfe->COD_CIDADE_ORIGEM,
            $xMunCarrega = $mdfe->CIDADE_ORIGEM
        );

        foreach($data['uf_rota'] as $uf_perc){
            if($uf_perc->UF == $mdfe->UF_ORIGEM || $uf_perc->UF == $mdfe->UF) continue;
            $resp = $this->mdfe->tagInfPercurso($uf_perc->UF);
        }

        $resp = $this->mdfe->tagemit(
            $data['emitente']->CNPJ, //CNPJ DO EMITENTE
            preg_replace('/[^0-9]/', '', $data['emitente']->INSC_ESTADUAL), //INSCRIÇÃO ESTADUAL DO EMITENTE
            $data['emitente']->SOCIAL, //SOCIAL DO EMITENTE
            $data['emitente']->FANTASIA // FANTASIA DO EMITENTE
        );

        $resp = $this->mdfe->tagenderEmit(
            $data['emitente']->ENDERECO_X, //LOGRADOURO DO EMITENTE
            $data['emitente']->NUMERO, //NUMERO DO ENDEREÇO
            '', //$data['emitente']->COMPLEMENTO, //COMPLEMENTO
            $data['emitente']->BAIRRO, //BAIRRO
            $data['emitente']->COD_CIDADE, //CODIGO DA CIDADE
            $data['emitente']->CIDADE, //CIDADE
            preg_replace('/[^0-9]/', '', $data['emitente']->CEP), // CEP
            $data['emitente']->UF, //ESTADO
            preg_replace('/[^0-9]/', '', $data['emitente']->TELEFONES), //TELEFONE
            $data['emitente']->EMAIL //EMAIL
        );

        $resp = $this->mdfe->tagInfMunDescarga(
            $nItem = 0,
            $cMunDescarga = $mdfe->COD_CIDADE_DESTINO, //CODIGO DA CIDADE
            $xMunDescarga = $mdfe->CIDADE //NOME DA CIDADE
        );

        foreach($data['seguro'] as $seguro){
            $RESP = preg_replace('/[^0-9]/', '', $seguro->RESPONSAVEL);
            $resp = $this->mdfe->tagSeg(
                $respSeg = $seguro->TIPO == 0 ? 1 : 2,
                $CNPJ = strlen($RESP) > 11 ? $RESP :  '',
                $CPF = strlen($RESP) <= 11 ? $RESP :  '',
                $nApol = $seguro->APOLICE,
                $nAver = $seguro->AVERBACAO,
                $xSeg = $seguro->NMSEGURADORA,
                $CNPJSeg = $seguro->SEGURADORA
            );
        }

        $resp = $this->mdfe->tagTot(
            $qCTe = $data['tot']->CTE,
            $qNFe = '',
            $qMDFe = '',
            $vCarga = $data['tot']->MERCADORIA,
            $cUnid = '01', // 01 – KG / 02 - TON
            $qCarga = number_format($data['tot']->PESO_TOTAL, 4, '.', '')
        );

        $resp = $this->mdfe->tagautXML(
            $cnpj = '40790883000103',
            $cpf = ''
        );

        $resp = $this->mdfe->tagautXML(
            $cnpj = $data['emitente']->CNPJ,
            $cpf = ''
        );

        $resp = $this->mdfe->taginfAdic(
            $infAdFisco = '',//'Inf. Fisco',
            $infCpl = $mdfe->OBSERVACAO
        );

        $resp = $this->mdfe->tagInfModal($versaoModal = '3.00');
        $resp = $this->mdfe->tagRodo(
            $rntrc = $data['veiculo']->RNTRC
        );

        $this->chave = $mdfe->NR_MDFE;
        foreach($data['cte'] as $cte){
            $resp = $this->mdfe->tagInfCTe(
                $nItem = 0,
                $chCTe = $cte->NR_CTE,
                $segCodBarra = ''
            );

            $resp = $this->mdfe->tagInfContratante(
                $CPF = strlen($cte->CONSIGNATARIO) <= 11 ? $cte->CONSIGNATARIO : '',
                $CNPJ = strlen($cte->CONSIGNATARIO) > 11 ? $cte->CONSIGNATARIO : ''
            );
        }

        $resp = $this->mdfe->tagCondutor(
            $xNome = $data['motorista']->NOME,
            $cpf = $data['motorista']->CPF
        );

        $resp = $this->mdfe->tagProp(
            $CPF = $data['motorista']->CPF,
            $RNTRC = $data['veiculo']->RNTRC,
            $xNome = $data['motorista']->NOME,
            $IE = $data['motorista']->IDENTIDADE,
            $UF = $data['motorista']->UF,
            $tpProp = 2
        );

        $resp = $this->mdfe->tagVeicTracao(
            $cInt = $data['veiculo']->CODIGO, // Código Interno do Veículo
            $placa = preg_replace('/[^A-Za-z0-9]/', '', $data['veiculo']->PLACA), // Placa do veículo
            $tara = $data['veiculo']->CAPACIDADE_KG,
            $capKG = $data['veiculo']->CAPACIDADE_KG,
            $capM3 = $data['veiculo']->CAPACIDADE,
            $tpRod = '06', // 01- Truck;   02 - Toco;   03 - Cavalo Mecânico;   04 - VAN;   05 - Utilitário;   06 - Outros.
            $tpCar = '02', // 00 - não aplicável;   01 - Aberta;   02 - Fechada/Baú;   03 - Granelera;   04 - Porta Container;   05 - Sider
            $UF = $data['veiculo']->UF,
            $propRNTRC = $data['veiculo']->RNTRC
        );

        $resp = $this->mdfe->montaMDFe();
        $this->xml = $this->mdfe->xml;
        $this->xml = $this->tools->assina($this->xml);
        $this->tools->validarXml($this->conf['versao'], $this->xml, 'mdfe');

        $dt_mdfe = substr($this->chave, 2, 4);
        Storage::put("MDFe/xml/{$dt_mdfe}/{$this->chave}-mdfe.xml", $this->xml);
        return $this->xml;
    }

    public function getData($filial, $codigo){
        //SETANDO COMO DEFAULT VALORES
        $rt = [
            'manifesto' => null,
            'motorista' => null,
            'emitente' => null,
            'uf_rota' => null,
            'veiculo' => null,
            'seguro' => null,
            'rota' => null,
            'cte' => null,
            'tot' => null,
        ];

        //CAPTURANDO O MANIFESTO
        $manifesto = DB::table('MANIFESTO AS M')->where(['M.FILIAL'=>$filial, 'M.MANIFESTO'=>$codigo])->orWhere('M.FILIAL', $filial)->where('M.CODIGO', $codigo);
        $manifesto = $manifesto->join('CIDADES AS CO', function($join){
            $join->on(['CO.ESTADO'=>'M.UF_ORIGEM', 'CO.DESCRICAO'=>'M.CIDADE_ORIGEM']);
        });
        $manifesto = $manifesto->join('CIDADES AS CD', function($join){
            $join->on(['CD.ESTADO'=>'M.UF', 'CD.DESCRICAO'=>'M.CIDADE']);
        });

        //EXECUTANDO O QUERY
        $qry = DB::raw('M.*, CO.CODIGO COD_CIDADE_ORIGEM, CD.CODIGO COD_CIDADE_DESTINO');
        $rt['manifesto'] = $manifesto->select($qry)->get()->toArray();
        $rt['manifesto'] = $rt['manifesto'][0] ?? null;
        $this->manifesto = $rt['manifesto'];

        if(is_null($rt['manifesto'])) throw new \Exception('MDFe não encontrada');

        //CAPTURANDO O EMITENTE
        $emitente = DB::table('FILIAIS AS F')->where('F.CODIGO', $rt['manifesto']->FILIAL);
        $emitente = $emitente->join('CIDADES AS C', function($join){
            $join->on(['C.ESTADO'=>'F.UF', 'C.DESCRICAO'=>'F.CIDADE']);
        });

        $qry = DB::raw('F.*, C.CODIGO COD_CIDADE, F.LOGRAD || \' \' || F.ENDERECO ENDERECO_X');
        $rt['emitente'] = $emitente->select($qry)->get()->toArray()[0] ?? null;
        if(is_null($rt['manifesto'])) throw new \Exception('MDFe não encontrada');

        //CAPTURANDO A SEGURADORA e EXECUTANDO QUERY
        $seguro = DB::table('MDFE_SEGURO AS MS')->where(['MS.FILIAL'=>$rt['manifesto']->FILIAL, 'MS.MANIFESTO'=>$rt['manifesto']->MANIFESTO]);
        $seguro= $seguro->join('SEGURADORA AS S', 'S.CNPJ', '=', 'MS.SEGURADORA');
        $seguro = $seguro->select('MS.*', 'S.SOCIAL AS NMSEGURADORA')->get()->toArray();
        $rt['seguro'] = $seguro ?? [];

        //CAPTURANDO A ROTA e EXECUTANDO QUERY
        $rota = DB::table('ROTAS')->where(['FILIAL'=>$rt['manifesto']->FILIAL, 'CODIGO'=>$rt['manifesto']->ROTA]);
        $rota = $rota->get()->toArray();
        $rt['rota'] = $rota[0] ?? ['UF' => ''];

        $rt['uf_rota'] = [];
        //CAPTURANDO OS ESTADOS DA ROTA
        foreach(explode(',', $rt['rota']->UF) as $cUF){
            $uf = DB::table('PARAMETROS')->where(['COD3' => $cUF, 'CODIGO' => 'ESTADOS']);
            $uf = $uf->select('ALFA1 AS UF')->get()->toArray();
            if($uf) $rt['uf_rota'][] = (object) ['UF' => $uf[0]->UF];
        }

        // /var_dump($rt['uf_rota']);

        //CAPTURANDO OS CTe's
        $cte = DB::table('MDFE_CTE AS M')->where(['M.FILIAL'=>$rt['manifesto']->FILIAL, 'M.MDFE'=>$rt['manifesto']->MANIFESTO]);
        $cte = $cte->join('CTRC AS C', 'C.CODIGO', '=', 'M.CTE');
        $rt['cte'] = $cte->select('C.*')->get()->toArray();
        $query = DB::raw('COUNT(*) CTE, SUM(C.TOTAL_MERC) MERCADORIA, SUM(C.PESO) PESO_TOTAL');
        $rt['tot'] = $cte->select($query)->get()->toArray()[0] ?? [];

        //CAPTURANDO O VEICULO USADO
        $veiculo = DB::table('VEICULOS AS V')->where(['V.PLACA'=>$rt['manifesto']->VEICULO]);
        $rt['veiculo'] = $veiculo->select('V.*')->get()->toArray()[0] ?? [];

        //CAPTURANDO O VEICULO USADO
        $motorista = DB::table('MOTORISTA AS M')->where(['M.CPF'=>$rt['manifesto']->MOTORISTA]);
        $rt['motorista'] = $motorista->select('M.*')->get()->toArray()[0] ?? [];

        return $rt;
    }

    public function getMDFe($filial, $codigo, $data=false) : string{
        if($data) $this->getData($filial, $codigo);
        $mdfe = DB::table('MANIFESTO')->where(['FILIAL'=>$filial, 'MANIFESTO'=>$codigo])->get()->toArray();
        $mdfe = $mdfe[0] ?? false;

        if($mdfe){
            $this->chave = $mdfe->NR_MDFE;
            $dt_mdfe = substr($this->chave, 2, 4);

            $path = "MDFe/aprovadas/{$dt_mdfe}/{$this->chave}-mdfe.xml";
            if(Storage::exists($path)){
                $this->xml = Storage::get($path);
            } else {
                $path = "MDFe/xml/{$dt_mdfe}/{$this->chave}-mdfe.xml";
                if(Storage::exists($path)){
                    $this->xml = Storage::get($path);
                } else {
                    $this->montaMDFe($filial, $codigo);
                    $this->setProtocolo();
                }
            }
        } else  throw new \Exception('MDFe não encontrado!');

        //echo $path;
        return $this->xml;
    }

    public function enviar() : Standardize{
        //Envia lote e autoriza
        $axmls[] = $this->xml;
        $chave = $this->chave;
        $lote = substr(str_replace(',', '', number_format(microtime(true) * 1000000, 0)), 0, 15);
        $res = $this->tools->sefazEnviaLote($axmls, $lote);
        $data_cte = substr($chave, 2, 4);

        //Salvando o retorno do envio do CTe
        $filename_send = "/MDFe/retorno/{$data_cte}/{$chave}-env.xml";
        Storage::put($filename_send, $res);

        //Converte resposta
        $stdCl = new Standardize($res);

        //Output array
        $arr = $stdCl->toArray();
        $std= $stdCl->toStd();

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
        $std = $stdCl->toStd();

        if (isset($std->protMDFe) && $std->protMDFe->infProt->cStat == 100) {
            $this->setProtocolo($std);
            $stdCl->add('status', 'OK');
        } else {
            if(isset($std->protMDFe) && $std->protMDFe->infProt->cStat == "204"){
                $this->setProtocolo($std);
            }

            //$arr['response'] = $arr;
            $stdCl->add('msg', $std->protMDFe->infProt->xMotivo ?? $std->xMotivo);
            $stdCl->add('status', 'ERRO');
        }

        return $stdCl;
    }

    public function setProtocolo(\stdClass $std) : Bool{
        $chave = $this->chave;
        $data_cte = substr($chave, 2, 4);
        $cStat = [100, 104, 103, 135];

        //Autorizado o uso do MDFe
        //adicionar protocolo
        $response = $this->consultar($chave, $std, $arr);
        if(!in_array($std->cStat, $cStat)) return false;

        //Salvando os dados da consulta
        $filename_con = "/MDFe/retorno/{$data_cte}/{$chave}-con.xml";
        Storage::put($filename_con, $response);

        //Adicionando o protocolo do envio
        $mdfefile = $this->xml;
        $auth = Complements::toAuthorize($mdfefile, $response);

        //Salva MDFe com protocolo
        $filename = "/MDFe/aprovadas/{$data_cte}/{$chave}-mdfe.xml";
        Storage::put($filename, $auth);
        return true;
    }

    public function consultar($chave=null, &$std=null, &$arr=null) : String{
        $response = $this->tools->sefazConsultaChave($chave ?? $this->chave, 2);
        $stdCl = new Standardize($response);
        $arr = $stdCl->toArray();
        $std = $stdCl->toStd();

        $arr['msg'] = $std->xMotivo;
        if($std->cStat == 100) $arr['status'] = 'OK';
        else $arr['status'] = 'ERRO';

        return $response;
    }

    public function cancelar(String $motivo, $chave=null) : Standardize{
        //CAPTURANDO O PROTOCOLO DO XML E VALIDANDO O TIPO DE XML GERADO
        $docmdfe = new Dom();
        $docmdfe->loadXMLString($this->xml);
        $nodemdfe = $docmdfe->getNode('MDFe', 0);
        $data_mdfe = substr($this->chave, 2, 4);

        if ($nodemdfe == '') {
            $msg = "O arquivo indicado como MDFe não é um xml de MDFe!";
            throw new Exception\RuntimeException($msg);
        } else {
            $protMDFe = $docmdfe->getNode('protMDFe');
            if ($protMDFe == '') {
                $msg = "O MDFe não está protocolado ainda!!";
                throw new Exception\RuntimeException($msg);
            } else {
                $protocolo = $protMDFe->getElementsByTagName('nProt')->item(0)->nodeValue;
            }
        }

        //CANCELANDO O MANIFESTO
        $xmlCancelamento = $this->tools->sefazCancela($chave ?? $this->chave, $motivo, $protocolo);

        //Converte resposta
        $stdCl = new Standardize($xmlCancelamento);
        $std = $stdCl->toStd();

        if($std->infEvento->cStat == 135){
            //APOS O CANCELAMENTO ADICIONO A TAG DE CANCELAMENTO E ARMAZENO
            $newXml = $this->tools->addCancelamento($this->xml, $xmlCancelamento);
            Storage::put('MDFe/canceladas/{$data_mdfe}/' . $this->chave . '-mdfe.xml', $newXml);
            $stdCl->add('status', 'OK');
        } else {
            $stdCl->add('status', 'ERRO');
            $stdCl->add('msg', $std->infEvento->xMotivo);
        }

        return $stdCl;
    }

    public function encerrar($dtEnc=null, $cUF=null, $cMun=null, $chave=null, $nProt=null) : Standardize{
        $dtEnc = $dtEnc ?? date('Y/m/d\tH:i:sP');
        $cUF   = $cUF ?? substr($this->manifesto->COD_CIDADE_DESTINO, 0, 2);
        $cMun  = $cMun ?? $this->manifesto->COD_CIDADE_DESTINO;
        $nProt = $nProt ?? $this->manifesto->PROTOCOLO;
        $chave = $chave ?? $this->chave;

        $response = $this->tools->sefazEncerra($chave, $nProt, $dtEnc, $cUF, $cMun);

        //Converte resposta
        $stdCl = new Standardize($response);
        $std = $stdCl->toStd();

        if(isset($std->infEvento) && $std->infEvento->cStat == 135){
            //APOS O CANCELAMENTO ADICIONO A TAG DE CANCELAMENTO E ARMAZENO
            $stdCl->add('status', 'OK');
            $stdCl->add('msg', $std->infEvento->xMotivo);
        } else {
            $stdCl->add('status', 'ERRO');
            $stdCl->add('msg', $std->infEvento->xMotivo);
        }

        return $stdCl;
    }

    public function print(){
        $docxml = $this->xml;
        $file_name = 'LogoMDFe_002.jpg';
        $logomarca = DB::table('IMAGENS')->where('CODIGO', $file_name);
        $logomarca = $logomarca->get()->toArray();
        $logomarca = $logomarca[0]->IMAGEM ?? '';

        $path = storage_path('app/MDFe/logos/' . $file_name);
        Storage::put('MDFe/logos/' . $file_name, $logomarca);
        $Damdfe = new Damdfe($docxml, 'P', 'A4', $path, 'I');
        return $Damdfe->printMDFe(uniqid() . '.pdf', 'I');
    }
}
