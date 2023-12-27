<?php

namespace App\Domain\DTO\User;

use App\Domain\DTO\Abstracts\DTO;

class UserStoreData extends UserLoginData
{
    /**
     * @var string
     */
    public string $name;
}
