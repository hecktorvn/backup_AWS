<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AutoCompleteController extends Controller
{
    public $campos = [
        'cliente' => [
            'int' => 'CNPJ_CPF',
            'str' => 'SOCIAL'
        ],
        'filiais' => [
            'int' => 'CODIGO',
            'str' => 'SOCIAL'
        ],
        'motorista' => [
            'int' => 'CODIGO',
            'str' => 'NOME'
        ]
    ];

    public function get(Request $req, $obj, $val){
        try{
            if(is_numeric($val)) {
                $res = DB::table( strtoupper($obj) )->where($this->campos[ strtolower($obj) ]['int'], $val);
            } else {
                $res = DB::table( strtoupper($obj) )->where($this->campos[ strtolower($obj) ]['str'], 'like', $val.'%');
            }

            $retorno =  $res->connection->select($res->toSql(), $res->getBindings());
        } catch (\Illuminate\Database\QueryException $ex) {
            $errMsg = $ex->getMessage();
            return false;
        }

        if($retorno === false) {
            $status = 'ERRO';
            $msg = $errMsg;
        } else {
            $status = 'OK';
            $msg = 'Encontrado';
        }

        $rr1 = array();
        foreach($retorno as $rr){
            $rr1[] = array_map('utf8_encode', (array) $rr);
        }

        $ret = ['response' => $rr1, 'status'=> $status, 'msg'=> $msg];
        return response()->json($ret);
    }
}
