<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Auth;
use DefRequestController;
use format;
use Tabelas;

class TabelaPrecoController extends Controller
{
    private static $table = 'TABELA_PRECO';
    private static $return = ['response' => false, 'status'=>'ERRO', 'msg' => 0];
    private static $tabelatx = '
    SELECT SP.* FROM SP_TABELAPRECO (
        :CLIENTE, :ESTADOORIGEM, :CIDADEORIGEM, :ESTADODESTINO,
        :CIDADEDESTINO, :PRODUTO, :VALOR, :PESO, :ICM_, :OUTROS_,
        :VOLUMES, :FATOR
    ) SP';

    //RETORNA OS DADOS APOS SALVAR
    public static function save(Request $req){
        $rt = self::$return;

        try{
            $types_dados = isset($req->types['dados']) ? $req->types['dados'] : [];
            $types_tonelada = isset($req->types['tonelada']) ? $req->types['tonelada'] : [];
            $types_prod = isset($req->types['produtos']) ? $req->types['produtos'] : [];
            $types_pacotinho = isset($req->types['pacotinho']) ? $req->types['pacotinho'] : [];

            $data = $req->all();
            $data['FILIAL'] = Auth::user()->minhaFilial();
            $data['ALTERACAO'] = date('Y-m-d h:i:s');
            if(!isset($data['CLIENTE'])) $data['CLIENTE'] = "TABELA_PADRAO";

            $pacotinho = $req->pacotinho;
            $produtos = $req->produtos;
            $tonelada = $req->tonelada;
            $dados = [
                'ICMS' => $data['ICMS'],
                'IND_CUB' => $data['IND_CUB'],
                'PRAZO_ENTREGA' => $data['PRAZO_ENTREGA'],
                'ICMS_INCLUSO' => isset($data['ICMS_INCLUSO']) ? 1 : 0
            ];

            //SETANDO O FORMATO
            foreach($dados as $i=>$v){
                if(isset($types_dados[$i])){
                    $dados[$i] = FormatControll::format($v, $types_dados[$i]);
                }
            };

            if(isset($data['types'])) unset($data['types']);
            if(isset($data['ICMS'])) unset($data['ICMS']);
            if(isset($data['IND_CUB'])) unset($data['IND_CUB']);
            if(isset($data['ICMS_INCLUSO'])) unset($data['ICMS_INCLUSO']);
            if(isset($data['PRAZO_ENTREGA'])) unset($data['PRAZO_ENTREGA']);
            if(isset($data['pacotinho'])) unset($data['pacotinho']);
            if(isset($data['tonelada'])) unset($data['tonelada']);
            if(isset($data['produtos'])) unset($data['produtos']);

            //INSERINDO OU ALTERANDO O TIPO 1
            if(is_array($pacotinho)){
                foreach($pacotinho as $i=>$pacote){
                    $pacote = array_merge($pacote, $data);
                    $pacote['TIPO'] = 1;
                    unset($pacote['DESCRICAO']);

                    foreach($pacote as $i=>$v){
                        if(isset($types_pacotinho[$i])){
                            $pacote[$i] = FormatControll::format($v, $types_pacotinho[$i]);
                        }
                    };

                    if(!isset($pacote['SEQUENCIA'])){
                        $pacote['SEQUENCIA'] = DefRequestController::geraCod('TABELA_PRECO');
                        DB::table(self::$table)->insert($pacote);
                    } else {
                        $where = ['SEQUENCIA' => $pacote['SEQUENCIA'], 'FILIAL' => $pacote['FILIAL']];
                        DB::table(self::$table)->where($where)->update($pacote);
                    }
                }
            }

            //INSERINDO OU ALTERANDO O TIPO 2
            $tonelada['TIPO'] = 2;
            $tonelada = array_merge($tonelada, $data);
            $tonelada = array_merge($tonelada, $dados);

            //FORMATANDO OS VALORES
            foreach($tonelada as $i=>$v){
                if(isset($types_tonelada[$i])){
                    $tonelada[$i] = FormatControll::format($v, $types_tonelada[$i]);
                }
            };

            if(!isset($tonelada['SEQUENCIA'])){
                $tonelada['SEQUENCIA'] = DefRequestController::geraCod('TABELA_PRECO');
                DB::table(self::$table)->insert($tonelada);
            } else {
                $where = ['SEQUENCIA' => $tonelada['SEQUENCIA'], 'FILIAL' => $tonelada['FILIAL']];
                DB::table(self::$table)->where($where)->update($tonelada);
            }

            //INSERINDO OU ALTERANDO O TIPO 3
            if(is_array($produtos)){
                foreach($produtos as $i=>$item){
                    $item = array_merge($item, $data);
                    $item['TIPO'] = 3;
                    $item['PESO_FRETE'] = 0;
                    unset($item['DESCRICAO']);

                    //FORMATANDO OS VALORES
                    foreach($item as $i=>$v){
                        if(isset($types_prod[$i])){
                            $item[$i] = FormatControll::format($v, $types_prod[$i]);
                        }
                    };

                    if(!isset($item['SEQUENCIA'])){
                        $item['SEQUENCIA'] = DefRequestController::geraCod('TABELA_PRECO');
                        DB::table(self::$table)->insert($item);
                    } else {
                        $where = ['SEQUENCIA' => $item['SEQUENCIA'], 'FILIAL' => $item['FILIAL']];
                        DB::table(self::$table)->where($where)->update($item);
                    }
                }
            }

            $rt['response'] = [$tonelada];
            $rt['status'] = 'OK';
        } catch(\Exception $e) {
            $rt['msg'] = $e->getMessage() . ' - ' . $e->getFile() . ' (' . $e->getLine() . ')';
        }

        return $rt;
    }

    //RETORNA UMA TABELA DE PRECO
    public static function get(Request $req){
        $where = [];
        if(isset($req->dest)) $where['T.DESTINO'] = $req->dest;
        if(isset($req->uf_dest)) $where['T.ESTADODEST'] = $req->uf_dest;
        if(isset($req->orig)) $where['T.ORIGEM'] = $req->orig;
        if(isset($req->uf_orig)) $where['T.ESTADOORIG'] = $req->uf_orig;
        if(isset($req->tab)) $where['T.CLIENTE'] = $req->tab;
        if(count($where) < 4) return false;

        $rt = self::$return;
        try{
            $qry = DB::table(self::$table . ' AS T')->leftJoin('PRODUTOS_SERVICOS AS P', 'P.CODIGO', '=', 'T.PRODUTO')->where( $where );
            $rt['response'] = $qry->select('T.*','P.DESCRICAO')->get();
            $rt['status'] = 'OK';
        } catch(\Exception $e) {
            $rt['msg'] = $e->getMessage() . ' - ' . $e->getFile() . ' (' . $e->getLine() . ')';
        }

        return $rt;
    }

    //CALCULA OS TOTAIS
    public static function CalcularTotais(Request $req){
        $xTpeso = 0;
        $xTvol = 0;
        $xTvalmerc = 0;
        $xFatorPedagio = 0;

        //CAPTURANDO OS CAMPOS DA TABELA
        $ctrc_campos = Tabelas::getCampos('CTRC');
        $ctrc = [];

        foreach($ctrc_campos as $campo){
            $ctrc[trim($campo)] = '';
        }

        //VERIFICANDO SE TEM NOTAS
        if(isset($req->notas) && !empty($req->notas)){
            $ctrc['VOLUMES'] = count($req->notas);
            $ctrc['TOTAL_MERC'] = 0;
            $ctrc['PESO'] = 0;

            foreach($req->notas as $nota){
                $ctrc['PESO'] += FormatControll::format($nota['PESO'], 'numeric');
                $ctrc['TOTAL_MERC'] += FormatControll::format($nota['TOTAL'], 'numeric');
            }

            $ctrc['CUBAGEM'] = $ctrc['PESO'];
        }

        //VERIFICA SE TEM CUBAGEM
        if(isset($req->cubagem) && !empty($req->cubagem)){
            self::CalcularCubagem($ctrc);
            if($ctrc['PESO'] > $ctrc['CUBAGEM']){
                $ctrc['CUBAGEM'] = $ctrc['PESO'];
            }
        }

        //CAPTURANDO A TABELA DE PRECO
        $data = [
            'CLIENTE' => $ctrc['CONSIGNATARIO'],
            'ESTADOORIG' => $req->CID_COLETA,
            'CIDADEORIGEM' => $ctrc['COLETA'],
            'ESTADODESTINO' => $req->CID_ENTREGA,
            'CIDADEDESTINO' => $ctrc['ENTREGA'],
            'PRODUTO' => $ctrc['PRODUTO'],
            'VALOR' => $ctrc['TOTAL_MERC'],
            'PESO' => ($ctrc['CUBAGEM'] < $ctrc['PESO'] ? $ctrc['PESO'] : $ctrc['CUBAGEM']),
            'ICM_' => $ctrc['ALIQUOTA_ICMS'],
            'OUTROS_' => 0,
            'VOLUMES' => $ctrc['VOLUMES'],
            'FATOR' => $xFatorPedagio
        ];

        $stmt = DB::getPdo()->prepare(self::$tabelatx);
        $qry = $stmt->execute($data);

        return $stmt->toSql();
    }
}
