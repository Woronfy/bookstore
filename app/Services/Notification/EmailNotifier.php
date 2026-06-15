<?php

namespace App\Services\Notification;

use Illuminate\Support\Facades\Mail;

class EmailNotifier implements NotifierInterface
{
    public function send(string $recipientEmail, string $code): bool
    {
        Mail::raw("Your two-factor authentication code is: {$code}", function ($message) use ($recipientEmail) {
            $message->to($recipientEmail)->subject('Authentication Code');
        });
        return true;
    }
}