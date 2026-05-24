<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateFooterSettingRequest;
use App\Services\Admin\SettingService;

class SettingController extends Controller
{
    public function __construct(
        protected SettingService $settingService
    ) {}

    public function editFooter()
    {
        $settings = $this->settingService->getSettingsByGroup('footer');

        return view('admin.settings.footer', compact('settings'));
    }

    public function updateFooter(UpdateFooterSettingRequest $request)
    {
        $this->settingService->updateFooterSettings($request->validated());

        return redirect()
            ->back()
            ->with('success', 'Cập nhật footer thành công');
    }
}
