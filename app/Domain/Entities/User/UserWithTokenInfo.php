<?php

namespace App\Domain\Entities\User;

use App\Models\User;

class UserWithTokenInfo
{
    /**
     * @param User $user
     * @param string $token
     */
    public function __construct(
        public readonly User $user,
        public readonly string $token,
    ) {}
}
