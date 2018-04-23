<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use App\Providers\Validate\OperadorValidateProvider;

class OperadorProvider extends ServiceProvider
{
    public function boot(){
        Auth::provider('operador-provider', function($app, array $config){
            return new OperadorValidateProvider($config['model']);
        });
    }
}
