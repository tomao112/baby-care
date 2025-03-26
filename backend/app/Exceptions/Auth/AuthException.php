<?php

namespace App\Exceptions\Auth;

use Exception;

abstract class AuthException extends Exception
{
    protected $code = 400;
}
