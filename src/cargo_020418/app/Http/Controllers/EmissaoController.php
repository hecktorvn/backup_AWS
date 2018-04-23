<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Format;
use DefRequestController;
use Auth;
use CTe;

class EmissaoController extends Controller
{
    public function cteView(){
        return response()->view('emissao_cte');
    }

    public function cte(Request $req){
        $return = ['status'=>'OK', 'msg'=>'', 'action'=>'update', 'response'=>false];
        try{
            //CAPTURANDO DADOS
            $data_ctrc = $req->all();
            $docs = isset($data_ctrc['docs']) ? $data_ctrc['docs'] : null;
            $notas = isset($data_ctrc['notas']) ? $data_ctrc['notas'] : null;
            $types = isset($data_ctrc['types']) ? $data_ctrc['types'] : [];

            if(isset($data_ctrc['docs'])) unset($data_ctrc['docs']);
            if(isset($data_ctrc['notas'])) unset($data_ctrc['notas']);
            if(isset($data_ctrc['types'])) unset($data_ctrc['types']);

            //INSERINDO DADOS EM CTRC
            $data_ctrc['FILIAL'] = Auth::user()->minhaFilial();

            if(!isset($data_ctrc['CODIGO']) || $data_ctrc['CODIGO'] == ''){
                $data_ctrc['CODIGO'] = DefRequestController::geraCod('CTRC_' . Auth::user()->minhaFilial());
                $return['action'] = 'insert';
            }

            $data_ctrc['SERIE'] = intval($data_ctrc['SERIE']);
            $data_ctrc = array_map('strtoupper', $data_ctrc);

            //FORMATANDO OS VALORES DE ACORO COM O TYPE
            if(isset($types['dados'])){
                foreach($types['dados'] as $key=>$type){
                    $data_ctrc[$key] = Format::format($data_ctrc[$key], $type);
                }
            }

            return response()->json($data_ctrc);
            die;

            //DANDO O INSERT OU O UPDATE CASO TENHA CODIGO
            if($return['action'] == 'insert') DB::table('CTRC')->insert($data_ctrc);
            else DB::table('CTRC')->where(['CODIGO'=>$data_ctrc['CODIGO'], 'FILIAL'=>$data_ctrc['FILIAL']])->update($data_ctrc);

            $return['response'] = $data_ctrc;

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
        } catch(\Exception $e) {
            $return['status'] = 'ERRO';
            $return['msg'] = $e->getMessage();
        }

        return response()->json($return);
    }

    public function enviarCte(Request $req){
        $return = ['status'=>'OK', 'msg'=>'', 'response'=>false];
        try{
            $cte = new CTe;
            $cte->montaCTe(Auth::user()->minhaFilial(), $req->cte);
            $data = $cte->enviar();
            $return['response'] = $data;

            if($data['status'] != 'OK'){
                $return['status'] = 'ERRO';
                $return['msg'] = $data['protCTe']['infProt']['xMotivo'];
            }
        }catch(Exception $e){
            $return['status'] = 'ERRO';
            $return['msg'] = $e->getMessage();
        }

        return response()->json($return);
    }
}
