<?php
namespace App\Http\Controllers;

use Auth;
use View;
use App\Tabelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Form;

class PaginasController extends Controller
{
    public function __construct(Request $req){
    }

    public function Inicio(){
        return View('Inicio');
    }

    public function login(){
        if (!Auth::guard('operador')->guest()) {
            return redirect()->route('home');
        } else {
            return View('layouts.login');
        }
    }

    public function lista_post(Request $req, $name){
        $retorno = ['draw' => $req->draw, 'recordsTotal' => 0, 'recordsFiltered' => 0];
        $data = $this::getDataPage('cadastro_' . $name);
        $where = null;
        $cc = 0;

        if (!is_null($data) && isset($data['table'])) {
            if(isset($req->search['value']) && $req->search['value'] != ''){
                $where = [];
                if(is_numeric($req->search['value'])) $where[Tabelas::$pesquisa[ strtoupper($name) ]['int']] = $req->search['value'];
                else $where[Tabelas::$pesquisa[ strtoupper($name) ]['str']] = ['condicao' => 'LIKE', 'value' => $req->search['value'] . '%'];
            }

            $order = null;
            if(isset($req->order) && count($req->order) > 0){
                $column = array_keys(Tabelas::$titulosLista[ strtoupper($name) ])[$req->order[0]['column']];
                $order = ['value'=>$column, 'type'=>$req->order[0]['dir']];
            }

            $qry = DefRequestController::listReturn($data['table'], $req->start, $req->length, $where, true, $order);
            $rr1 = array();

            $cc = $qry['count_total'];
            unset($qry['count_total']);

            foreach ($qry as $rr) {
                $rr1[] = array_map('utf8_encode', (array) $rr);
            }

            $qry = $rr1;
        } else {
            $qry = [];
        }

        $retorno['data'] = $qry;
        $retorno['recordsFiltered'] = $cc;
        return  json_encode($retorno);
    }

    public function lista(Request $req, $name) {
        $view = 'cadastros.list';
        if (View::exists($view)) {
            $pk = Tabelas::$pesquisa[strtoupper($name)]['int'];
            return self::returnView($view, ['name'=>$name, 'header'=>$this::getHeaderList($name), 'pk'=>$pk], 'dataList');
        } else {
            return response()->view('error', ['title'=>'404'], 404);
        }
    }

    public function cadastros(Request $req, $name, $codigo=null) {
        $data = $this::getDataPage('cadastro_' . $name);

        if (!empty($codigo) && !is_null($data)) {
            $qry = self::getData($data['table'], 0, 1, [$data['primary_key']=> $codigo]);
        } else {
            $qry = null;
        }

        $view = 'cadastros.' . $name;
        if (View::exists($view)) {
            return self::returnView($view, $qry, $name);
        } else {
            return response()->view('error', ['title'=>'404'], 404);
        }
    }

    private static function getHeaderList($page) {
        $rt = Tabelas::$titulosLista[strtoupper($page)];
        return $rt;
    }

    private static function getDataPage($page) {
        $rt = null;
        switch ($page) {
            case 'cadastro_cliente':
                $rt = ['table'=>'cliente', 'primary_key'=>'CNPJ_CPF'];
                break;
            case 'cadastro_operador':
                $rt = ['table'=>'operador', 'primary_key'=>'CODIGO'];
                break;
            case 'cadastro_veiculo':
                $rt = ['table'=>'veiculos', 'primary_key'=>'PLACA'];
                break;
            case 'cadastro_filial':
                $rt = ['table'=>'filiais', 'primary_key'=>'CODIGO'];
                break;
            case 'cadastro_cidade':
                $rt = ['table'=>'cidades', 'primary_key'=>'CODIGO'];
                break;
        }
        return $rt;
    }

    private static function returnView($nameView, $var, $nameVar) {
        $invalidSearch = ($var === false ? 'false' : 'true');
        $var = ($var === false ? null : $var);
        return View($nameView, [$nameVar=>$var, 'invalidSearch'=>$invalidSearch]);
    }

    private static function getData($tabela, $offset, $limit, $where=null) {
        $qry = DefRequestController::listReturn($tabela, $offset, $limit, $where);

        if (count($qry) > 0 && !is_string($qry)) {
            $return = $qry[0];
        } else {
            $return = false;
        }

        return $return;
    }
}
