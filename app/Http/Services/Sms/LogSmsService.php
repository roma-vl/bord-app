<?php

namespace App\Http\Services\Sms;

use App\Contracts\SmsServiceInterface;
use Illuminate\Support\Facades\Log;

class LogSmsService implements SmsServiceInterface
{
    public function sendVerifyCode(string $phone, string $code): void
    {
        Log::info("SMS sent to $phone via Log with code: $code");
    }
}
