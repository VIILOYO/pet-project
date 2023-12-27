<?php

namespace App\Exceptions\Auth;

use App\Exceptions\AppHttpException;

class IncorrectLoginDataException extends AppHttpException
{
    protected $code = 401;

    protected $message = 'exceptions.auth.incorrectLoginData';
}
