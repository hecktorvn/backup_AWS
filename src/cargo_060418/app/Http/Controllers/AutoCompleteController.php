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
        ],
        'uf_nome' => [
            'int' => 'ALFA2',
            'str' => 'DESCR',
            'tabela' => 'PARAMETROS',
            'where' => ['CODIGO'=>'ESTADOS']
        ],
        'seguradoras' => [
            'int' => 'COD1',
            'str' => 'DESCR',
            'tabela' => 'PARAMETROS',
            'where' => ['CODIGO'=>'SEGURADORAS']
        ],
        'cfop' => [
            'int' => 'COD1',
            'str' => 'DESCR',
            'tabela' => 'PARAMETROS',
            'where' => ['CODIGO'=>'CFOP']
        ],
        'produtos' => [
            'int' => 'CODIGO',
            'str' => 'DESCRICAO',
            'tabela' => 'PRODUTOS_SERVICOS'
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
            'str' => 'DESCRICAO',
            'if' => [
                'uf' => 'ESTADO'
            ]
        ]
    ];

    public function get(Request $req, $obj, $val){
        try{
            $val = strtoupper($val);
            if( in_array(strtolower($obj), array_keys($this->outraTabela)) ){
                $data = $this->outraTabela[ strtolower($obj) ];
            } else {
                $data = $this->campos[ strtolower($obj) ];
            }

            if(isset($data['tabela'])) $obj = $data['tabela'];
            if(isset($data['where'])) $where = $data['where'];
            else $where = [];

            if(isset($data['if'])){
                foreach($data['if'] as $index=>$campo){
                    if(!empty($req->get($index))){
                        $where[] = [$campo, '=', $req->get($index)];
                    }
                }
            }

            if(is_numeric($val)) {
                $where[] = [$data['int'], $val];
            } else {
                $where[] = [$data['str'], 'like', $val.'%'];
            }

            $res = DB::table( strtoupper($obj) )->where($where);
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
