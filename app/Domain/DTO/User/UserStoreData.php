<?php

namespace App\Domain\DTO\User;

use App\Domain\DTO\Abstracts\DTO;

class UserStoreData extends DTO
{
    /**
     * @var string
     */
    public string $name;
    /**
     * @var string
     */
    public string $email;
    /**
     * @var string
     */
    public string $password;
}
