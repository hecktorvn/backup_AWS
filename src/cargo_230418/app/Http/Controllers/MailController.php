<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Config;
use Auth;

//Mail::send(view, array, function) - envia a partir de um template
//Mail::raw(string, function) - envia a partir de um texto
class MailController extends Controller{
    public $config = [];

    public function __construct(){
        $Email = DB::table('PARAMETROS')->where(['CODIGO'=>'EMAIL'])->get()->toArray();

        //CONFIGURANDO O GRAVANDO OS DADOS DO EMAIL
        Config::set('mail.host', $Email[0]->ALFA2);
        Config::set('mail.port', $Email[0]->COD2);
        Config::set('mail.username', $Email[0]->ALFA3);
        Config::set('mail.password', $Email[0]->ALFA4);
        Config::set('mail.pretend', false);

        $this->config['host'] = Config::get('mail.host');
        $this->config['port'] = Config::get('mail.port');
        $this->config['username'] = Config::get('mail.username');
        $this->config['password'] = Config::get('mail.password');
    }

    public function sendTemplate($template, $data, $assunto, $to, $func=null){
        $config = $this->config;
        Mail::send($template, $data, function($message) use ($config, $assunto, $func, $to){
            $message->from($config['username']);
            $message->to($to);
            $message->subject($assunto);

            if(is_callable($func) && !is_string($func)){
                $func($message);
            }
        });
    }

    public function send($msg, $assunto, $to, $func=null){
        $config = $this->config;
        Mail::raw('', function($message) use ($msg, $config, $assunto, $func, $to){
            $message->from($config['username']);
            $message->to($to);

            //ADICIONANDO ASSUNTO E A MENSAGEM
            $message->subject($assunto);
            $message->setBody($msg, 'text/html');

            if(is_callable($func) && !is_string($func)){
                $func($message);
            }
        });
    }
}
