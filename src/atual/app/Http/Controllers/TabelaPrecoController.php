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
                'ICMS_INCLUSO' => isset($data['ICMS_INCLUSO']) ? 1 : 0,
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
                    $pacote['PRODUTO'] = 0;
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
        $xValRed = 0;
        $rt = self::$return;
        $rt['status'] = 'OK';

        $xFatorPedagio = DB::select("SELECT VALOR3 FROM PARAMETROS WHERE CODIGO = 'CTRC_" . Auth::user()->minhaFilial() . "'");
        if($xFatorPedagio) $xFatorPedagio = $xFatorPedagio[0]->VALOR3;
        else $xFatorPedagio = 0;

        //CAPTURANDO OS CAMPOS DA TABELA
        $ctrc_campos = Tabelas::getCampos('CTRC');
        $ctrc = [];

        foreach($ctrc_campos as $campo){
            $ctrc[trim($campo)] = '';
        }

        //CAPTURANDO NOTAS E CUBAGENS
        $dados_ctrc = $req->all();
        if(isset($dados_ctrc['notas'])){
            $notas = $dados_ctrc['notas'];
            unset($dados_ctrc['notas']);
        }else $notas = [];

        if(isset($dados_ctrc['cubagem'])){
            $cubagem = $dados_ctrc['cubagem'];
            unset($dados_ctrc['cubagem']);
        }else $cubagem = [];

        //PREENCHENDO OS CAMPOS
        foreach($ctrc as $name=>$valor){
            if(isset($dados_ctrc[$name])){
                $ctrc[$name] = $dados_ctrc[$name];
            }
        }

        //VERIFICANDO SE TEM NOTAS
        if(isset($notas) && !empty($notas)){
            $ctrc['VOLUMES'] = count($notas);
            $ctrc['TOTAL_MERC'] = 0;
            $ctrc['PESO'] = 0;

            foreach($notas as $nota){
                $ctrc['PESO'] += FormatControll::format($nota['PESO'], 'numeric');
                $ctrc['TOTAL_MERC'] += FormatControll::format($nota['TOTAL'], 'numeric');
            }

            $ctrc['CUBAGEM'] = $ctrc['PESO'];
        }

        //VERIFICA SE TEM CUBAGEM
        if(isset($cubagem) && !empty($cubagem)){
            self::CalcularCubagem($req, $ctrc);
            if($ctrc['PESO'] > $ctrc['CUBAGEM']){
                $ctrc['CUBAGEM'] = $ctrc['PESO'];
            }
        }

        //CAPTURANDO A TABELA DE PRECO
        $data = [
            'CLIENTE' => $ctrc['CONSIGNATARIO'],
            'ESTADOORIGEM' => $req->TX_UF_COLETA,
            'CIDADEORIGEM' => $ctrc['TX_COLETA'],
            'ESTADODESTINO' => $req->TX_UF_ENTREGA,
            'CIDADEDESTINO' => $ctrc['TX_ENTREGA'],
            'PRODUTO' => $ctrc['PRODUTO'],
            'VALOR' => $ctrc['TOTAL_MERC'],
            'PESO' => ($ctrc['CUBAGEM'] < $ctrc['PESO'] ? $ctrc['PESO'] : $ctrc['CUBAGEM']),
            'ICM_' => floatval($ctrc['ALIQUOTA_ICMS']),
            'OUTROS_' => 0,
            'VOLUMES' => $ctrc['VOLUMES'],
            'FATOR' => $xFatorPedagio
        ];

        foreach($data as $i=>$val){
            if(empty($val)) $data[$i] = 0;
        }

        //$rt['response'] = $data;
        //return $rt;

        $Tabela_Preco = DB::select(self::$tabelatx, $data);
        if($Tabela_Preco) $Tabela_Preco = (array) $Tabela_Preco[0];

        //VARIFICA SE RETORNOU ALGUAM COISA
        if(!$Tabela_Preco || empty($Tabela_Preco)){
            $rt['status'] = 'ERRO';
            $rt['msg'] = '
            Tabela de preço não cadastrada para estas cidades
            <div class="p-1">
                Coleta: <strong>' . $dados_ctrc['TX_COLETA'] . '</strong>  -
                Entrega: <strong>' . $dados_ctrc['TX_ENTREGA'] . '</strong>
            </div>';
            return $rt;
        }

        if($Tabela_Preco['FRETEPESO'] == 0 || $ctrc['PESO'] == 0){
            $ctrc['PESO_KG'] = 0;
        } else {
            $ctrc['PESO_KG'] = $Tabela_Preco['FRETEPESO'] / $ctrc['PESO'];
            if(!is_numeric($ctrc['PESO_KG'])) $ctrc['PESO_KG'] = 0;
        }

        //SETANDO VALORES
        $XIcmsIncluso = $Tabela_Preco['ICMS_INCLUSO'] == 1;
        if($ctrc['PESO'] == 0 && $ctrc['TOTAL_MERC'] == 0){
            $ctrc['BASE_ICMS']   = 0;
            $ctrc['DESPACHO']    = 0;
            $ctrc['FRETE_PESO']  = 0;
            $ctrc['FRETE_VALOR'] = 0;
            $ctrc['OUTROS']      = 0;
            $ctrc['PEDAGIO']     = 0;
            $ctrc['SEC_CAT']     = 0;
            $ctrc['TX_COLETA']   = 0;
            $ctrc['TX_ENTREGA']  = 0;
            $ctrc['VALOR_FRETE'] = 0;
            $ctrc['TOTAL_ICMS']  = 0;
            $ctrc['GRIS']        = 0;
            $Val_min             = 0;
        } else {
            $ctrc['BASE_ICMS']   = $Tabela_Preco['BASE_ICM'];
            $ctrc['DESPACHO']    = $Tabela_Preco['DESPACHO'];
            $ctrc['FRETE_PESO']  = $Tabela_Preco['FRETEPESO'];
            $ctrc['FRETE_VALOR'] = $Tabela_Preco['FRETEVALOR'];
            $ctrc['OUTROS']      = $Tabela_Preco['OUTROS'];
            $ctrc['PEDAGIO']     = $Tabela_Preco['PEDAGIO'];
            $ctrc['SEC_CAT']     = $Tabela_Preco['SEC_CAT'];
            $ctrc['TX_COLETA']   = $Tabela_Preco['TX_COLETA'];
            $ctrc['TX_ENTREGA']  = $Tabela_Preco['TX_ENTREGA'];
            $ctrc['VALOR_FRETE'] = $Tabela_Preco['TOTAL'];
            $ctrc['TOTAL_ICMS']  = $Tabela_Preco['VALOR_ICM'];
            $ctrc['GRIS']        = $Tabela_Preco['GRIS'];
            $Val_min             = $Tabela_Preco['MINIMO'];

            self::CalculaFrete($XIcmsIncluso, $Val_min, $xValRed, $ctrc);
        }

        $ctrc['VALOR_RECEBER'] = $ctrc['VALOR_FRETE'];
        $pesocub = $ctrc['CUBAGEM'];
        self::CalcularPartilha($req, $ctrc);

        $rt['response'] = $ctrc;
        return $rt;
    }

    //CALCULA O FRETE
    public static function CalculaFrete($IcmsIncluso, $VMinimo, $RedBC, &$ctrc){
        $xminimo = false;
        $ctrc['VALOR_FRETE'] =
            $ctrc['FRETE_VALOR'] + $ctrc['ADVALOREM'] +
            $ctrc['DESPACHO']    + $ctrc['SEC_CAT'] +
            $ctrc['PEDAGIO']     + $ctrc['OUTROS'] +
            $ctrc['FRETE_PESO']  + $ctrc['MANTA'] +
            $ctrc['EMBALAGEM']   + $ctrc['TX_COLETA'] +
            $ctrc['TX_ENTREGA']  + $ctrc['GRIS'];

        if(!$IcmsIncluso) $Base = $ctrc['VALOR_FRETE'];
        else $Base = $ctrc['VALOR_FRETE'] / ((100-$ctrc['ALIQUOTA_ICMS']) /100);

        if($VMinimo > $Base && $Base > 0){
            $ctrc['OUTROS'] = $VMinimo - $Base;
            $Base = $VMinimo;
        }

        $ctrc['BASE_ICMS'] = $Base * ((100-$RedBC)/100);
        $ctrc['TOTAL_ICMS'] = $ctrc['BASE_ICMS'] * $ctrc['ALIQUOTA_ICMS'] / 100;
        $ctrc['VALOR_RECEBER'] = $ctrc['VALOR_FRETE'];
        $ctrc['VALOR_FRETE'] = $Base;
    }

    //CALCULA A CUBAGEM
    public static function CalcularCubagem(Request &$req, &$ctrc){
        $Totcub = 0; $xtotAlt = 0; $xtotComp = 0; $xtotLarg = 0;
        $ctrc['ALT_CUB'] = 0; $ctrc['LARG_CUB'] = 0; $ctrc['COMP_CUB'] = 0;

        if(empty($req['cubagem'])) return false;
        $sql = "
        SELECT IND_CUB FROM TABELA_PRECO WHERE CLIENTE = '{$ctrc['CONSIGNATARIO']}'
        AND ORIGEM = '{$req['TX_COLETA']}' AND DESTINO = '{$req['TX_ENTREGA']}' AND TIPO = 2";

        $tindc = DB::select($sql);
        $tindc = $tindc ? (array) $tindc[0] : ['IND_CUB' => 0];

        if($tindc['IND_CUB'] > 0) $Val_ind = $tindc['IND_CUB'];
        else{
            $sql = "
            SELECT IND_CUB FROM TABELA_PRECO WHERE CLIENTE = 'TABELA_PADRAO'
            AND ORIGEM = '{$req['TX_COLETA']}' AND DESTINO = '{$req['TX_ENTREGA']}' AND TIPO = '2'";
            $tindc = DB::select($sql);
            $tindc = $tindc ? (array) $tindc[0] : ['IND_CUB'=>0];

            if($tindc['IND_CUB'] > 0) $Val_ind = $tindc['IND_CUB'];
            else $Val_ind = 0;
        }

        $ctrc['ALT_CUB'] = 0;
        $ctrc['LARG_CUB'] = 0;
        $TotalGeral = 0;
        foreach($req['cubagem'] as $cubagem){
            $ctrc['ALT_CUB'] += FormatControll::format($cubagem['ALTURA'], 'numeric');
            $ctrc['LARG_CUB'] += FormatControll::format($cubagem['LARGURA'], 'numeric');
            $TotalGeral += FormatControll::format($cubagem['TOTAL'], 'numeric');
        }

        if($TotalGeral > 0){
            $ctrc['COMP_CUB'] = $TotalGeral / ($ctrc['ALT_CUB'] * $ctrc['LARG_CUB']);
        } else $ctrc['COMP_CUB'] = 0;

        $ctrc['CUBAGEM'] = $TotalGeral * $Val_ind;
        if($ctrc['CUBAGEM'] <= 0) $ctrc['CUBAGEM'] = $ctrc['PESO'];
    }

    //CALCULA A PARTILHA
    public static function CalcularPartilha(Request &$req, &$ctrc){
        $xI = 0;
        if(
            $req->FINDALIDADE == 1 && $req->TIPOSERVICO == 1 && $req->COLETA != '' &&
            $req->ENTREGA != '' && $req->CONSIGNATARIO != '' && $req->TX_UF_COLETA != $req->TX_UF_ENTREGA &&
            ($req->CONDICAO_TRIBUTARIA == 9 || ($req->CONDICAO_TRIBUTARIA == 1 && ($req->UF_CONSI != $req->TX_UF_ENTREGA))) &&
            $req->ColetaALIQEST > 0 && $req->EntregaALIQINTER > 0
        ){
            $vBC = $ctrc['BASE_ICMS'] > 0 ? $ctrc['BASE_ICMS'] : $ctrc['VALOR_FRETE'];
            $ALIQINTER = $req->EntregaALIQINTER;
            $ALIQEST = $req->ColetaALIQEST;

            switch($ctrc['EMISSAO']){
                case $ctrc['EMISSAO'] <= 2015: $vPerc = 0; break;
                case 2016: $vPerc = 40; break;
                case 2017: $vPerc = 60; break;
                case 2018: $vPerc = 80; break;
                default: $vPerc = 100; break;
            }

            $vCompartilhado = ($vBC * ($ALIQINTER - $ALIQEST)/100);
            $ctrc['CtrcFCPUFDEST'] = $vBC * $req->FECOP / 100;
            $ctrc['ICMSUFDEST'] = $vCompartilhado * $vPerc / 100;
            $ctrc['ICMSUFREMET'] = $vCompartilhado - $ctrc['ICMSUFDEST'];
            $ObsPartilha = "PARTILHA: {$vPerc}%, ICMS_Inter: {$ALIQEST}%, FECOP: {$ctrc['FCPUFDEST']}, ";
            $ObsPartilha .= "ICMS_Dest: {$ctrc['ICMSUFDEST']}, ICMS_Remet: {$ctrc['ICMSUFREMET']}|";

            $req->OBSERVACAO = $ObsPartilha;
        }
    }
}
