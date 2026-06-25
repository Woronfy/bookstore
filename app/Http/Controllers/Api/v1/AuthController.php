<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\VerifyTwoFactorRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected TwoFactorService $twoFactorService;

    public function __construct(TwoFactorService $twoFactorService)
    {
        $this->twoFactorService = $twoFactorService;
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $author = Author::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'nickname'   => $validated['nickname'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => trans('auth.registration.success'),
            'author'  => new AuthorResource($author),
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $author = Author::where('email', $validated['email'])->first();

        if (!$author || !Hash::check($validated['password'], $author->password)) {
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
        $validated = $request->validated();

        $author = Author::where('email', $validated['email'])->first();

        if (!$author) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.2fa.user_not_found')],
            ]);
        }

        $this->twoFactorService->verifyCode($author->email, $validated['code']);

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
        $author = $request->user();
        $data = $request->validated();

        if (isset($data['first_name'])) {
            $author->first_name = $data['first_name'];
        }
        if (isset($data['last_name'])) {
            $author->last_name = $data['last_name'];
        }
        if (isset($data['nickname'])) {
            $author->nickname = $data['nickname'];
        }
        if (isset($data['email'])) {
            $author->email = $data['email'];
        }
        if (isset($data['password'])) {
            $author->password = Hash::make($data['password']);
        }
        $author->save();

        return response()->json([
            'message' => trans('auth.profile.updated'),
            'author'  => new AuthorResource($author),
        ]);
    }
}