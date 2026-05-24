<?php

namespace App\Services\Admin;

use App\Models\Setting;
use Illuminate\Support\Collection;

class SettingService
{
    public function getSettingsByGroup(string $group): Collection
    {
        return Setting::where('group', $group)->pluck('value', 'key');
    }

    public function updateFooterSettings(array $data): void
    {
        $allowedKeys = [
            'company_email',
            'company_phone',
            'company_address',
        ];

        foreach ($allowedKeys as $key) {
            Setting::updateOrCreate(
                [
                    'key' => $key,
                ],
                [
                    'value' => $data[$key] ?? null,
                    'group' => 'footer',
                ]
            );
        }
    }
}