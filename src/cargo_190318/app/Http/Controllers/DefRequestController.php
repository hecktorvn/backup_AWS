<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DefRequestController extends Controller
{
    private $colunas = array();
    public $genID = 0;
    public $errMsg = "";
    private $redUri = "";
    public function default(Request $req, $tab='', $act='', $key='id'){
        /*var_dump($req->all());
        die('teste');*/

        if($tab == '') return redirect()->back()->with("erro", "Tabela inválida");
        if(empty($req->all())) return redirect()->back()->with("erro", "Registros inválidos");

        $this->redUri = $req->route();
        //$this->colunas = DB::getSchemaBuilder()->getColumnListing($tab);
        $return = 0;

        if($act=='ins') $return = $this->insert($req, $tab, $key);
        elseif($act=='upd') $return =  $this->update($req, $tab, $key);
        elseif($act=='del')  $return = $this->delete($req, $tab, $key);
        else return redirect()->back()->with("erro", "Ação não identificada");

        if($return === false) return redirect()->back()->with("erro", $this->errMsg);
        else return redirect()->back()->with("genId", $this->genID);
    }

    public function insert(Request $req, $tab, $key){
        $dados = $req->all();
        try{
            $auto_increment = 0;

            foreach($dados['dataType'] as $iData => $vData){
                if($vData == 'auto_increment' && $auto_increment <= 0) $auto_increment = self::geraCod($tab);
                if($vData == 'auto_increment') $dados[$iData] = $auto_increment;
            }

            $this::checkTypes($dados);
            //$registros = array();
            //foreach($dados as $k=>$v)
            //if(array_search($k, $this->colunas)) $registros[$k] = $v;

            $this->genID = $dados[$key];
            DB::table($tab)->insert($dados);
            return true;
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->errMsg = $ex->getMessage();
            return false;
        }
    }

    public function update(Request $req, $tab='', $key='id'){
        try{
            $dados = $req->all();
            $registros = array();

            $this::checkTypes($dados);
            $id = $dados[$key];

            DB::table($tab)->where($key, $id)->update($dados);
            return true;
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->errMsg = $ex->getMessage();
            return false;
        }
    }

    public function delete(Request $req, $tab=''){
        //$dados = $req->input('all');
        return 'Função ainda não implementada';
    }

    static function list($table,  &$ret='', $offset=0, $limit=1000, $where=null){
        try{
    	    $db = DB::table( strtoupper($table) )->offset($offset)->limit($limit);

            if(is_array($where)){
                foreach($where as $campo=>$valor){
                    $cond = '=';
                    if(is_array($valor)){
                        $val = $valor['value'];
                        $cond = $valor['condicao'];
                    } else {
                        $val = $valor;
                    }

                    $db->where($campo, $cond, strval($val));
                }
            }

            $ret = $db->get();
    	    return true;
        }catch(\Illuminate\Database\QueryException $ex){
            $ret = $ex->getMessage();
            return false;
        }
    }

    public static function checkTypes(&$data){
        if(isset($data['_token'])) unset($data['_token']);
        if (!isset($data['dataType'])){
            FormatControll::valuesFrmt($data);
        } else {
            foreach ($data as $keyItem => $itemDate){
                if ( in_array($keyItem, array_keys($data['dataType'])) ){
                    $data[$keyItem] = FormatControll::format($data[$keyItem], $data['dataType'][$keyItem]);
                } elseif (!is_array($data[$keyItem]) && !is_object($data[$keyItem])){
                    FormatControll::valuesFrmt($data[$keyItem]);
                }
            }
            if(isset($data['dataType'])) unset($data['dataType']);
        }
    }

    static function listReturn($table, $offset=0, $limit=1000, $where){
        $return = [];
        self::list($table, $return, $offset, $limit, $where);
        return $return;
    }

    static function geraCod($tab){
        $novo = DB::select("SELECT NOVOCODIGO FROM SP_GERACODIGO('$tab');");
        return $novo[0]->NOVOCODIGO;
    }
}
