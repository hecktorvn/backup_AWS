<?php

namespace App\Http\Controllers;

use Lang;
use Auth;
use Session;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $req){
        if($this->guard()->attempt(['CODIGO'=>$req->username, 'SENHA'=>$req->password])){
            return redirect()->route('home');
        } else {
            return redirect()->route('login')->with(['erro'=>Lang::get('layout.login.error')]);
        }
    }

    protected function guard(){
        return Auth::guard('operador');
    }

    public function logout(){
        $this->guard()->logout();
        return redirect()->route('login');
    }
}
