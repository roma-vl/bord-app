<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
   public function up(): void
    {
        $this->migrator->add('general.site_name', 'Spatie');
        $this->migrator->add('general.maintenance_mode', true);

        $this->migrator->add('user.allow_registration', true);
        $this->migrator->add('user.require_email_verification', true);

    }
};

