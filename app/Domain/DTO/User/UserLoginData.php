<?php

namespace App\Domain\DTO\User;

use App\Domain\DTO\Abstracts\DTO;

class UserLoginData extends DTO
{
    /**
     * @var string
     */
    public string $email;
    /**
     * @var string
     */
    public string $password;
}
