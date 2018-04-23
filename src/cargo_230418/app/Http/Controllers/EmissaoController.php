<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\MailController;

use Format;
use DefRequestController;
use Auth;
use CTe;

class EmissaoController extends Controller
{
    // CTe - Funções para a emissão de CTe
    public function cteView(Request $req, $filial=null, $codigo=null){
        if(!empty($filial) && !empty($codigo)){
            $campos = [
                'CODIGO', 'SITUACAO', 'PROTOCOLO', 'ESPECIE', 'DT_ENTREGA',
                'OBSERVACAO', 'COLETA', 'ENTREGA', 'VEICULO', 'MOTORISTA'
            ];
            $campos = implode(',', $campos);
            $cte = DB::select("SELECT {$campos} FROM CTRC WHERE FILIAL = ? AND CODIGO = ?", [$filial, $codigo]);
            if($cte) $cte = (array) $cte[0];
        } else{
            $cte = null;
        }

        return response()->view('emissao_cte', ['filial'=>$filial, 'codigo'=>$codigo, 'cte'=>$cte]);
    }

    // CTe - Captura os dados do CTe
    public static function getCte($filial, $codigo){
        $cte = DB::table('CTRC')->where(['FILIAL'=>$filial, 'CODIGO'=>$codigo]);
        $cte = $cte->get()->toArray()[0];

        //CAPTURANDO OUTROS DOCUMENTOS
        $doc_ctrc = DB::table('DOC_CTRC')->where(['CTRC'=>$codigo, 'FILIAL'=>$filial]);
        $doc_ctrc = $doc_ctrc->get()->toArray();

        //NOTAS FISCAIS
        $notas = DB::table('NOTAS_FISCAIS')->where(['CTRC'=>$codigo, 'FILIAL'=>$filial]);
        $notas = $notas->get()->toArray();

        //CUBAGEM
        $cubagem = DB::table('CUBAGEM')->where(['CTRC'=>$codigo, 'FILIAL'=>$filial]);
        $cubagem = $cubagem->get()->toArray();

        //CRIANDO RETORNO
        $return = [
            'cte'=>$cte,
            'doc_ctrc'=>$doc_ctrc,
            'notas'=>$notas,
            'cubagem'=>$cubagem,
        ];

        return $return;
    }

    // CTe - salvando os dados do CTe
    public function cte(Request $req){
        $return = ['status'=>'OK', 'msg'=>'', 'action'=>'update', 'response'=>false];
        try{
            //CAPTURANDO DADOS
            $data_ctrc = $req->all();
            $docs = isset($data_ctrc['docs']) ? $data_ctrc['docs'] : null;
            $notas = isset($data_ctrc['notas']) ? $data_ctrc['notas'] : null;
            $cubagem = isset($data_ctrc['cubagem']) ? $data_ctrc['cubagem'] : null;
            $totais = isset($data_ctrc['totais']) ? $data_ctrc['totais'] : [];
            $types = isset($data_ctrc['types']) ? $data_ctrc['types'] : [];

            if(isset($data_ctrc['docs'])) unset($data_ctrc['docs']);
            if(isset($data_ctrc['notas'])) unset($data_ctrc['notas']);
            if(isset($data_ctrc['cubagem'])) unset($data_ctrc['cubagem']);
            if(isset($data_ctrc['types'])) unset($data_ctrc['types']);
            if(isset($data_ctrc['totais'])) unset($data_ctrc['totais']);

            //INSERINDO DADOS EM CTRC
            $data_ctrc['FILIAL'] = Auth::user()->minhaFilial();

            if(!isset($data_ctrc['CODIGO']) || $data_ctrc['CODIGO'] == ''){
                $data_ctrc['CODIGO'] = DefRequestController::geraCod('CTRC_' . Auth::user()->minhaFilial());
                $return['action'] = 'insert';
            }

            $data_ctrc['SERIE'] = intval($data_ctrc['SERIE']);
            $data_ctrc = array_map('strtoupper', $data_ctrc);

            //LIMITANDO OS CARACTERES DE NATUREZA
            $data_ctrc['NATUREZA'] = substr($data_ctrc['NATUREZA'], 0, 30);

            //REMOVENDO CAMPOS INEXISTENTES NO $totais
            unset($totais['PESO_KG']);

            //JUNTANDO OS DADOS DO TOTAL COM OS DADOS DO CTe
            $data_ctrc = array_merge($totais, $data_ctrc);

            //FORMATANDO OS VALORES DE ACORO COM O TYPE
            if(isset($types['dados'])){
                foreach($types['dados'] as $key=>$type){
                    $data_ctrc[$key] = Format::format($data_ctrc[$key], $type);
                }
            }

            //DANDO O INSERT OU O UPDATE CASO TENHA CODIGO
            $whereCTRC = ['CODIGO'=>$data_ctrc['CODIGO'], 'FILIAL'=>$data_ctrc['FILIAL']];

            unset($data_ctrc['NR_CTE']);
            $data_ctrc['IMPRESSOES'] = 1;
            if($return['action'] == 'insert') DB::table('CTRC')->insert($data_ctrc);
            else DB::table('CTRC')->where($whereCTRC)->update($data_ctrc);

            $nr_cte = DB::table('CTRC')->where($whereCTRC)->get();
            $return['response'] = $nr_cte[0];

            //INSERINDO DOCUMENTOS
            $whereDoc = ['CTRC'=>$data_ctrc['CODIGO'], 'FILIAL'=>$data_ctrc['FILIAL']];
            DB::table('DOC_CTRC')->where( $whereDoc )->delete();

            if(count($docs) > 0){
                foreach($docs as $doc){
                    $doc['SEQUENCIA'] = DefRequestController::geraCod('DOC_CTRC');
                    $doc['CTRC'] = $data_ctrc['CODIGO'];
                    $doc['FILIAL'] = $data_ctrc['FILIAL'];
                    $doc['EMISSAO'] = Format::format($doc['EMISSAO'], 'date');
                    $doc['VALOR'] = Format::format($doc['VALOR'], 'numeric');
                    $doc['NUMERO'] = Format::format($doc['NUMERO'], 'integer');
                    DB::table('DOC_CTRC')->insert($doc);
                }
            }

            //INSERINDO NOTAFISCAL
            $whereNotas = ['CTRC'=>$data_ctrc['CODIGO'], 'FILIAL'=>$data_ctrc['FILIAL']];
            DB::table('NOTAS_FISCAIS')->where( $whereNotas )->delete();

            if(count($notas) > 0){
                foreach($notas as $nota){
                    $nota['SEQUENCIA'] = DefRequestController::geraCod('NOTAS_FISCAIS');
                    $nota['CTRC'] = $data_ctrc['CODIGO'];
                    $nota['FILIAL'] = $data_ctrc['FILIAL'];
                    $nota['REMETENTE'] = $data_ctrc['REMETENTE'];
                    $nota['DESTINATARIO'] = $data_ctrc['DESTINATARIO'];

                    foreach($types['notas'] as $key=>$type){
                        $nota[$key] = Format::format($nota[$key], $type);
                    }

                    DB::table('NOTAS_FISCAIS')->insert($nota);
                }
            }

            //INSERINDO CUBAGEM
            $whereCubagem = ['CTRC'=>$data_ctrc['CODIGO'], 'FILIAL'=>$data_ctrc['FILIAL']];
            DB::table('CUBAGEM')->where( $whereNotas )->delete();

            if(count($cubagem) > 0){
                $controle_cub = DefRequestController::geraCod('CONTROLE_CUB');
                foreach($cubagem as $cub){
                    $cub['CONTROLECUB'] = $controle_cub;
                    $cub['SEQUENCIA'] = DefRequestController::geraCod('CUBAGEM');
                    $cub['CTRC'] = $data_ctrc['CODIGO'];
                    $cub['FILIAL'] = $data_ctrc['FILIAL'];

                    if(isset($types['cubagem'])){
                        foreach($types['cubagem'] as $key=>$type){
                            $cub[$key] = Format::format($cub[$key], $type);
                        }
                    }

                    DB::table('CUBAGEM')->insert($cub);
                }
            }
        } catch(\Exception $e) {
            $return['status'] = 'ERRO';
            $return['msg'] = $e->getMessage() . ' - ' . $e->getFile() . '(' . $e->getLine() . ')';
            DB::rollBack();
        }

        return response()->json($return);
    }

    // CTe - Enviando o CTe para a sefaz
    public function enviarCte(Request $req){
        $return = ['status'=>'OK', 'msg'=>'', 'response'=>false];
        try{
            $cte = new CTe();
            $cte->montaCTe(Auth::user()->minhaFilial(), $req->cte);

            $data = $cte->enviar();
            $return['response'] = $data;

            $dataUpdate = [];
            $dataUpdate['STATUS_CTE'] = $data['protCTe']['infProt']['cStat'];
            $dataUpdate['PROTOCOLO'] = $data['protCTe']['infProt']['nProt'] ?? null;
            $dataUpdate['MOTIVO'] = substr($data['protCTe']['infProt']['xMotivo'], 0, 40);
            $dataUpdate['ENVIO'] = date('Y-m-d H:i:s');

            $cte_db = DB::table('CTRC')->where('CODIGO', $req->cte);
            $cte_db->update($dataUpdate);

            if($data['status'] != 'OK'){
                $return['status'] = 'ERRO';
                $return['msg'] = $data['protCTe']['infProt']['xMotivo'];
            }
        }catch(\Exception $e){
            $return['status'] = 'ERRO';
            $msg = preg_replace("/(\n{2,})/", "<br>", $e->getMessage());
            $return['msg'] = '<br>' . preg_replace("/\n/", '<br>', $msg) . ' - <b>' . $e->getFile() . ' (' . $e->getLine() . ')</b>';
        }

        return response()->json($return);
    }

    // CTe - Cancelando e enviando para a sefaz
    public function cancelarCte(Request $req){
        $return = ['status'=>'ERRO', 'response'=>0, 'titulo'=>'Error! ', 'msg'=>'Erro ao tentar cancelar CTe'];
        $motivo = $req->motivo;
        $ctrc = $req->ctrc;

        //VERIFICANDO SE O MOTIVO TEM MENOS DE 15 CARACTERES
        if(strlen(trim($motivo)) < 15){
            $return['msg'] = 'Justificativa deve ter no mínimo 15 letras!';
            $return['titulo'] ='Operação Inválida';
            return $return;
        }

        //VERIFICANDO SE TEM CARTA DE CORRECAO OU CTRC
        $cartaCorrecao = DB::select('SELECT * FROM CARTA_CORRECAO WHERE FILIAL=? AND CTRC=?', [$ctrc['FILIAL'], $ctrc['CODIGO']]);
        $complementar = DB::select('SELECT * FROM CTRC WHERE FILIAL=? AND CTE_ACOMPLEMENTAR=? AND COALESCE(SITUACAO, 0)<>9', [$ctrc['FILIAL'], $ctrc['CODIGO']]);

        $titulo = $return['titulo'];
        if( !empty($complementar) ){
            $return['msg'] = 'CTe complementado/anulado/susbtituido.<br>Não permitido cancelar.';
            $return['titulo'] ='Operação Abortada';
        } else if( !empty($cartaCorrecao) ){
            $return['msg'] = 'Foi emitido uma CARTA CORREÇÃO para esse CTe, não sendo permitido o cancelamento!';
            $return['titulo'] = 'Operação Inválida';
        } else if( empty($ctrc['PROTOCOLO']) ){
            $return['msg'] = 'CTe não enviado! Não permitido o cancelamento';
            $return['titulo'] = 'Operação Abortada';
        } else {
            $date_chave = substr($ctrc['NR_CTE'], 2, 4);
            $fileName =  storage_path("app/CTe/aprovadas/{$date_chave}/{$ctrc['NR_CTE']}-protcte.xml");

            if(!file_exists($fileName)){
                $return['msg'] = 'Arquivo XML não encontrado!';
                $return['titulo'] = 'Erro ao localizar Conhecimento';
            }
        }

        //CASO TENHA DADO ERRO RETORNAR O MESMO
        if($titulo != $return['titulo']) return $return;

        $DataHoraEvento = date('d/m/Y H:i:s');
        $xml = simplexml_load_file($fileName);

        //CAPTURANDO EMITENTE E NUMERO DO CTE
        $xEmit = $xml->CTe->infCte->emit->CNPJ;
        $xNCTe = $xml->CTe->infCte->ide->nCT;

        //MOTANDO O SELECT E SETANDO O WHERE
        $emitente = DB::table('FILIAIS AS F')->where('F.CNPJ', $xEmit);

        //JOIN DE ESTADO
        $emitente->join('PARAMETROS AS P', function($join){
            $join->where(['P.CODIGO'=>'ESTADOS'])->on(['P.ALFA1'=>'F.UF']);
        });

        //JOIN DE CIDADES
        $emitente->leftJoin('CIDADES AS C', function($join){
            $join->on(['C.DESCRICAO'=>'F.CIDADE', 'C.CODIGO_UF'=>'P.COD3']);
        });

        //EXECUTANDO O SELECT
        $emitente = $emitente->select('F.CODIGO', 'F.CNPJ', 'C.CODIGO AS COD_MUNIC');
        $emitente = $emitente->get()->toArray();

        //VERIFICA SE NÃO TEM EMITENTE
        if(empty($emitente)){
            $return['msg'] = 'Emitente nao cadastrado.';
            $return['titulo'] = 'Operação Inválida';

            return $return;
        }

        //VERIFICANDO SE O RETORNO É MESMO O EMITENTE
        if($ctrc['FILIAL'] != $emitente[0]->CODIGO || $ctrc['CODIGO'] != $xNCTe){
            $ctrc = DB::table('CTRC')->where(['FILIAL'=>$emitente[0]->CODIGO, 'CODIGO'=>$xNCTe]);
            $ctrc = $ctrc->get()->toArray();

            if(empty($ctrc)){
                $return['msg'] = 'CTe não localizado!';
                $return['titulo'] = 'Operação Inválida';

                return $return;
            } else {
                $ctrc = (array) $ctrc[0];
            }
        }

        //CANCELANDO O CTe
        $cte = new CTe();
        $data = $cte->cancelar($ctrc['NR_CTE'], $motivo, $xml->protCTe->infProt->nProt);

        //CASO SEJA ERRO EXIBE MENSAGEM
        if($data['error']){
            $return['msg'] = $data['infEvento']->xMotivo;
            $return['titulo'] = 'Erro ao cancelar Conhecimento';
        } else {
            $cStat = $data['infEvento']->cStat;
            $nProt = $data['infEvento']->nProt;
            $dhRegEvento = $data['infEvento']->dhRegEvento;

            $update = [
                'SITUACAO' => 9,
                'STATUS_CTE' => 101,
                'MOTIVO' => $cStat,
                'PROTOCOLO_CANC' => $nProt,
                'MOTIVO_CANC' => $motivo,
                'OPERADOR_CANC' => Auth::user()->CODIGO,
                'DATA_CANC' =>  date('Y-m-d H:i:s', strtotime($dhRegEvento)),
            ];

            $db_ctrc = DB::table('CTRC')->where(['FILIAL'=>$ctrc['FILIAL'], 'CODIGO'=>$ctrc['CODIGO']]);
            $qry = $db_ctrc->update($update);

            if($qry){
                $return['msg'] = 'Conhecimento cancelado com sucesso!';
                $return['titulo'] = 'INFORMAÇÃO';
                $return['status'] = 'OK';
            }
        }

        return $return;
    }

    // CTe - IMPRIME O CTe
    public function printCte(Request $req){
        $cte = new CTe(false);
        $chave = $req->cte['NR_CTE'];
        $dt_chave = substr($chave, 2, 4);
        $cce = empty($req->cce) ? false : $req->cce;

        if(!$cce){
            $path = storage_path("app/CTe/aprovadas/{$dt_chave}/{$chave}-protcte.xml");
        } else {
            $numCCe = DB::select('SELECT count(*)+1 NUM FROM CARTA_CORRECAO WHERE FILIAL = ? AND CTRC = ?', [$req->cte['FILIAL'], $req->cte['CODIGO']]);
            $numCCe = $numCCe[0]->NUM-1;
            $path = storage_path("app/CTe/cce/{$chave}-CCe-{$numCCe}-procEvento.xml");
        }

        if(!isset($req->valid) || $req->valid != true){
            if($cce) return $cte->printCCe($path);
            else return $cte->print($path, $req->cancelado);
        } else return ['status'=> (file_exists($path) ? 'OK' : 'ERROR'), 'msg'=>'Arquivo XML não encontrado!', 'titulo'=>'Operação Cancelada'];
    }

    // CTe - ENVIA O CTe POR EMAIL
    public function sendMail(Request $req){
        $rt = ['status'=>'ERRO', 'msg'=>'', 'response'=>0];
        $mail = new MailController();

        if(!isset($req->cte) || empty($req->cte)){
            $rt['msg'] = 'Favor informar um CTe.';
        } else if(!isset($req->destino) || empty($req->destino)){
            $rt['msg'] = 'Favor informar um E-mail para o envio.';
        } else {
            $cte = DB::table('CTRC AS C')->where(['C.CODIGO'=>$req->cte]);
            $cte->join('FILIAIS AS F', 'F.CODIGO', '=', 'C.FILIAL');
            $cte = $cte->select('C.CODIGO', 'C.NR_CTE', 'C.PROTOCOLO', 'F.FANTASIA', 'F.EMAIL')->get()->toArray();

            if(!$cte){
                $rt['msg'] = 'CTe não encontrado.';
                $rt['titulo'] = 'Erro ao tentar enviar CTe.';
                return $rt;
            } else {
                $cte = (array) $cte[0];
            }

            $data = [
                'fantasia' => $cte['FANTASIA'],
                'email' => $cte['EMAIL'],
                'codigo' => $cte['CODIGO'],
                'chave' => $cte['NR_CTE']
            ];

            try{
                $cte_cont = new CTe();
                $dt_chave = substr($data['chave'], 2, 4);
                $path = storage_path('app/CTe/aprovadas/' . $dt_chave . '/' . $data['chave'] . '-protcte.xml');
                $assunto = 'Arquivo Xml do(s) CTe(s) emitido(s) pela Empresa ' . $data['fantasia'];

                if(empty($cte['PROTOCOLO'])){
                    $rt['msg'] = 'o CTe ainda não foi enviado.';
                    $rt['titulo'] = 'Erro ao tentar enviar CTe.';
                    return $rt;
                } else if(!file_exists($path)) {
                    $rt['msg'] = 'Arquivo XML não encontrado.';
                    $rt['titulo'] = 'Erro ao tentar enviar CTe.';
                    return $rt;
                }

                $mail->sendTemplate('mail.cte', $data, $assunto, $req->destino, function($msg) use ($cte_cont, $data, $path){
                    $content = $cte_cont->print($path, false, true)['response'];
                    $msg->attachData($content, $data['chave'] . '-cte.pdf', ['mime' => 'application/pdf']);
                    $msg->attach($path, ['mime' => 'text/xml']);
                });

                $rt['status'] = 'OK';
                $rt['msg'] = 'E-mail enviado com sucesso!';
                $rt['titulo'] = 'Envio de CTe';
            } catch(Exception $e) {
                $rt['msg'] = $e->getMessage();
            }
        }

        return $rt;
    }

    // CTe - VALIDA E CRIA A CARTA DE CORREÇÃO
    public function cce(Request $req){
        $cte = $req->ctrc;
        $chave = $cte['NR_CTE'];
        $dt_chave = substr($chave, 2, 4);

        //CAMPTURANDO O CAMINHO DO XML
        $path = storage_path("app/CTe/aprovadas/{$dt_chave}/{$chave}-protcte.xml");
        $rt = ['status'=>'ERRO', 'msg'=>'', 'response'=>0];

        if(!file_exists($path)){
            $rt['msg'] = 'Arquivo XML não encontrado.';
        } else {
            $SeqEvento = DB::select('SELECT COUNT(*)+1 SEQEVENTO FROM CARTA_CORRECAO WHERE FILIAL=? AND CTRC=?', [$cte['FILIAL'], $cte['CODIGO']]);
            $SeqEvento = $SeqEvento[0]->SEQEVENTO;
            $lote = DefRequestController::geraCod('CCE_' . str_pad($cte['FILIAL'], 3, '0', STR_PAD_LEFT));
            $DataHoraCce = \date('Y-m-d H:i:s');
            $nSeqEvento = $SeqEvento;

            //GRUPO E NOME DO CAMPO
            $correcao = [
                'campos' => [
                    'OBSERVACAO' => 'xObs',
                    'MOTORISTA' => 'moto',
                    'VEICULO' => 'placa',
                    'PREVISAO' => 'dPrev',
                ],
                'grupos' => [
                    'xObs' => 'compl',
                    'moto' => 'moto',
                    'placa' => 'veic',
                    'dPrev' => 'infNf',
                ]
            ];

            //MONTANDO ARREY COM AS CORREÇÕES
            $infCorrecao = [];
            foreach($req->correcao as $iCCe=>$vCCe){
                $campo = @$correcao['campos'][$iCCe];
                if(empty($campo)) continue;
                $infCorrecao[] = [
                    'grupoAlterado' => $correcao['grupos'][$campo],
                    'campoAlterado' => $campo,
                    'valorAlterado' => $vCCe,
                    'nroItemAlterado' => 1
                ];
            }

            $cte_ = new CTe();
            $send = $cte_->correcao($infCorrecao, $chave, $nSeqEvento);
            if($send->status != 'OK'){
                $rt['status'] = $send->status;
                $rt['msg'] = $send->msg;
            } else {
                //DADOS DA CARTA_CORRECAO
                $data = [
                    'FILIAL' => $cte['FILIAL'],
                    'NUMERO' => $lote,
                    'CTRC' => $cte['CODIGO'],
                    'CHAVE_CTE' => $chave,
                    'TP_EVENTO' => 110110,
                    'LOTE' => $lote,
                    'DATAHORA' => date('Y-m-d H:i:s'),
                    'SEQ_EVENTO' => $SeqEvento,
                    'VERSAO_EVENTO' => '1.00',
                    'DESCRICAO_EVENTO' => 'Carta de Correção',
                    'PROTOCOLO' => $send->infEvento->nProt,
                    'OPERADOR' => Auth::user()->CODIGO,
                    'XML' => $send->xml
                ];

                //ADICIONANDO CARTA_CORRECAO
                DB::table('CARTA_CORRECAO')->insert($data);

                //PERCORRENTO CAMPOS E SALVANDO
                foreach($infCorrecao as $iCCe=>$vCCe){
                    $campo = array_keys($req->correcao)[$iCCe];
                    //DADOS IT_CARTA_CORRECAO
                    $data = [
                        'FILIAL' => $cte['FILIAL'],
                        'CARTA' => $lote,
                        'SEQUENCIA' => $iCCe,
                        'CAMPO' => $campo,
                        'ITEM' => $vCCe['nroItemAlterado'],
                        'VALOR' => $vCCe['valorAlterado']
                    ];

                    //ADICIONANDO IT_CARTA_CORRECAO
                    DB::table('IT_CARTA_CORRECAO')->insert($data);
                }

                $rt['msg'] = 'Mensagem: ' . $send->infEvento->xMotivo . '<br>Verificar a autenticidade dessa carta no site da SEFAZ';
                $rt['titulo'] = 'Retorno Operação';
            }
        }

        return $rt;
    }

    // MANIFESTO - Funções para emissão de manifesto
    public function manifestoView(Request $req, Array $data=[]){
        return response()->view('manifesto', $data);
    }

    // MANIFESTO - Retorna a lista de CTRC para inclusão
    public function incluirManifesto(Request $req){
        $rt = ['status'=>'ERRO', 'response'=>null, 'msg'=>'Registro não localizado.'];
        $data = $req->all();
        $sql_ctrc = '
        SELECT
            DISTINCT COALESCE(C.MANIFESTO, M.CODIGO) MANIFESTO,  CONSIG.CODIGO CODCLIENTE, V.PLACA,
            CONSIG.SOCIAL NMCONSIG, REMETE.SOCIAL NMREMETE, DESTINO.SOCIAL NMDESTINO, C.*,  CR.CODIGO_UF CUFREM,
            CD.CODIGO_UF CUFDEST, CR.CODIGO CMUNREM, CD.CODIGO CMUNDEST,DESTINO.ENDERECO ENDDESTINO,
            REMETE.ENDERECO ENDREMETE, CONSIG.ENDERECO ENDCONSIG, FP.NOME NMFP,O.NOME USUARIO, CR.ESTADO UFORIGEM,
            CD.ESTADO UFDESTINO, RED.SOCIAL NMREDESPACHO, EXP.SOCIAL NMEXPEDIDOR
        FROM CTRC C
            LEFT JOIN CLIENTE CONSIG ON (CONSIG.CNPJ_CPF=C.CONSIGNATARIO OR CONSIG.CODIGO=C.CONSIGNATARIO)
            LEFT JOIN CLIENTE REMETE ON (REMETE.CNPJ_CPF=C.REMETENTE OR REMETE.CODIGO =C.REMETENTE)
            LEFT JOIN CLIENTE DESTINO ON (DESTINO.CNPJ_CPF=C.DESTINATARIO OR DESTINO.CODIGO = C.DESTINATARIO)
            LEFT JOIN CLIENTE RED ON RED.CNPJ_CPF=C.REDESPACHO
            LEFT JOIN CLIENTE EXP ON EXP.CNPJ_CPF=C.EXPEDIDOR
            LEFT JOIN VEICULOS V ON V.PLACA=  C.VEICULO
            LEFT JOIN CIDADES CR ON CR.CODIGO = C.CID_COLETA
            LEFT JOIN CIDADES CD ON CD.CODIGO = C.CID_ENTREGA
            LEFT JOIN FORMAPAGTO FP ON FP.CODIGO = C.FORMAPAGTO
            LEFT JOIN OPERADOR O ON O.CODIGO = C.OPERADOR_CANC
            LEFT JOIN MDFE_CTE MC ON MC.FILIAL_CTE=C.FILIAL AND MC.CTE=C.CODIGO ';
            /*(
                SELECT MF.FILIAL_CTE, MF.CTE, M2.CODIGO MDFE FROM MDFE_CTE MF
                    JOIN CTRC C2 ON C2.FILIAL=MF.FILIAL_CTE AND C2.CODIGO=MF.CTE
                    JOIN MANIFESTO M2 ON M2.FILIAL=MF.FILIAL AND M2.MANIFESTO=MF.MDFE
                WHERE COALESCE(C2.SITUACAO, 0) NOT IN (2,7,9)';*/

        /*if(!empty($data['FILIAL'])) $sql_ctrc .= ' AND C2.FILIAL = :FILIAL';
        if(!empty($data['DT_INI'])){
            if(empty($data['DT_FIM'])) $data['DT_FIM'] = $data['DT_INI'];
            $sql_ctrc .= " AND C2.EMISSAO BETWEEN :DT_INI AND :DT_FIM";
        }*/

        $sql_ctrc .=
        // MC ON MC.FILIAL_CTE=C.FILIAL AND MC.CTE=C.CODIGO
        'LEFT JOIN MANIFESTO M ON ((MC.MDFE IS NOT NULL AND MC.MDFE=M.MANIFESTO) or (MC.MDFE IS NULL AND C.MANIFESTO=M.CODIGO)) AND COALESCE(MC.FILIAL, C.FILIAL)=M.FILIAL
        WHERE ((C.PROTOCOLO IS NOT NULL AND C.ENVIO IS NOT NULL) OR C.IMPRESSOES >0) AND FINALIDADE<>2 ';

        $xaux = ' AND ';
        if(!empty($data['FILIAL'])){
            $sql_ctrc .= $xaux . 'C.FILIAL=:FILIAL';
            $xaux = ' AND ';
        }

        $data['CHAVE_CTE'] = trim($data['CHAVE_CTE']);
        $cte = preg_replace('/[^0-9]/', '', $data['CHAVE_CTE']);

        if($cte != ''){
            if(strlen($cte) == 44){
                $data['CHAVE_CTE'] = $cte;
                $sql_ctrc .= $xaux . "C.NR_CTE = :CHAVE_CTE";
            } else {
                $data['CHAVE_CTE'] = substr($data['CHAVE_CTE'], strlen($data['CHAVE_CTE']), 1) == ',' ? substr(trim($data['CHAVE_CTE']), 1, strlen($data['CHAVE_CTE'])-1) : $data['CHAVE_CTE'];
                $sql_ctrc .= $xaux . 'C.CODIGO IN (:CHAVE_CTE)';
            }

            $xaux = ' AND ';
        }

        if(!empty($data['DT_INI'])){
            if(empty($data['DT_FIM'])) $data['DT_FIM'] = $data['DT_INI'];
            $sql_ctrc .= $xaux . "(C.EMISSAO BETWEEN :DT_INI AND :DT_FIM";
            $xaux = ') AND ';
        }

        if(!empty($data['ESTADO'])){
            $sql_ctrc .= $xaux . 'CD.CODIGO_UF=:ESTADO';
            $xaux = ' AND ';
        }

        if(!empty($data['CONSGINATARIO'])){
            $sql_ctrc .= $xaux . "C.CONSIGNATARIO=:CONSGINATARIO";
            $xaux = ' AND ';
        }

        $mdfe = trim(preg_replace('/[^0-9]/', '', $data['CHAVE_MDFE']));
        if(!empty($data['EXPEDIDOR'])){
            $sql_ctrc .= $xaux . "C.EXPEDIDOR=:EXPEDIDOR";
        }

        if(!empty($mdfe)){
            if(strlen($mdfe) == 44){
                $data['CHAVE_MDFE'] = $mdfe;
                $SQL = 'M.NR_MDFE=:CHAVE_MDFE';
            } else {
                $SQL = 'M.MANIFESTO IN (:CHAVE_MDFE)';
            }

            $sql_ctrc .= $xaux . $SQL;
        } else if($data['SITUACAO'] == 0){
            if(empty($data['CHAVE_CTE'])) $sql_ctrc .= $xaux . "((M.CODIGO IS NULL) OR (M.SITUACAO=9))";
        } else {
            $sql_ctrc .= $xaux . "(M.CODIGO IS NOT NULL AND M.SITUACAO=1)";
        }

        $sql_ctrc .= '
        AND COALESCE(C.SITUACAO, 0) NOT IN (2,7,9)
        ORDER BY C.CODIGO';

        //FORMATANDO OS VALORES
        $data['DT_INI'] = Format::format($data['DT_INI'], 'date');
        $data['DT_FIM'] = Format::format($data['DT_FIM'], 'date');
        $qry = DB::select($sql_ctrc, $data);

        if($qry){
            $rt['response'] = $qry;
            $rt['status'] = 'OK';
        }

        return $rt;
    }

    // MANIFESTO - Valida e Grava o Manifesto
    public function gravarManifesto(Request $req){
        //SETANDO VALORES DEFAULT PARA O RETORNO DE DADOS
        $rt = ['status'=>'ERRO', 'titulo'=>'A T N Ç Ã O', 'msg'=>'Erro ao tentar gerar MDFe', 'response'=>0];
        $manifesto = $req->manifesto ?? [];
        $seguro = $req->seguro ?? [];
        $cte = $req->cte ?? [];

        if(count($cte) <= 0){
            $rt['msg'] = 'Necessário no mínimo um CTe para a geração do MDFe';
            $rt['titulo'] = 'Operação Abortada';
            $rt['goto'] = '#emissao_a';
            return $rt;
        }

        if(empty($req->VEICULO)){
            $rt['msg'] = 'Por favor informe o VEICULO corretamente';
            $rt['focus'] = 'VEICULO';
            return $rt;
        }

        if(empty($req->MOTORISTA)){
            $rt['msg'] = 'Por favor informe o MOTORISTA corretamente';
            $rt['focus'] = 'MOTORISTA';
            return $rt;
        }

        if(empty($req->CIOT) || strlen($req->CIOT) < 12){
            $rt['msg'] = 'CIOT menor que o permitido (12)!';
            $rt['titulo'] = 'Operação Inválida';
            $rt['focus'] = 'CIOT';
            return $rt;
        }

        if(empty($req->ROTA_UF) || strrpos($req->ROTA_UF, $req->ESTADO_COD3) < 0){
            $rt['msg'] = 'A Rota selecionada não passa no estado destino desse manifesto';
            $rt['focus'] = 'ROTA';
            return $rt;
        }

        foreach($cte as $item){
            if($req->UF != $item['UFDESTINO']){
                $rt['response'] = $item;
                $rt['msg'] = 'Não é permitido emitir MDFe para estado diferente do informado no CTe';
                return $rt;
            }
        }

        if(count($seguro) <= 0){
            $rt['msg'] = 'Dados do Seguro obrigatórios! Favor informar.';
            $rt['titulo'] = 'Operação Abortada';
            $rt['goto'] = '#seguro_a';
            return $rt;
        }

        //GRAVANDO MDFE
        try{
            //DB::beginTransaction();
            if(!empty($manifesto) && $req->UF != $manifesto['CIDADE']){
                $data_update = [
                    'SITUACAO' => 9,
                    'MOTIVO_CANC' => 'ALTERAÇÃO DO DESTINO',
                    'OPERADOR_CANC' => Auth::user()->CODIGO,
                    'DATA_CANC' => \Carbon\Carbon::now()
                ];

                $where = ['FILIAL'=>$manifesto['FILIAL'], 'CODIGO'=>$manifesto['CODIGO']];
                DB::table('MANIFESTO')->where($where)->update($data_update);
            }

            if(empty($req->CODIGO)){
                $xMFilial = Auth::user()->minhaFilial();
                $UMANI = DefRequestController::geraCod('MANIFESTO_' . $xMFilial);
                $CODIGO = STR_PAD($UMANI, 6, '0', STR_PAD_LEFT) . '/' . substr(date('Y'), 3, 4) . '-' . $req->CIDADE_ABREVIATURA;
                $rt['response'] = $CODIGO;

                if(empty($manifesto)){
                    $CODMDFE = DefRequestController::geraCod('MFE_' . STR_PAD($xMFilial, 3, '0', STR_PAD_LEFT));
                    $rt['MFE'] = $CODMDFE;
                }
            } else {
                $xMFilial = $manifesto['FILIAL'];
                $CODIGO = $manifesto['CODIGO'] ?? $req->CODIGO;
                $CODMDFE = $manifesto['MANIFESTO'] ?? '0';

                $where_data = ['FILIAL'=>$manifesto['FILIAL'], 'MANIFESTO'=>$manifesto['MANIFESTO']];
                DB::table('MDFE_SEGURO')->where($where_data)->delete();
            }

            $UFILIAL = $xMFilial;
            $data_update = [
                'CODIGO' => $CODIGO,
                'MANIFESTO' => $CODMDFE,
                'MOTORISTA' => preg_replace('/[^0-9]/', '', $req->MOTORISTA),
                'TELERISCO' => empty($req->TELERISCO) ? null : $req->TELERISCO,
                'PREVISAO' => Format::format($req->PREVISAO, 'date'),
                'LACRE' => empty($req->LACRE) ? null : $req->LACRE,
                'DATA' => empty($manifesto['DATA']) ? date('Y-m-d') : $manifesto['DATA'],
                'VEICULO' => $req->VEICULO,
                'CARRETA' => empty($req->CARRETA) ? null : $req->CARRETA,
                'DTSAIDA' => Format::format($req->DTSAIDA, 'date'),
                'CIDADE' => $req->CIDADE,
                'UF' => $req->UF,
                'FILIAL' => $UFILIAL,
                'OBSERVACAO' => empty($req->OBSERVACAO) ? null : $req->OBSERVACAO,
                'TOTAL' => $req->TOTAL_FRETE,
                'SITUACAO' => 0,
                'CIOT' => empty($req->CIOT) ? null : $req->CIOT,
                'ROTA' => empty($req->ROTA) ? null : $req->ROTA,
                'HORA_SAIDA' => $req->HORA_SAIDA,
                'HORA_PREVISAO' =>$req->HORA_PREVISAO,
                'UF_ORIGEM' => $req->UF_ORIGEM,
                'CIDADE_ORIGEM' => $req->CIDADE_ORIGEM,
                'MOTORISTA2' => $req->MOTORISTA2
            ];

            $rt['response'] = $data_update;
            $table = DB::table('MANIFESTO');

            if(!empty($manifesto)) $table->where(['CODIGO'=>$CODIGO, 'FILIAL'=>$UFILIAL])->update($data_update);
            else $table->insert($data_update);

            DB::table('MDFE_CTE')->where(['FILIAL'=>Auth::user()->minhaFilial(), 'MDFE'=>$CODMDFE])->delete();

            foreach($cte as $iItem=>$item){
                $data_insert = [
                    'FILIAL' => Auth::user()->minhaFilial(),
                    'MDFE' => $CODMDFE,
                    'FILIAL_CTE' => $item['FILIAL'],
                    'CTE' => $item['CODIGO'],
                    'SEQUENCIA' => $iItem
                ];

                DB::table('MDFE_CTE')->insert($data_insert);
            }

            if(count($seguro) > 0){
                foreach($seguro as $iSeg=>$item){
                    $data_insert = [
                        'FILIAL' => $UFILIAL,
                        'MANIFESTO' => $CODMDFE,
                        'SEQUENCIA' => $iSeg,
                        'TIPO' => $item['TIPO'],
                        'RESPONSAVEL' => $item['RESPONSAVEL'],
                        'SEGURADORA' => $item['SEGURADORA'],
                        'APOLICE' => $item['APOLICE'],
                        'AVERBACAO' => $item['AVERBACAO']
                    ];

                    DB::table('MDFE_SEGURO')->insert($data_insert);
                }
            }

            $rt['status'] = 'OK';
            $rt['titulo'] = 'M A N I F E S T O';

            if(empty($manifesto)) $rt['msg'] = 'Manifesto gerado com sucesso!';
            else $rt['msg'] = 'Manifesto alterado com sucesso!';

            $rt['response'] = DB::table('MANIFESTO')->where(['CODIGO'=>$CODIGO, 'FILIAL'=>$UFILIAL])->get()->toArray()[0];// ['CODIGO'=>$CODIGO, 'CODMDFE'=>$CODMDFE];
        } catch (\Exception $e){
            $rt['msg'] = $e->getMessage() . ' - ' . $e->getFile() . '(' . $e->getLine() . ')';
            DB::rollBack();
        }
        return $rt;
    }

    // MANIFESTO - Retorna os dados do manifesto
    public function getManifesto(Request $req, $filial, $codigo){

        // CAPTURANDO O MANIFESTO
        $manifesto = DB::table('MANIFESTO')->where(['FILIAL'=>$filial, 'MANIFESTO'=>$codigo])->get()->toArray();
        $manifesto = $manifesto ? $manifesto[0] : [];

        // CAPTURANDO OS SEGUROS DO MANIFESTO
        $seguro = DB::table('MDFE_SEGURO AS S')->where(['S.FILIAL'=>$filial, 'S.MANIFESTO'=>$codigo]);
        $seguro = $seguro->leftJoin('CLIENTE AS C', 'C.CNPJ_CPF', '=', 'S.RESPONSAVEL');
        $seguro = $seguro->leftJoin('FILIAIS AS F', 'F.CNPJ', '=', 'S.RESPONSAVEL');
        $seguro = $seguro->leftJoin('SEGURADORA AS SG', 'SG.CNPJ', '=', 'S.SEGURADORA');
        $qry = DB::raw('IIF(S.TIPO = 0, \'EMITENTE\', \'CONTRATANTE\') TIPO_TX, IIF(S.TIPO = 0, F.SOCIAL, C.SOCIAL) RESPONSAVEL_TX, SG.SOCIAL SEGURADORA_TX, S.*');
        $seguro = $seguro->select($qry)->get()->toArray();

        // CAPTURANDO OS CTe's
        $cte = DB::table('MDFE_CTE AS MDF')->where(['MDF.FILIAL'=>$filial, 'MDF.MDFE'=>$codigo]);
        $cte = $cte->join('CTRC AS CTE', 'CTE.CODIGO', '=', 'MDF.CTE');
        $cte = $cte->leftJoin('CLIENTE AS CR', 'CR.CNPJ_CPF', '=', 'CTE.REMETENTE');
        $cte = $cte->leftJoin('CLIENTE AS CD', 'CD.CNPJ_CPF', '=', 'CTE.DESTINATARIO');
        $cte = $cte->leftJoin('CLIENTE AS CC', 'CC.CNPJ_CPF', '=', 'CTE.CONSIGNATARIO');
        $cte = $cte->leftJoin('CLIENTE AS EX', 'EX.CNPJ_CPF', '=', 'CTE.EXPEDIDOR');
        $cte = $cte->leftJoin('CIDADES AS CE', 'CE.CODIGO', '=', 'CTE.CID_ENTREGA');
        $cte = $cte->leftJoin('CIDADES AS CO', 'CO.CODIGO', '=', 'CTE.CID_COLETA');
        $qry = DB::raw('CTE.*, CR.SOCIAL NMREMETE, CD.SOCIAL NMDESTINO, CD.SOCIAL NMCONSIG, CE.ESTADO UFDESTINO, CE.DESCRICAO DESTINO, CO.ESTADO UFORIGEM, CO.DESCRICAO ORIGEM, EX.SOCIAL');
        $cte = $cte->select($qry)->get()->toArray();

        $data = ['mdfe'=>$manifesto, 'seguro'=>$seguro, 'cte'=>$cte];
        return $this->manifestoView($req, $data);
    }
}
