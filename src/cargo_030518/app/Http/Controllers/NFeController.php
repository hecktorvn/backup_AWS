<?php
namespace App\Http\Controllers;

use Auth;
use Format;
use Illuminate\Http\Request;
use NFePHP\CTe\Make as MakeCTe;
use NFePHP\MDFe\Make as MakeMDFe;
use NFePHP\CTe\Tools as ToolsCTe;
use NFePHP\MDFe\Tools as ToolsMDFe;

use NFePHP\Common\Certificate;
use NFePHP\Common\Soap\SoapCurl;

abstract class NFeController extends Controller{
    public $xml = false;
    public $make = false;
    public $tools = false;
    public $chave = false;
    public $conf = false;
    private $type = 'CTe';

    public function start_confg(Bool $config, String $type='CTe'){
        $this->type = $type;
        $filial = Auth::user()->getFilial();
        if($config) $this->config($filial);
    }

    public function config($data){
        $this->conf = [
            "atualizacao" => date('Y-m-d h:i:s'),
            "tpAmb" => 2,
            "razaosocial" => $data->SOCIAL,
            "cnpj" => $data->CNPJ,
            "cUF" => $data->CUF,
            "siglaUF" => $data->UF,
            "schemes" => "PL_".$this->type."_300",
            "versao" => '3.00',
            "proxyConf" => [
                "proxyIp" => "",
                "proxyPort" => "",
                "proxyUser" => "",
                "proxyPass" => ""
            ]
        ];

        //monta o config.json
        $configJson = json_encode($this->conf);

        //carrega o conteudo do certificado.
        //$content = file_get_contents( storage_path('certificados/certificado.pfx') );
        $content = file_get_contents(storage_path('certificados/KADOSHI.pfx'));

        //intancia a classe tools
        //$this->tools = new Tools($configJson, Certificate::readPfx($content, '123456'));
        if($this->type == 'CTe'){
            $this->tools = new ToolsCTe($configJson, Certificate::readPfx($content, 'gleydson'));
            $this->make = new MakeCTe();
        } else if($this->type == 'MDFe'){
            $this->tools = new ToolsMDFe($configJson, Certificate::readPfx($content, 'gleydson'));
            $this->make = new MakeMDFe();
        }
    }
}
