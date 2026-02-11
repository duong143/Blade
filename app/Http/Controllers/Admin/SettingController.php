<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function editFooter()
    {
        $settings = Setting::where('group', 'footer')
            ->pluck('value', 'key');

        return view('admin.settings.footer', compact('settings'));
    }

    public function updateFooter(Request $request)
    {
        $data = $request->only([
            'company_email',
            'company_phone',
            'company_address',
        ]);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'group' => 'footer',
                ]
            );
        }

        return redirect()->back()->with('success', 'Cập nhật footer thành công');
    }
}
