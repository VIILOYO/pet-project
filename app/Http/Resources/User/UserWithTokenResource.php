<?php

namespace App\Http\Resources\User;

use App\Domain\Entities\User\UserWithTokenInfo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin UserWithTokenInfo
 */
class UserWithTokenResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array<string, UserResource|string>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => UserResource::make($this->user),
            'token' => $this->token
        ];
    }
}
