<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;

class LoyaltyCustomerController extends Controller
{
    public function addPoints(Request $request, $id, LoyaltyService $loyalty)
    {
        $data = $request->validate([
            'points' => ['required', 'integer', 'min:1'],
            'description' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'in:earn,bonus,referral'],
        ]);

        $user = User::where('type', 'client')->findOrFail($id);
        $loyalty->adminAddPoints($user, (int) $data['points'], $data['description'], $data['type'] ?? 'bonus');

        session()->flash('Success', "تم إضافة {$data['points']} نقطة للعميل");

        return back();
    }

    public function deductPoints(Request $request, $id, LoyaltyService $loyalty)
    {
        $data = $request->validate([
            'points' => ['required', 'integer', 'min:1'],
            'description' => ['required', 'string', 'max:255'],
        ]);

        $user = User::where('type', 'client')->findOrFail($id);
        $loyalty->adminDeductPoints($user, (int) $data['points'], $data['description']);

        session()->flash('Success', "تم خصم {$data['points']} نقطة من العميل");

        return back();
    }

    public function checkTier($id, LoyaltyService $loyalty)
    {
        $user = User::where('type', 'client')->findOrFail($id);
        $updated = $loyalty->checkAndUpdateUserTier($user->fresh());

        session()->flash('Success', $updated ? 'تم تحديث مستوى العميل' : 'لا يوجد ترقية جديدة');

        return back();
    }
}
