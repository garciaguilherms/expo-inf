<?php

namespace App\Models\Auth;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;

class AuthUser implements AuthenticatableContract
{
    use Authenticatable;

    protected array $fillable = ['id', 'name', 'email', 'password', 'remember_token'];

    public $id;
    public $name;
    public $email;
    public $password;
    public $remember_token;

    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->id;
    }

    public function getAuthPassword(): string
    {
        return $this->password;
    }

    public function getRememberToken(): string
    {
        return $this->remember_token ?? '';
    }

    public function setRememberToken($value): void
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName(): string
    {
        return 'remember_token';
    }
}

