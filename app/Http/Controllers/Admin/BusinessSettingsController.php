<?php

namespace App\Http\Controllers\Admin;

use App\HelperClasses\BusinessSettingHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BusinessSettingsController extends Controller
{
    public function edit()
    {
        $settings = [
            'company_phone' => BusinessSettingHelper::get('company_phone', ''),
            'company_email' => BusinessSettingHelper::get('company_email', ''),
            'company_address' => BusinessSettingHelper::get('company_address', ''),
            'company_whatsapp' => BusinessSettingHelper::get('company_whatsapp', ''),
            'tax_percentage' => BusinessSettingHelper::get('tax_percentage', 15),
            'delivery_fee' => BusinessSettingHelper::get('delivery_fee', 2.5),
        ];

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'company_phone' => ['nullable', 'string', 'max:50'],
            'company_email' => ['nullable', 'email', 'max:255'],
            'company_address' => ['nullable', 'string', 'max:500'],
            'company_whatsapp' => ['nullable', 'string', 'max:50'],
            'tax_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'delivery_fee' => ['required', 'numeric', 'min:0'],
        ]);

        foreach ($data as $key => $value) {
            BusinessSettingHelper::set($key, $value);
        }

        session()->flash('Success', 'Settings updated successfully.');

        return back();
    }
}
