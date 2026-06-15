<?php

namespace App\Services\Notification;

use Illuminate\Support\Facades\Log;

class LogNotifier implements NotifierInterface
{
    public function send(string $recipientEmail, string $code): bool
    {
        Log::channel('stack')->info("2FA code for {$recipientEmail}: {$code}");
        return true;
    }
}