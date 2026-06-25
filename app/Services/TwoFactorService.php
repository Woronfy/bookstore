<?php

namespace App\Services;

use App\Services\Notification\NotifierInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class TwoFactorService
{
    protected NotifierInterface $notifier;

    public function __construct(NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    public function generateAndSendCode(string $email): string
    {
        $code = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        $cacheKey = "2fa_code_{$email}";
        Cache::put($cacheKey, $code, 600);
        $this->notifier->send($email, $code);
        return $code;
    }

    public function verifyCode(string $email, string $code): void
    {
        $cacheKey = "2fa_code_{$email}";
        $cachedCode = Cache::get($cacheKey);

        if (!$cachedCode || $cachedCode !== $code) {
            throw ValidationException::withMessages([
                'code' => [trans('auth.2fa.invalid')],
            ]);
        }

        Cache::forget($cacheKey);
    }
}