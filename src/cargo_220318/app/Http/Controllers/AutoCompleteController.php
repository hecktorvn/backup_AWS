<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AutoCompleteController extends Controller
{
    public $outraTabela = [
        'uf' => [
            'int' => 'ALFA2',
            'str' => 'ALFA1',
            'tabela' => 'PARAMETROS',
            'where' => ['CODIGO'=>'ESTADOS']
        ]
    ];

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
        ],
        'cidades' => [
            'int' => 'CODIGO',
            'str' => 'DESCRICAO'
        ]
    ];

    public function get(Request $req, $obj, $val){
        try{
            $val = strtoupper($val);
            if(is_numeric($val)) {
                if( in_array(strtolower($obj), array_keys($this->outraTabela)) ){
                    $data = $this->outraTabela[ strtolower($obj) ];
                    $where[] = [$data['int'], $val];
                    $res = DB::table($data['tabela'])->where($where);
                } else {
                    $where[] = [$this->campos[ strtolower($obj) ]['int'], $val];
                    $res = DB::table( strtoupper($obj) )->where($where);
                }
            } else {
                if( in_array(strtolower($obj), array_keys($this->outraTabela)) ){
                    $data = $this->outraTabela[ strtolower($obj) ];
                    $where[] = [$data['str'], 'LIKE', 'value'=>$val . '%'];
                    $res = DB::table($data['tabela'])->where($where);
                } else {
                    $where[] = [$this->campos[ strtolower($obj) ]['str'], 'like', $val.'%'];
                    $res = DB::table( strtoupper($obj) )->where($where);
                }
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
