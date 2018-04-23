<?php

namespace App\Http\Controllers;

use Auth;
use View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Form;
//use Illuminate\Support\Facades\Auth;

class PaginasController extends Controller
{
    public function __construct(Request $req){

    }

    public function Inicio(){
        return View('Inicio');
    }

    public function login(){
        if(!Auth::guard('operador')->guest()) return redirect()->route('home');
        else return View('layouts.login');
    }

    public function cadastros(Request $req, $name, $codigo=null){
        $data = $this::getDataPage('cadastro_' . $name);

        if(!empty($codigo) && !is_null($data)) $qry = self::getData($data['table'], 0, 1, [$data['primary_key']=> $codigo]);
        else $qry = null;

        $view = 'cadastros.' . $name;
        if(View::exists($view)) return self::returnView($view, $qry, $name);
        else return response()->view('error', ['title'=>'404'], 404);
    }

    private static function getDataPage($page){
        $rt = null;
        switch($page){
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

    private static function returnView($nameView, $var, $nameVar){
        $invalidSearch = ($var === false ? 'false' : 'true');
        $var = ($var === false ? null : $var);
        return View($nameView, [$nameVar=>$var, 'invalidSearch'=>$invalidSearch]);
    }

    private static function getData($tabela, $offset, $limit, $where=null){
        $qry = DefRequestController::listReturn($tabela, $offset, $limit, $where);

        if(count($qry) > 0 && !is_string($qry)) $return = $qry[0];
        else $return = false;

        return $return;
    }
}
