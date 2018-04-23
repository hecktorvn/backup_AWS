<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AutoCompleteController extends Controller
{
    public $campos = [
        'cst' => [
            'int' => 'COD1',
            'str' => 'DESCR',
            'tabela' => 'PARAMETROS',
            'where' => ['CODIGO'=>'SITUATRIB']
        ],
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
            'where' => ['CODIGO'=>'ESTADOS'],
            'or' => [
                'str' => ['campo'=>'ALFA1']
            ]
        ],
        'seguradoras' => [
            'int' => 'COD1',
            'str' => 'DESCR',
            'tabela' => 'PARAMETROS',
            'where' => ['CODIGO'=>'SEGURADORAS']
        ],
        'seguradora' => [
            'int' => 'CPF',
            'str' => 'FANTASIA'
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
        ],
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
            'str' => 'NOME',
            'max' => 10,
            'or' => [
                'int' => ['min'=>11, 'campo'=>'CPF']
            ]
        ],
        'cidades' => [
            'int' => 'CODIGO',
            'str' => 'DESCRICAO',
            'if' => [
                'uf' => 'ESTADO'
            ]
        ],
        'veiculos' => [
            'int' => 'PLACA',
            'str' => 'PLACA',
        ],
        'rotas' => [
            'int' => 'CODIGO',
            'str' => 'DESCRICAO'
        ]
    ];

    public function get(Request $req, $obj, $val){
        try{
            $val = strtoupper($val);
            $data = $this->campos[ strtolower($obj) ];
            $where = [];
            $or = [];

            if(isset($data['or'])) $or = $data['or'];
            if(isset($data['tabela'])) $obj = $data['tabela'];
            if(isset($data['where'])) $where = $data['where'];

            //CASO EU ENVIE O CAMPO CITADO NO if
            //ELE ADICIONA O VALOR NO WHERE
            if(isset($data['if'])){
                foreach($data['if'] as $index=>$campo){
                    if(!empty($req->get($index))){
                        $where[] = [$campo, '=', $req->get($index)];
                    }
                }
            }

            if(is_numeric($val)) {
                $max = strlen($val);
                if(isset($data['max'])) $max = $data['max'];
                $where[] = [$data['int'], substr($val, 0, $max)];
            } else {
                $where[] = [$data['str'], 'like', $val.'%'];
            }

            //SETANDO O OR
            $orWhere = [];
            foreach ($or as $iOr => $vl) {
                $cond = '=';
                $campo = $iOr;
                $vCond = $val;

                if(is_array($vl)){
                    if(isset($vl['cond'])) $cond = $vl['cond'];
                    if(isset($vl['campo'])) $campo = $vl['campo'];
                    if(isset($vl['value'])) $vCond = $vl['value'];
                    if(isset($vl['min']) && strlen($vCond) < $vl['min']) continue;
                } else {
                    $vCond = $vl;
                }

                //MONTANDO O ARRAY
                $orWhere[] = [$campo, $cond, $vCond];
            }

            //SETANDO O TABLE E O WHERE
            $res = DB::table( strtoupper($obj) )->where($where);

            //ADICIONANDO A CONDIÇÃO
            $res = $res->orWhere($orWhere);
            $retorno = $res->connection->select($res->toSql(), $res->getBindings());
        } catch (\Illuminate\Database\QueryException $ex) {
            $errMsg = $ex->getMessage();
            $retorno = false;
        }

        if($retorno === false) {
            $status = 'ERRO';
            $msg = $errMsg;
        } else {
            $status = 'OK';
            $msg = 'Encontrado';
        }

        $rr1 = array();
        if($retorno){
            foreach($retorno as $rr){
                $rr1[] = array_map('utf8_encode', (array) $rr);
            }
        }

        $ret = ['response' => $rr1, 'status'=> $status, 'msg'=> $msg];
        return response()->json($ret);
    }
}
