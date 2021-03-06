<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Função utilizada para capturar e
     * retornar os itens do menu
     */
    public static function getMenu($url){
        $res = DB::table('MENU')->where('SITUACAO', 1)->orderBy('ORDEM', 'ASC');
        $retorno = $res->connection->select($res->toSql(), $res->getBindings());

        $pai = [];
        $menuList = ['LIST'=>['namelist'=>'list']];

        foreach($retorno as $menu){
            $menu = (array) $menu;

            if($menu['PAI'] == 0){
                $menu['NOME'] = utf8_encode($menu['NOME']);
                $pai[$menu['SEQUENCIA']]['data'] = $menu;
                $pai[$menu['SEQUENCIA']]['CAMINHO'] = 'Home/' . $menu['NOME'];
                $menuList[] = &$pai[$menu['SEQUENCIA']];
                $menuList['LIST'][$url . $menu['URL']] = $menu;
            }
        }

        foreach($retorno as $menu){
            $menu = (array) $menu;
            if($menu['PAI'] != 0) {
                $paiMenu = $menu['PAI'];
                $menuPai = &$pai[$paiMenu];

                if(!isset($menuPai['FILHO'])) $menuPai['FILHO'] = [];

                $menu['NOME'] = utf8_encode($menu['NOME']);
                $menu['CAMINHO'] = $menuPai['CAMINHO'] . '/' . $menu['NOME'];
                $menuPai['FILHO'][] = $menu;
                $menuList['LIST'][$url . $menu['URL']] = $menu;
            }
        }

        return $menuList;
    }
}
