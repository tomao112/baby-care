<?php

namespace App\Exceptions\Auth;

class AccountLockedException extends AuthException
{
    protected $message = 'アカウントがロックされています。';
    protected $code = 423;
}
