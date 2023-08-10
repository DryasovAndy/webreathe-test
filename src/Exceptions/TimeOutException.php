<?php

namespace App\Exceptions;

use Exception;

class TimeOutException extends Exception
{
    protected $message = 'Time out.';
}