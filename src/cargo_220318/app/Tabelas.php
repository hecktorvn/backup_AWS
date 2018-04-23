<?php
namespace App;

use Illuminate\Support\Facades\DB;

class Tabelas{
        public static $titulosLista = [
            'CLIENTE' => ['CNPJ_CPF'=>'CNPJ/CPF', 'SOCIAL'=>'Razão Social', 'ENDERECO'=>'Endereço', 'CIDADE'=>'Cidade', 'UF'=>'UF', 'TELEFONES'=>'Telefone', 'EMAIL'=>'E-mail'],
            'CIDADE' => ['CODIGO'=>'Código IBGE', 'DESCRICAO'=>'Nome', 'ESTADO'=>'UF'],
            'VEICULO' => ['NOME_PROP'=>'Proprietário', 'TELEFONE'=>'Telefone', 'PLACA'=>'Placa', 'VEICULO'=>'Veículo', 'MARCA'=>'Marca', 'MODELO'=>'Modelo', 'COR'=>'Cor'],
            'FILIAL' => ['CODIGO'=>'Código', 'SOCIAL'=>'Razão Social', 'TELEFONES'=>'Telefone', 'ENDERECO'=>'Endereço', 'UF'=>'UF'],
            'OPERADOR' => ['CODIGO'=>'Código', 'NOME'=>'Nome', 'FUNCAO'=>'Função', 'MAXDESC'=>'Desconto Máximo']
        ];

        public static $pesquisa = [
            'CLIENTE' => [
                'str'=>'SOCIAL',
                'int'=>'CNPJ_CPF'
            ],
            'CIDADE' => [
                'str'=>'DESCRICAO',
                'int'=>'CODIGO'
            ],
            'VEICULO' => [
                'str'=>'PLACA',
                'int'=>'PLACA'
            ],
            'FILIAL' => [
                'str'=>'SOCIAL',
                'int'=>'CODIGO'
            ],
            'OPERADOR' => [
                'str'=>'NOME',
                'int'=>'CODIGO'
            ]
        ];

        static function getCampos($table){
            $sql = 'SELECT RDB$FIELD_NAME CAMPO FROM RDB$RELATION_FIELDS WHERE RDB$RELATION_NAME = '.$table;
            $campos = DB::select($sql);

            $rr = array();
            foreach($campos as $v) $rr[] = $v->CAMPO;
            return $rr;
        }
        static function getTitulosLista($table, $campos){
            $ret = array();
            foreach($campos as $campo)
                $ret[$campo] = $this->titulosLista[$table][$campo];
            return $ret;

        }


}



 ?>
