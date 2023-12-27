<?php

namespace App\Http\Controllers\User;

use App\Domain\DTO\User\UserLoginData;
use App\Domain\DTO\User\UserStoreData;
use App\Exceptions\Auth\IncorrectLoginDataException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserWithTokenResource;
use App\Services\User\AuthService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpFoundation\Cookie;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    )
    {
    }

    /**
     * @param RegisterRequest $request
     * @return Response
     *
     * @throws ValidatorException|InvalidArgumentException
     */
    public function registration(RegisterRequest $request): Response
    {
        $userInfo = $this->authService->registration(
            new UserStoreData($request->validated())
        );

        return response(
            new UserWithTokenResource(
                $userInfo
            )
        )->withCookie(
            new Cookie(
                config('auth.cookie_key'),
                $userInfo->token,
                Carbon::now()->addMinutes(
                    config('session.lifetime'),
                ),

            )
        );
    }

    /**
     * @param LoginRequest $request
     * @return Response
     *
     * @throws InvalidArgumentException|IncorrectLoginDataException
     */
    public function login(LoginRequest $request): Response
    {
        $userInfo = $this->authService->login(
            new UserLoginData($request->validated())
        );

        return response(
            new UserWithTokenResource(
                $userInfo
            )
        )->withCookie(
            new Cookie(
                config('auth.cookie_key'),
                $userInfo->token,
                Carbon::now()->addMinutes(
                    config('session.lifetime'),
                ),

            )
        );
    }

    /**
     * @return UserResource
     */
    public function getAuth(): UserResource
    {
        return UserResource::make(
            Auth::user()
        );
    }
}
