<?php
class teste{
    static $var = 'woow';

    public static function retorn(){
        return new teste;
    }
}

$var1 = teste::retorn();

$var2 = new teste;
$var2 = $var2::retorn();

$var2::$var = 'testando';

echo $var2::$var;
