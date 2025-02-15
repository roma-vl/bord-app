<?php

namespace App\Http\Services\Sms;

use App\Contracts\SmsServiceInterface;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class TwilioSmsService implements SmsServiceInterface
{
    public function sendVerifyCode(string $phone, string $code): void
    {
        $config = config('sms.services.twilio');

        if (!$config['sid'] || !$config['token'] || !$config['from']) {
            Log::error("Twilio credentials are missing in configuration.");
            return;
        }

        try {
            $twilio = new Client($config['sid'], $config['token']);
            $twilio->messages->create($phone, [
                'from' => $config['from'],
                'body' => "Your verification code: $code"
            ]);

            Log::info("SMS sent to $phone via Twilio with code: $code");

        } catch (\Exception $e) {
            Log::error("Failed to send SMS: " . $e->getMessage());
        }
    }
}
