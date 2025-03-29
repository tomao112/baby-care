<?php

namespace App\Exceptions\Auth;

use Exception;

abstract class ChildException extends Exception
{
    protected $code = 400;
}
