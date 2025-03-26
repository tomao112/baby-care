<?php

namespace App\Exceptions\Auth;

class InvalidCredentialsException extends AuthException
{
    protected $message = 'メールアドレスまたはパスワードが正しくありません。';
    protected $code = 401;
}
