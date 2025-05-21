<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class UserSettings extends Settings
{
    public bool $allow_registration;

    public bool $require_email_verification;

    public static function group(): string
    {
        return 'user';
    }
}
