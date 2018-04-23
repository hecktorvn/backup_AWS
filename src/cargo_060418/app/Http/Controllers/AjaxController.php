<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TabelaPreco;

class AjaxController extends Controller
{
    //
	public function post(Request $req, $tabela=0, $act=0, $key='id'){
		$ret = ['response' => false, 'status'=>'ERRO', 'msg' => 'Objeto não informado'];
		$dados = $req->all();

		if($tabela == 'saveTabelaPreco'){
			$ret = TabelaPreco::save($req);
		} elseif($tabela == 'CalcularTotaisTP'){
			$ret = TabelaPreco::CalcularTotais($req);
		} elseif($tabela == 'getTabelaPreco') {
			$ret = TabelaPreco::get($req);
		} elseif($tabela == 'xmls' && (!is_numeric($act) && $act != '')){
			$ret = ['response' => XmlsController::$act, 'status'=>'OK', 'msg'=>0];
		} elseif($tabela != '' && (!is_numeric($act) && $act != '')){
			$retorno = false;
			$status = 'ERRO';
			$msg = 'Solicitação inválida';
			$defreq = new DefRequestController();

			if(!isset($req->limit)) $limit = 1000;
			else $limit = $req->limit;

			if($act == 'list') $defreq->list($tabela, $retorno, $req->offset, $limit);
      		if($act == 'ins') $retorno = $defreq->insert($req, $tabela, $key);
			if($act == 'upd') $retorno = $defreq->update($req, $tabela, $key);

			if($retorno === false) {
				$status = 'ERRO';
				$msg = $defreq->errMsg;
			}else{
				$status = 'OK';
				$msg = $defreq->genID;
			}

			$ret = ['response' => $retorno, 'status'=> $status, 'msg'=> $msg];
		} else {
			$ret = ['response' => false, 'status'=> 'ERRO', 'msg' => 'Objeto não informado'];
		}

		return response()->json($ret);
	}
}
