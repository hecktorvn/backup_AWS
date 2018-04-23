<?php

namespace App\Providers\Validate;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class OperadorValidateProvider implements UserProvider
{
    public function __construct($model)
    {
        $this->model = $model;
    }

    public function retrieveById($identifier)
    {
        /* Get by id */
        return $this->createModel()->newQuery()->find($identifier);
    }

    public function retrieveByToken($identifier, $token)
    {
        $model = $this->createModel();

        return $model->newQuery()
            ->where($model->getAuthIdentifierName(), $identifier)
            ->where($model->getRememberTokenName(), $token)
            ->first();
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        //$user->setRememberToken($token);
        //$user->save();
    }

    public function retrieveByCredentials(array $credentials)
    {
        $query = $this->createModel()->newQuery();

        foreach ($credentials as $key => $value) {
            if (!self::contains($key, 'SENHA')) {
                $query->where($key, $value);
            }
        }
        
        return $query->first();
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        /* Match password here */
        return ($credentials["SENHA"] == $user->getPassWord());
    }

    public function createModel()
    {
        $class = '\\'.ltrim($this->model, '\\');
        return new $class;
    }

    static function contains($haystack, $needle)
    {
        return strpos($haystack, $needle) !== false;
    }
}
