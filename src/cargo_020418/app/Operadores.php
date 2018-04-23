<?php

namespace App;

use Format;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class Operadores extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'CODIGO';
    protected $guard = 'operador';
    protected $table = 'OPERADOR';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'CODIGO', 'NOME', 'SENHA',
    ];

    public function getPassWord(){
        return Format::Cryptografa($this->SENHA);
    }

    public function getFilial(){
        $filial = $this->minhaFilial();
        return DB::select('SELECT * FROM FILIAIS WHERE CODIGO = ?', [$filial])[0];
    }

    public function minhaFilial(){
        $rt = DB::select('SELECT COD1 FROM PARAMETROS WHERE CODIGO = ?', ['MINHAFILIAL'])[0];
        return $rt->COD1;
    }
}
