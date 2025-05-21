<?php

namespace App\Http\Services;

use App\Http\Requests\Admin\UpdateGeneralSettingsRequest;
use App\Http\Requests\Admin\UpdateUserSettingsRequest;
use App\Settings\GeneralSettings;
use App\Settings\UserSettings;

class SettingsService
{
    public static function listGroups(): array
    {
        return [
            'general' => GeneralSettings::class,
            'user' => UserSettings::class,
        ];
    }

    public static function getGroupData(string $group): array
    {
        if (array_key_exists($group, SettingsService::listGroups())) {
            $settings = app(SettingsService::listGroups()[$group]);
        }

        if ($group === 'general') {
            return [
                'site_name' => $settings->site_name,
                'maintenance_mode' => $settings->maintenance_mode,
            ];
        } elseif ($group === 'user') {
            return [
                'allow_registration' => $settings->allow_registration,
                'require_email_verification' => $settings->require_email_verification,
            ];

        }

        return [];
    }

    public static function saveGeneralData(GeneralSettings $settings, UpdateGeneralSettingsRequest $request): void
    {
        $settings->site_name = $request->input('site_name');
        $settings->maintenance_mode = $request->input('maintenance_mode');
        $settings->save();
    }

    public static function saveUserData(UserSettings $settings, UpdateUserSettingsRequest $request): void
    {
        $settings->allow_registration = $request->input('allow_registration');
        $settings->require_email_verification = $request->input('require_email_verification');
        $settings->save();
    }
}
