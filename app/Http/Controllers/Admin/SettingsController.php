<?php
// app/Http/Controllers/Admin/SettingsController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateGeneralSettingsRequest;
use App\Http\Requests\Admin\UpdateUserSettingsRequest;
use App\Http\Services\SettingsService;
use App\Settings\GeneralSettings;
use App\Settings\UserSettings;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function index()
    {
        $groups = SettingsService::listGroups();
        return Inertia::render('Admin/Settings/Index', compact('groups'));
    }
    public function edit(string $group)
    {
        $data = SettingsService::getGroupData($group);
        return Inertia::render('Admin/Settings/Edit', [
            'group' => $group,
            'settings' => $data,
        ]);
    }

    public function updateGeneral(GeneralSettings $group, UpdateGeneralSettingsRequest $request)
    {
        SettingsService::saveGeneralData($group, $request);
        return redirect()->back();

    }

    public function updateUser(UserSettings $group, UpdateUserSettingsRequest $request)
    {
        SettingsService::saveUserData($group, $request);

        return redirect()->back();
    }

}
