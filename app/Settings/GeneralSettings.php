<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_name;

    public bool $maintenance_mode;

    public static function group(): string
    {
        return 'general';
    }
}
