<?php
namespace App\Http\Controllers;

use Auth;
use View;
use Format;
use App\Tabelas;
use Illuminate\Http\Request;

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

    public function viewCte(Request $req, $chave){
        $dtChave = substr($chave, 2, 4);

        if(file_exists( storage_path("app/CTe/aprovadas/{$dtChave}/{$chave}-protcte.xml") )){
            $content = file_get_contents( storage_path("app/CTe/aprovadas/{$dtChave}/{$chave}-protcte.xml") );
        } elseif(file_exists( storage_path("app/CTe/xml/{$dtChave}/{$chave}-cte.xml") )) {
            $content = file_get_contents( storage_path("app/CTe/xml/{$dtChave}/{$chave}-cte.xml") );
        } else {
            $dados = ['title'=>'404', 'msg_error'=>'Arquivo <b>CT-e</b> não encontrado!'];
            return response()->view('error', $dados, 404);
        }

        return response( $content )->header('Content-type', 'application/xml');
    }

    public function lista_post(Request $req, $name){
        //CRIANDO O ARRAY DE RETORNO E SETANDO OS DADOS DEFAULT
        $retorno = ['draw' => $req->draw, 'recordsTotal' => 0, 'recordsFiltered' => 0];
        $optPesquisa = @Tabelas::$pesquisa[ strtoupper($name) ];
        $optTabela = @Tabelas::$options[ strtoupper($name) ];

        //VERIFICANDO SE É UMA PAGINA DE CADASTRO OU NÃO
        if(!isset($optTabela['cadastro']) || $optTabela['cadastro']){
            $data = $this::getDataPage('cadastro_' . $name);
        } else {
            $data = $this::getDataPage($name);
        }

        //SETANDO VALORES DEFAULT
        $where = null;
        $cc = 0;

        //VERIFICANDO SE TEM ALGUM RETORNO OU NÃO DO getDataPage
        if (!is_null($data) && isset($data['table'])) {
            //VERIFICANDO SE FOI PREENCHIDO ALGO NA PESQUISA
            if(isset($req->search['value']) && $req->search['value'] != ''){
                $where = [];
                if(is_numeric($req->search['value'])) $where[Tabelas::$pesquisa[ strtoupper($name) ]['int']] = $req->search['value'];
                else $where[Tabelas::$pesquisa[ strtoupper($name) ]['str']] = ['condicao' => 'LIKE', 'value' => $req->search['value'] . '%'];
            }


            $order = null; //ORDENANDO OS DADOS
            if(isset($req->order) && count($req->order) > 0){
                $column = array_keys(Tabelas::$titulosLista[ strtoupper($name) ])[$req->order[0]['column']];
                $order = ['value'=>$column, 'type'=>$req->order[0]['dir']];
            }

            //EXECUTANDO O SELECT PARA PARA EXIBIR OS DADOS
            $join = isset($optPesquisa['join']) ? $optPesquisa['join'] : null;
            $qry = DefRequestController::listReturn($data['table'], $req->start, $req->length, $where, true, $order, $join);
            $rr1 = array();

            //RECEBENDO A QUANTIDADE TOTAL DE REGISTROS
            $cc = $qry['count_total'];
            unset($qry['count_total']);

            foreach ($qry as $ir=>$rr) {
                $rr = array_map('utf8_encode', (array) $rr);

                foreach($rr as $irr=>$vrr){
                    $type = isset($optTabela['types'][$irr]) ? $optTabela['types'][$irr] : 'string';
                    $rr[$irr] = Format::HTML($vrr, $type);
                }

                $rr1[] = $rr;
            }

            $qry = $rr1;
        } else {
            $qry = [];
        }

        $retorno['data'] = $qry;
        $retorno['recordsFiltered'] = $cc;
        return response($retorno)->header('Content-type', 'application/json');
    }

    public function lista(Request $req, $name) {
        $view = 'cadastros.list';
        if (View::exists($view)) {
            $pk = Tabelas::$pesquisa[strtoupper($name)]['int'];
            $data_view = [
                'name' => $name,
                'header' => $this::getHeaderList($name),
                'ordem' => Tabelas::$ordem[ strtoupper($name) ],
                'options' =>  @Tabelas::$options[ strtoupper($name) ],
                'pk' => $pk,
            ];

            return self::returnView($view, $data_view, 'dataList');
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
            case 'ctrc':
                $rt = ['table'=>'ctrc', 'primary_key'=>'CODIGO'];
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
