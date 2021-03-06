<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormatControll extends Controller
{
    //FORMATA TODOS OS VALORES
    public static function valuesFrmt(&$value){
        if(!is_array($value)){
            if($value == '' || $value == 'null') $value = null;
            else if(is_numeric($value)) $value = self::format($value, 'numeric');
        }else{
            foreach($value as $i=>$v){
                self::valuesFrmt($value[$i]);
            }
        }
    }

    //FORMATA PARA O FORMATO UTILIZADO NO HTML
    public static function HTML($value, $format){
        $format = strtolower($format);
        if(empty($value)) return '';

        switch($format){
            case 'money':
                $value = self::format($value, 'float');
                $value = number_format($value, 2, ',', '.');
                break;
            case 'date':
                $value = self::format($value, 'date', 'Y-m-d', 'd/m/Y');
                break;
            case 'timestamp':
                $value = self::format($value, 'timestamp', 'Y-m-d', 'd/m/Y');
                break;
            case 'time':
                $value = self::format($value, 'time');
                break;
        }

        return $value;
    }

    //RECEBE O FORMATO E FORMATA DA MEHOR FORMATA
    public static function format($value, $format, $formatData='d/m/Y', $toFormat='Y-m-d'){
        $format = strtolower($format);
        switch($format){
            case 'float':
                $value = self::float($value, $formatData);
                break;
            case 'onlynumber':
                $value = preg_replace('/[^0-9]/', '', $value);
                break;
            case 'date':
                if( empty($value) ) return null;
                $value = trim(preg_replace('/(.*)\s(.*)/', '$1', $value));
                $value = Carbon::createFromFormat($formatData, $value)->format($toFormat);
                break;
            case 'integer':
                $value = preg_replace('/[^0-9,.]/', '', $value);
                $value = preg_replace('/./', '', $value);
                $value = preg_replace('/,/', '.', $value);
                $value = intval($value);
                break;
            case 'timestamp':
                if( empty($value) ) return null;
                if( strlen( preg_replace('/[^:]/', '', $value) ) == 1 ) $value .= date(':s');
                if( strrpos($value, ':') <= -1 ) $value = trim($value) . date(' H:i:s');

                $value = Carbon::createFromFormat($formatData . ' H:i:s', $value)->format($toFormat . ' H:i:s');
                break;
            case 'time':
                if( empty($value) ) return null;
                $value = Carbon::createFromFormat('H:i:s', $value)->format('H:i:s');
                break;
            case 'cryptografa':
                $value = self::Cryptografa($value, 'C');
                break;
            case 'numeric':
                $separator = preg_replace("/(.*)([.]|[,]{1})([0-9]{1,})$/", "$2", $value);
                preg_match("/([0-9\.\,-]+)([.|,])([0-9]+)/", $value, $money_cents);

                if(!isset($money_cents[2]) || $money_cents[2] != $separator) $value = preg_replace('/[^0-9\-]/', '', $value);
                else $value = preg_replace('/[^0-9\-]/', '', $money_cents[1]) . '.' . $money_cents[3];
                break;
        }

        return $value;
    }

    //FORMATA A DATA
    public static function pct($value){
        $value = self::format($value, 'float');
        if($value > 100) $value = 100;
        return $value;
    }

    //FORMATA A DATA
    public static function date($date, $format='d/m/Y'){
        return Carbon::parse($date)->format($format);
    }

    //RETORNA O VALOR EM FLOAT
    public static function float($value, $dcm=null){
        $value = self::format($value, 'numeric');
        if(is_numeric($dcm)) $value = number_format($value, $dcm, '.', '');
        return floatval($value);
    }

    //RETORNA O VALOR EM MONEY
    public static function money($value, $dcm=2){
        return number_format($value, $dcm, ',', '.');
    }

    //CRYPTOGRAFA E DECRIPTOGRAFA O TEXTO
    public static function Cryptografa($Src, $Action='D'){
    	if ($Src == '') return;
    	$Key    = 'YUQL23KL23DF90WI5E1JAS467NMCXXL6JAOAUWWMCL0AOMM4A4VZYW9KHJUI2347EJHJKDF3424SKL K3LAKDJSL9RTIKJ';
    	$Dest   = ''; $KeyLen = strlen($Key); $KeyArr = str_split($Key); $KeyPos = -1; $SrcPos = 0;
    	$SrcAsc = 0; $SrcPos = 2; $Range  = 255;

    	if(strtoupper($Action) == 'D'):
    		$OffSet = hexdec(substr($Src,0,2));
    		while($SrcPos < strlen($Src)):
    			$SrcAsc = hexdec(substr($Src,$SrcPos,2));
    			if ($KeyPos < $KeyLen) $KeyPos = $KeyPos + 1; else $KeyPos = 0;
    			$TmpSrcAsc = $SrcAsc ^ ord($KeyArr[$KeyPos]);
    			if ($TmpSrcAsc <= $OffSet) $TmpSrcAsc = 255 + $TmpSrcAsc - $OffSet;
    			else $TmpSrcAsc = $TmpSrcAsc - $OffSet;
    			$Dest .= chr($TmpSrcAsc);
    			$OffSet = $SrcAsc;
    			$SrcPos = $SrcPos + 2;
    		endwhile; $Result = $Dest;
    	elseif(strtoupper($Action) == 'C' || strtoupper($Action) == 'E'):
    		$OffSet = rand(0, $Range); $SrcArr = str_split($Src);
    		$Dest = self::FormatToHexa($OffSet);
    		for($SrcPos=0;$SrcPos<strlen($Src);$SrcPos++){
    			$SrcAsc = fmod((ord($SrcArr[$SrcPos]) + $OffSet), 255);
    			if($KeyPos < $KeyLen) $KeyPos = $KeyPos + 1; else $KeyPos = 0;
    			$SrcAsc = $SrcAsc ^ ord($KeyArr[$KeyPos]);
    			$Dest  .= self::FormatToHexa($SrcAsc); $OffSet = $SrcAsc;
    		} $Result = strtoupper($Dest);
    	endif;

        return $Result;
    }

    //CRYPTOGRAFA O TEXTO PARA HEXADECIMAL
    public static function FormatToHexa($val){
    	$rt = dechex($val);
        $rt = strlen($rt) < 2 ? '0'.$rt : $rt;
        return $rt;
    }
}
