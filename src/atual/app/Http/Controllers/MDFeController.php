<?php
namespace App\Http\Controllers;

use DB;
use NFePHP\Common\UFList;

class MDFeController extends NFeController{
    public $mdfe = false;
    public function __construct($config=true){
        $this->start_confg($config, 'MDFe');
        $this->tools->model('58');
        $this->mdfe = $this->make;
    }

    public function montaMDFe($filial, $codigo){
        $data = $this->getData($filial, $codigo);
        $mdfe = $data['manifesto'];
        $chave = $mdfe->NR_MDFE ?? null;
        $cDV = substr($chave, -2, 1);

        $cUF = UFList::getCodeByUF($mdfe->UF_ORIGEM); // 41-PR, 42-SC
        $tpAmb = $this->conf['tpAmb'];  // 1-Producao(versao fiscal), 2-Homologacao(versao de teste)
        $mod = $this->make->mod;  // Modelo do documento fiscal: 58 para identificação do MDF-e
        $serie = 1;   // Serie do MDFe
        $tpEmis = 1;  // Forma de emissao do MDFe: 1-Normal; 2- Contingencia
        $numeroMDFe = $mdfe->MANIFESTO;
        $cMDF = $mdfe->CODIGO;
        //return $mdfe->DATACAD;

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
            $dhEmi  = $mdfe->DATACAD, // Data e hora de emissão do Manifesto (Formato AAAA-MM-DDTHH:MM:DD TZD)
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
            $data['emitente']->COMPLEMENTO, //COMPLEMENTO
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

        $resp = $this->mdfe->tagInfCTe(
            $nItem = 0,
            $chCTe = $chave,
            $segCodBarra = ''
        );

        foreach($data['seguro'] as $seguro){
            $resp = $this->mdfe->tagSeg(
                $nApol = $seguro->APOLICE,
                $nAver = $seguro->AVERBACAO
            );

            $RESP = preg_replace('/[^0-9]/', '', $seguro->RESPONSAVEL);
            $resp = $this->mdfe->tagInfResp(
                $respSeg = $seguro->SEQUENCIA,
                $CNPJ = strlen($RESP) > 11 ? $RESP :  '',
                $CPF = strlen($RESP) <= 11 ? $RESP :  ''
            );

            $resp = $this->mdfe->tagInfSeg(
                $xSeg = 'SOMPRO',
                $CNPJ = $seguro->SEGURADORA
            );
        }

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

        /*$resp = $this->mdfe->taginfAdic(
            $infAdFisco = 'Inf. Fisco',
            $infCpl = 'Inf. Complementar do contribuinte'
        );*/

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
        $this->xml = $this->mdfe->xml;
        $this->xml = $this->tools->assina($this->xml);
        return $this->xml;
    }

    public function getData($filial, $codigo){
        //SETANDO COMO DEFAULT VALORES
        $rt = [
            'manifesto' => null,
            'emitente' => null,
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
        $qry = DB::raw('M.*, CO.CODIGO COD_CIDADE_ORIGEM, CO.CODIGO COD_CIDADE_DESTINO');
        $rt['manifesto'] = $manifesto->select($qry)->get()->toArray();
        $rt['manifesto'] = $rt['manifesto'][0] ?? null;
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
        $seguro = DB::table('MDFE_SEGURO')->where(['FILIAL'=>$rt['manifesto']->FILIAL, 'MANIFESTO'=>$rt['manifesto']->ROTA]);
        $seguro = $seguro->get()->toArray();
        $rt['seguro'] = $seguro ?? [];

        //CAPTURANDO A ROTA e EXECUTANDO QUERY
        $rota = DB::table('ROTAS')->where(['FILIAL'=>$rt['manifesto']->FILIAL, 'CODIGO'=>$rt['manifesto']->ROTA]);
        $rota = $rota->get()->toArray();
        $uf_rota = $rota[0]->UF ?? 'null,';

        $rt['rota'] = $rota;
        $rt['uf_rota'] = $uf_rota;

        //CAPTURANDO OS ESTADOS DA ROTA
        $estados = DB::table('CIDADES')->whereIn('CODIGO_UF', explode(',', $uf_rota));
        $estados = $estados->select('ESTADO AS UF')->distinct('UF')->get()->toArray();
        $rt['uf_rota'] = $estados ?? [];

        return $rt;
    }
}
