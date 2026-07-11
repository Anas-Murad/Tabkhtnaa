<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyTier;
use App\Models\LoyaltyTransaction;
use App\Models\Order;
use App\Services\LoyaltyService;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{
    use HelperTrait;

    public function __construct(private readonly LoyaltyService $loyalty)
    {
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        $this->loyalty->ensureReferralCode($user);
        $this->loyalty->applyBirthdayBonus($user->fresh());

        return $this->returnDataArray($this->loyalty->profilePayload($user->fresh()));
    }

    public function tiers()
    {
        $tiers = LoyaltyTier::where('is_active', true)->orderBy('level')->get();

        return $this->returnDataArray($tiers);
    }

    public function transactions(Request $request)
    {
        $user = $request->user();
        $query = LoyaltyTransaction::where('user_id', $user->id)->orderByDesc('created_at');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $transactions = $query->simplePaginate((int) ($request->per_page ?? 20));

        return $this->returnPaginateData($transactions);
    }

    public function expiryInfo(Request $request)
    {
        $payload = $this->loyalty->profilePayload($request->user());

        return $this->returnDataArray([
            'expiry_days_remaining' => $payload['expiry_days_remaining'],
            'expiry_date' => $payload['expiry_date'],
            'is_expiring_soon' => $payload['is_expiring_soon'],
            'total_points' => $payload['total_points'],
        ]);
    }

    public function calculateRedeem(Request $request)
    {
        $request->validate([
            'points_to_redeem' => ['required', 'integer', 'min:1'],
            'order_id' => ['nullable', 'integer', 'exists:orders,id'],
        ]);

        try {
            $user = $request->user();
            $order = null;
            if ($request->filled('order_id')) {
                $order = Order::where('user_id', $user->id)->findOrFail($request->order_id);
            }

            return $this->returnDataArray(
                $this->loyalty->calculateRedeem($user, (int) $request->points_to_redeem, $order)
            );
        } catch (\RuntimeException $e) {
            return $this->returnError($e->getMessage());
        }
    }

    public function redeem(Request $request)
    {
        $request->validate([
            'points_to_redeem' => ['required', 'integer', 'min:1'],
            'order_id' => ['nullable', 'integer', 'exists:orders,id'],
        ]);

        try {
            $result = $this->loyalty->redeemPoints(
                $request->user(),
                (int) $request->points_to_redeem,
                $request->order_id
            );

            return $this->returnDataArray($result, 'تم تطبيق الخصم بنجاح');
        } catch (\RuntimeException $e) {
            return $this->returnError($e->getMessage());
        }
    }

    public function activeCampaigns()
    {
        return $this->returnDataArray($this->loyalty->activeCampaigns());
    }

    public function referralCode(Request $request)
    {
        $user = $request->user();
        $code = $this->loyalty->ensureReferralCode($user);

        return $this->returnDataArray(['referral_code' => $code]);
    }

    public function useReferralCode(Request $request)
    {
        $request->validate([
            'referral_code' => ['required', 'string', 'max:32'],
        ]);

        try {
            $result = $this->loyalty->useReferralCode($request->user(), $request->referral_code);

            return $this->returnDataArray($result, 'تم تطبيق كود الإحالة بنجاح');
        } catch (\RuntimeException $e) {
            return $this->returnError($e->getMessage());
        }
    }

    public function rewards(Request $request)
    {
        $settings = $this->loyalty->settings();

        return $this->returnDataArray([
            'welcome_bonus_points' => (int) $settings->welcome_bonus_points,
            'birthday_bonus_points' => (int) $settings->birthday_bonus_points,
            'referral_referrer_points' => (int) $settings->referral_referrer_points,
            'referral_referred_points' => (int) $settings->referral_referred_points,
            'enable_welcome_bonus' => (bool) $settings->enable_welcome_bonus,
            'enable_birthday_bonus' => (bool) $settings->enable_birthday_bonus,
            'enable_referral' => (bool) $settings->enable_referral,
        ]);
    }
}
