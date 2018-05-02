<?php
namespace App;

use Illuminate\Support\Facades\DB;

class Tabelas{
        public static $titulosLista = [
            'CTRC' => [
                'CODIGO' => 'Código',
                'TX_REMETENTE' => 'Remetente',
                'TX_DESTINATARIO' => 'Destinatário',
                'COLETA' => 'Origem',
                'ENTREGA' => 'Destino',
                'EMISSAO' => 'Emissão',
                'DT_ENTREGA' => 'Entrega',
                'TOTAL_MERC' => 'Mercadoria',
                'NR_CTE' => 'Chave',
            ],
            'CLIENTE' => [
                'CNPJ_CPF' => 'CNPJ/CPF',
                'SOCIAL' => 'Razão Social',
                'FANTASIA' => 'Fantasia',
                'ENDERECO' => 'Endereço',
                'CIDADE' => 'Cidade',
                'UF' => 'UF',
                'TELEFONES' => 'Telefone',
                'EMAIL' => 'E-mail'
            ],
            'CIDADE' => [
                'CODIGO' => 'Código IBGE',
                'DESCRICAO' => 'Nome',
                'ESTADO' => 'UF'
            ],
            'VEICULO' => [
                'NOME_PROP' => 'Proprietário',
                'TELEFONE' => 'Telefone',
                'PLACA' => 'Placa',
                'VEICULO' => 'Veículo',
                'MARCA' => 'Marca',
                'MODELO' => 'Modelo',
                'COR' => 'Cor'
            ],
            'FILIAL' => [
                'CODIGO' => 'Código',
                'SOCIAL' => 'Razão Social',
                'TELEFONES' => 'Telefone',
                'ENDERECO' => 'Endereço',
                'UF' => 'UF'
            ],
            'OPERADOR' => [
                'CODIGO' => 'Código',
                'NOME' => 'Nome',
                'FUNCAO' => 'Função',
                'MAXDESC' => 'Desconto Máximo'
            ],
            'MANIFESTO' => [
                'MANIFESTO' => 'Código',
                'DATACAD' => 'Emissão',
                'CHEGADA' => 'Chegada',
                'FILIAL' => 'Filial',
                'VEICULO' => 'Veículo',
                'CIDADE_ORIGEM' => 'Origem',
                'UF_ORIGEM' => 'Estado',
                'CIDADE' => 'Destino',
                'UF' => 'Estado',
                'SITUACAO_2' => 'Situação',
            ]
        ];

        public static $options = [
            'CTRC' => [
                'title' => 'Pesquisa de CTe',
                'cadastro' => false,
                'buttons' => [
                    'new' => 'Emitir CTe',
                    'edit' => 'Visualizar CTe'
                ],
                'link' => '/emissao/cte',
                'linkEdit' => '/emissao/cte/',
                'dataEdit' => ['FILIAL', 'CODIGO'],
                'types' => [
                    'TOTAL_MERC' => 'money',
                    'EMISSAO' => 'date',
                    'DT_ENTREGA' => 'date',
                ]
            ],
            'MANIFESTO' => [
                'title' => 'Pesquisa de MDFe',
                'cadastro' => false,
                'buttons' => [
                    'new' => 'Emitir MDFe',
                    'edit' => 'Visualizar MDFe'
                ],
                'link' => '/manifesto',
                'linkEdit' => '/manifesto',
                'dataEdit' => ['FILIAL', 'MANIFESTO'],
                'types' => [
                    'DATACAD' => 'date',
                    'CHEGADA' => 'date',
                ]
            ]
        ];

        public static $ordem = [
            'CLIENTE' => 'SOCIAL',
            'CIDADE' => 'DESCRICAO',
            'VEICULO' => 'PLACA',
            'FILIAL' => 'SOCIAL',
            'OPERADOR' => 'NOME',
            'CTRC' => 'CODIGO',
            'MANIFESTO' => 'MANIFESTO'
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
            ],
            'MANIFESTO' => [
                'str'=>'CODIGO',
                'int'=>'MANIFESTO',
                'select' => [
                    "IIF(
                        M.SITUACAO = 9,
                        'Cancelado',
                        IIF(
                            M.SITUACAO = 1,
                            'Encerrado',
                            IIF(
                                M.PROTOCOLO IS NOT NULL,
                                'Autorizado',
                                'Não Autorizado'
                            )
                        )
                    ) AS SITUACAO_2",
                    'M.*',
                ]
            ],
            'CTRC' => [
                'str' => 'CODIGO',
                'int' => 'CODIGO',
                'join' => [
                    [
                        'TABLE' => 'CLIENTE',
                        'ON' => ['CNPJ_CPF' => 'REMETENTE'],
                        'SHOW' => ['SOCIAL AS TX_REMETENTE'],
                    ],
                    [
                        'TABLE' => 'CLIENTE',
                        'ON' => ['CNPJ_CPF' => 'DESTINATARIO'],
                        'SHOW' => ['SOCIAL AS TX_DESTINATARIO'],
                    ]
                ]
            ]
        ];

        static function getCampos($table){
            $sql = "SELECT RDB\$FIELD_NAME CAMPO FROM RDB\$RELATION_FIELDS WHERE RDB\$RELATION_NAME = '{$table}'";
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
