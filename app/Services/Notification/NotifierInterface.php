<?php

namespace App\Services\Notification;

interface NotifierInterface
{
    public function send(string $recipientEmail, string $code): bool;
}