<?php

namespace App\Exceptions\Auth;

class UserAlreadyExistsException extends AuthException
{
    protected $message = 'このメールアドレスは既に登録されています。';
    protected $code = 422;
}
