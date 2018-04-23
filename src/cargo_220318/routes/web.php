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

Route::get('/testes', function(){ return View('teste'); });

//SETANDO O PREFIXO /
Route::group(['prefix' => '/'], function(){
    Route::get('/login', 'PaginasController@login')->name('login');
    Route::post('/login', 'LoginController@login')->name('login');

    Route::group(['middleware' => 'auth:operador'], function(){
        //DESCONECTANDO DA CONTA
        Route::get('/logout', 'LoginController@logout')->name('logout');

        //CHAMADA PARA INICIO DA PAGINA
        Route::get('/', 'PaginasController@Inicio');
        Route::get('/home', 'PaginasController@Inicio')->name('home');

        //EMISSÃO
        Route::get('/emissao/cte', function(){
            return View('emissao_cte');
        });

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
