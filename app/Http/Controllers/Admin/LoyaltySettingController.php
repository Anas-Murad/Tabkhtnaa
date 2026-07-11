<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoyaltySetting;
use Illuminate\Http\Request;

class LoyaltySettingController extends Controller
{
    public function edit()
    {
        $settings = LoyaltySetting::firstOrCreate([]);

        return view('admin.loyalty.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'points_per_dinar' => ['required', 'integer', 'min:1'],
            'min_redemption_points' => ['required', 'integer', 'min:0'],
            'points_expiry_months' => ['required', 'integer', 'min:1', 'max:60'],
            'referral_referrer_points' => ['required', 'integer', 'min:0'],
            'referral_referred_points' => ['required', 'integer', 'min:0'],
            'welcome_bonus_points' => ['required', 'integer', 'min:0'],
            'birthday_bonus_points' => ['required', 'integer', 'min:0'],
            'enable_points_system' => ['nullable', 'boolean'],
            'enable_min_redemption' => ['nullable', 'boolean'],
            'enable_expiry' => ['nullable', 'boolean'],
            'enable_auto_redemption' => ['nullable', 'boolean'],
            'enable_double_points' => ['nullable', 'boolean'],
            'enable_tiers' => ['nullable', 'boolean'],
            'enable_welcome_bonus' => ['nullable', 'boolean'],
            'enable_birthday_bonus' => ['nullable', 'boolean'],
            'enable_referral' => ['nullable', 'boolean'],
        ]);

        foreach ([
            'enable_points_system',
            'enable_min_redemption',
            'enable_expiry',
            'enable_auto_redemption',
            'enable_double_points',
            'enable_tiers',
            'enable_welcome_bonus',
            'enable_birthday_bonus',
            'enable_referral',
        ] as $flag) {
            $data[$flag] = $request->boolean($flag);
        }

        LoyaltySetting::firstOrCreate([])->update($data);
        session()->flash('Success', 'تم حفظ إعدادات نظام الولاء بنجاح');

        return back();
    }
}
