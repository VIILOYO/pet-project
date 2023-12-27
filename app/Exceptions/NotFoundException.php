<?php

namespace App\Exceptions;
/**
 * Class NotFoundException
 * @package App\Exceptions\Http\Common
 */
class NotFoundException extends AppHttpException
{
    protected $code = "404";

    protected $message = "Объект не найден";
}
