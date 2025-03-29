<?php

namespace App\Exceptions\children;

use Exception;

abstract class ChildException extends Exception
{
    protected $code = 400;
}
