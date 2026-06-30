<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\VerifyTwoFactorRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use App\Services\AuthService;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected TwoFactorService $twoFactorService;
    protected AuthService $authService;

    public function __construct(TwoFactorService $twoFactorService, AuthService $authService)
    {
        $this->twoFactorService = $twoFactorService;
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $dto = $request->toDTO();
        $author = $this->authService->register($dto);

        return response()->json([
            'message' => trans('auth.registration.success'),
            'author'  => new AuthorResource($author),
        ], 201);
    }

    public function sendCode(LoginRequest $request)
    {
        $dto = $request->toDTO();

        $author = $this->authService->getAuthorIfCredentialsValid($dto->email, $dto->password);

        if (!$author) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.login.failed')],
            ]);
        }

        $code = $this->twoFactorService->generateAndSendCode($author->email);

        return response()->json([
            'message' => trans('auth.login.code_sent'),
            'debug'   => app()->environment('local') ? $code : null,
        ]);
    }

    public function verifyTwoFactor(VerifyTwoFactorRequest $request)
    {
        $dto = $request->toDTO();

        $author = Author::where('email', $dto->email)->first();

        if (!$author) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.2fa.user_not_found')],
            ]);
        }

        $this->twoFactorService->verifyCode($author->email, $dto->code);

        $token = $author->createToken('auth_token')->plainTextToken;

        return response()->json([
            'author' => new AuthorResource($author),
            'token'  => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => trans('auth.logout.success')]);
    }

    public function profile(Request $request)
    {
        return new AuthorResource($request->user());
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $dto = $request->toDTO();
        $author = $this->authService->updateProfile($request->user(), $dto);

        return response()->json([
            'message' => trans('auth.profile.updated'),
            'author'  => new AuthorResource($author),
        ]);
    }
}