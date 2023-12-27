<?php

namespace App\Repositories\User;

use App\Models\User;
use Prettus\Repository\Eloquent\BaseRepository;

class UserRepositoryEloquent extends BaseRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return User::class;
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        /** @var User|null */
        return $this->model->newQuery()
            ->where('email', $email)
            ->first();
    }
}
