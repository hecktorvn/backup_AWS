<?php

namespace App;

use Format;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
}
