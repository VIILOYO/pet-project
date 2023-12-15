<?php

namespace App\Services\User;

use App\Domain\DTO\User\UserStoreData;
use App\Domain\Entities\User\UserWithTokenInfo;
use App\Models\User;
use App\Repositories\User\UserRepositoryEloquent;
use Illuminate\Support\Facades\Hash;
use Prettus\Validator\Exceptions\ValidatorException;

class AuthService
{
    public function __construct(
        private readonly UserRepositoryEloquent $userRepository
    ) {}

    /**
     * @param UserStoreData $data
     * @return UserWithTokenInfo
     *
     * @throws ValidatorException
     */
    public function registration(UserStoreData $data): UserWithTokenInfo
    {
        /** @var User $user */
        $user = $this->userRepository->create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ]);

        return new UserWithTokenInfo(
            $user,
            $user->createToken('token')->plainTextToken
        );
    }
}
