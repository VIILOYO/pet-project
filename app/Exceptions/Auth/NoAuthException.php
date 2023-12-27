<?php

namespace App\Exceptions\Auth;

use App\Exceptions\AppHttpException;

class NoAuthException extends AppHttpException
{
    protected $code = 403;
    protected $message = 'exceptions.auth.noAuth';
}
