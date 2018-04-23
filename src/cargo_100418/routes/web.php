<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//SETANDO O PREFIXO /
Route::group(['prefix' => '/'], function(){
    Route::get('/login', 'PaginasController@login')->name('login');
    Route::post('/login', 'LoginController@login')->name('login');

    Route::group(['middleware' => 'auth:operador'], function(){
        //EMISSAO DE CTE
        Route::post('/emissao/cte', 'EmissaoController@cte');
        Route::get('/emissao/cte/post', 'EmissaoController@cte');
        Route::post('/emissao/cte/send', 'EmissaoController@enviarCte');
        Route::get('/emissao/cte/send', 'EmissaoController@enviarCte');
        Route::get('/emissao/cte', 'EmissaoController@cteView');
        Route::get('/emissao/cte/{filial}/{codigo}', 'EmissaoController@cteView');

        Route::get('/testes', function(){ return response()->view('teste'); });
        Route::get('/testes/xml', function(){ return response()->view('teste', ['type'=>'xml'])->header('Content-type', 'application/xml'); });

        //DESCONECTANDO DA CONTA
        Route::get('/logout', 'LoginController@logout')->name('logout');

        //CHAMADA PARA INICIO DA PAGINA
        Route::get('/', 'PaginasController@Inicio');
        Route::get('/home', 'PaginasController@Inicio')->name('home');

        //LINK PARA VISUALIZAR O CTE
        Route::get('/cte/{chave}', 'PaginasController@viewcte');

        //CHAMADA PARA CADASTRO
        Route::get('/cadastro/{name}', 'PaginasController@cadastros');
        Route::get('/lista/{name}', 'PaginasController@lista');
        Route::post('/lista/{name}', 'PaginasController@lista_post');

        //LISTAGEM DE DADOS
        Route::get('/cadastro/{name}/{codigo}', 'PaginasController@cadastros');

        //COMANDOS DE AJAX E DEFREQ
        Route::get('/ajax/{obj}/{act}', 'AjaxController@post');
        Route::post('/ajax/{obj}/{act}', 'AjaxController@post');
        Route::get('/ajax/{obj}/{act}/{key}', 'AjaxController@post');
        Route::post('/ajax/{obj}/{act}/{key}', 'AjaxController@post');
        Route::post('/auto/{obj}/{val}', 'AutoCompleteController@get');
        Route::get('/auto/{obj}/{val}', 'AutoCompleteController@get');
        Route::get('/defreq/{tab}/{act}', 'DefRequestController@default');
        Route::post('/defreq/{tab}/{act}', 'DefRequestController@default');
        Route::get('/defreq/{tab}/{act}/{key}', 'DefRequestController@default');
        Route::post('/defreq/{tab}/{act}/{key}', 'DefRequestController@default');
    });
});
