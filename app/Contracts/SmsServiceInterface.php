<?php

namespace App\Contracts;

interface SmsServiceInterface
{
    public function sendVerifyCode(string $phone, string $code): void;
}
