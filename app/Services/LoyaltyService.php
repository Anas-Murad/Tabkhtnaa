<?php

namespace App\Services;

use App\Models\DoublePointsCampaign;
use App\Models\LoyaltySetting;
use App\Models\LoyaltyTier;
use App\Models\LoyaltyTransaction;
use App\Models\Order;
use App\Models\Redemption;
use App\Models\Referral;
use App\Models\User;
use App\Models\UserTierHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoyaltyService
{
    public function settings(): LoyaltySetting
    {
        return LoyaltySetting::firstOrCreate([], [
            'points_per_dinar' => 1,
            'min_redemption_points' => 100,
            'points_expiry_months' => 12,
        ]);
    }

    public function profilePayload(User $user): array
    {
        $settings = $this->settings();
        $expiry = $this->expiryInfo($user);
        $nextTier = $this->nextTierProgress($user);

        return [
            'total_points' => (int) $user->total_points,
            'points_value' => round((float) $user->total_points, 2),
            'lifetime_spending' => round((float) $user->lifetime_spending, 2),
            'current_tier' => $user->current_tier ?? 'Regular',
            'next_tier' => $nextTier['name'],
            'points_to_next_tier' => $nextTier['points_needed'],
            'progress_percentage' => $nextTier['progress'],
            'min_redemption_points' => $this->minRedemptionForUser($user, $settings),
            'points_per_dinar' => (int) $settings->points_per_dinar,
            'expiry_days_remaining' => $expiry['days_remaining'],
            'expiry_date' => $expiry['expiry_date'],
            'is_expiring_soon' => $expiry['is_expiring_soon'],
            'enable_points_system' => (bool) $settings->enable_points_system,
            'can_apply_referral' => empty($user->referred_by),
            'referral_code' => $user->referral_code,
        ];
    }

    public function awardPoints(
        User $user,
        int $points,
        string $type = 'earn',
        ?string $description = null,
        ?int $orderId = null,
        array $metadata = [],
        bool $updateLifetimeSpending = false,
        float $spendingAmount = 0
    ): bool {
        $settings = $this->settings();
        if (!$settings->enable_points_system || $points <= 0 || $user->type !== 'client') {
            return false;
        }

        DB::transaction(function () use ($user, $points, $type, $description, $orderId, $metadata, $updateLifetimeSpending, $spendingAmount) {
            $user->increment('total_points', $points);
            if ($updateLifetimeSpending && $spendingAmount > 0) {
                $user->increment('lifetime_spending', $spendingAmount);
            }
            LoyaltyTransaction::create([
                'user_id' => $user->id,
                'order_id' => $orderId,
                'points' => $points,
                'type' => $type,
                'description' => $description ?? "اكتساب {$points} نقطة",
                'metadata' => $metadata,
            ]);
            $this->checkAndUpdateUserTier($user->fresh());
        });

        return true;
    }

    public function awardPointsForOrder(Order $order): bool
    {
        if ($order->loyalty_points_awarded) {
            return false;
        }

        $completed = $order->status === 'delivered'
            || ($order->delivery_type === 'pick_up' && $order->status === 'prepared');
        if (!$completed) {
            return false;
        }

        $user = User::find($order->user_id);
        if (!$user || $user->type !== 'client') {
            return false;
        }

        $settings = $this->settings();
        if (!$settings->enable_points_system) {
            return false;
        }

        $basePoints = (int) floor((float) $order->total * (int) $settings->points_per_dinar);
        if ($basePoints <= 0) {
            return false;
        }

        $tierMultiplier = $this->tierMultiplier($user);
        $campaignMultiplier = $this->campaignMultiplier($order->delivery_type);
        $points = (int) floor($basePoints * $tierMultiplier * $campaignMultiplier);

        $this->awardPoints(
            $user,
            $points,
            'earn',
            "نقاط من طلب #{$order->id}",
            $order->id,
            [
                'tier_multiplier' => $tierMultiplier,
                'campaign_multiplier' => $campaignMultiplier,
            ],
            true,
            (float) $order->total
        );

        $order->update([
            'points_earned' => $points,
            'loyalty_points_awarded' => true,
        ]);

        return true;
    }

    public function applyWelcomeBonus(User $user): void
    {
        $settings = $this->settings();
        if (!$settings->enable_welcome_bonus || !$settings->enable_points_system || $user->type !== 'client') {
            return;
        }
        if ($user->welcome_bonus_awarded) {
            return;
        }

        $this->awardPoints(
            $user,
            (int) $settings->welcome_bonus_points,
            'welcome',
            'مكافأة ترحيب'
        );
        $user->update(['welcome_bonus_awarded' => true]);
    }

    public function applyBirthdayBonus(User $user): void
    {
        $settings = $this->settings();
        if (!$settings->enable_birthday_bonus || !$settings->enable_points_system || !$user->dob) {
            return;
        }

        $today = now();
        $dob = Carbon::parse($user->dob);
        if ($dob->month !== $today->month || $dob->day !== $today->day) {
            return;
        }

        $alreadyAwarded = LoyaltyTransaction::where('user_id', $user->id)
            ->where('type', 'birthday')
            ->whereYear('created_at', $today->year)
            ->exists();
        if ($alreadyAwarded) {
            return;
        }

        $multiplier = $this->tierBirthdayMultiplier($user);
        $points = (int) floor($settings->birthday_bonus_points * $multiplier);
        $this->awardPoints($user, $points, 'birthday', 'مكافأة عيد الميلاد');
    }

    public function ensureReferralCode(User $user): string
    {
        if ($user->referral_code) {
            return $user->referral_code;
        }

        do {
            $code = strtoupper(Str::substr(Str::slug($user->name ?: 'USER', ''), 0, 4)) . random_int(1000, 9999);
        } while (User::where('referral_code', $code)->exists());

        $user->update(['referral_code' => $code]);

        return $code;
    }

    public function useReferralCode(User $user, string $referralCode): array
    {
        $settings = $this->settings();
        if (!$settings->enable_referral || !$settings->enable_points_system) {
            throw new \RuntimeException('برنامج الإحالة غير مفعّل');
        }
        if ($user->referred_by) {
            throw new \RuntimeException('تم استخدام كود إحالة مسبقاً');
        }

        $referrer = User::where('referral_code', strtoupper(trim($referralCode)))
            ->where('id', '!=', $user->id)
            ->first();
        if (!$referrer) {
            throw new \RuntimeException('كود الإحالة غير صالح');
        }

        DB::transaction(function () use ($user, $referrer, $settings) {
            $user->update(['referred_by' => $referrer->id]);
            Referral::create([
                'referrer_id' => $referrer->id,
                'referred_id' => $user->id,
                'points_awarded_to_referrer' => (int) $settings->referral_referrer_points,
                'points_awarded_to_referred' => (int) $settings->referral_referred_points,
            ]);
            $this->awardPoints(
                $referrer,
                (int) $settings->referral_referrer_points,
                'referral',
                "مكافأة إحالة - {$user->name}"
            );
            $this->awardPoints(
                $user,
                (int) $settings->referral_referred_points,
                'referral',
                "مكافأة انضمام عبر إحالة"
            );
        });

        return [
            'referrer_name' => $referrer->name,
            'points_awarded' => (int) $settings->referral_referred_points,
        ];
    }

    public function calculateRedeem(User $user, int $pointsToRedeem, ?Order $order = null): array
    {
        $settings = $this->settings();
        $this->validateRedemption($user, $pointsToRedeem, $settings);

        $discountAmount = round($pointsToRedeem, 2);
        $orderTotal = $order ? (float) $order->total : null;
        if ($orderTotal !== null) {
            $discountAmount = min($discountAmount, $orderTotal);
            $pointsToRedeem = (int) floor($discountAmount);
        }

        return [
            'points_to_redeem' => $pointsToRedeem,
            'discount_amount' => $discountAmount,
            'remaining_points' => max(0, (int) $user->total_points - $pointsToRedeem),
            'final_order_amount' => $orderTotal !== null ? max(0, $orderTotal - $discountAmount) : null,
        ];
    }

    public function redeemPoints(User $user, int $pointsToRedeem, ?int $orderId = null): array
    {
        $settings = $this->settings();
        $this->validateRedemption($user, $pointsToRedeem, $settings);

        $order = null;
        if ($orderId) {
            $order = Order::where('user_id', $user->id)->findOrFail($orderId);
        }

        $preview = $this->calculateRedeem($user, $pointsToRedeem, $order);
        $pointsToRedeem = (int) $preview['points_to_redeem'];
        $discountAmount = (float) $preview['discount_amount'];

        DB::transaction(function () use ($user, $pointsToRedeem, $discountAmount, $order, $orderId) {
            $user->decrement('total_points', $pointsToRedeem);

            $redemption = Redemption::create([
                'user_id' => $user->id,
                'order_id' => $orderId,
                'points_used' => $pointsToRedeem,
                'amount_discounted' => $discountAmount,
                'status' => 'applied',
            ]);

            LoyaltyTransaction::create([
                'user_id' => $user->id,
                'order_id' => $orderId,
                'points' => -$pointsToRedeem,
                'type' => 'redeem',
                'description' => $orderId ? "استبدال نقاط على طلب #{$orderId}" : 'استبدال نقاط',
                'metadata' => ['redemption_id' => $redemption->id],
            ]);

            if ($order) {
                $newTotal = max(0, (float) $order->total - $discountAmount);
                $order->update([
                    'discount' => (float) $order->discount + $discountAmount,
                    'total' => $newTotal,
                    'points_redeemed' => $pointsToRedeem,
                ]);
            }
        });

        return [
            'points_used' => $pointsToRedeem,
            'discount_amount' => $discountAmount,
            'remaining_points' => $user->fresh()->total_points,
        ];
    }

    public function expirePointsForAllUsers(): int
    {
        $settings = $this->settings();
        if (!$settings->enable_expiry || !$settings->enable_points_system) {
            return 0;
        }

        $expiredCount = 0;
        User::where('type', 'client')->where('total_points', '>', 0)->chunkById(100, function ($users) use ($settings, &$expiredCount) {
            foreach ($users as $user) {
                if ($this->expirePointsForUser($user, $settings)) {
                    $expiredCount++;
                }
            }
        });

        return $expiredCount;
    }

    public function activeCampaigns()
    {
        $settings = $this->settings();
        if (!$settings->enable_double_points) {
            return collect();
        }

        return DoublePointsCampaign::active()->orderByDesc('start_date')->get();
    }

    public function checkAndUpdateUserTier(User $user): bool
    {
        $settings = $this->settings();
        if (!$settings->enable_tiers) {
            return false;
        }

        $oldTier = $user->current_tier ?: 'Regular';
        $newTier = LoyaltyTier::where('is_active', true)
            ->where('min_lifetime_spending', '<=', $user->lifetime_spending)
            ->orderByDesc('min_lifetime_spending')
            ->first();

        if (!$newTier || $newTier->name === $oldTier) {
            return false;
        }

        UserTierHistory::create([
            'user_id' => $user->id,
            'old_tier' => $oldTier,
            'new_tier' => $newTier->name,
            'reason' => 'ترقية تلقائية بناءً على الإنفاق التراكمي',
        ]);
        $user->update(['current_tier' => $newTier->name]);

        return true;
    }

    private function validateRedemption(User $user, int $pointsToRedeem, LoyaltySetting $settings): void
    {
        if (!$settings->enable_points_system) {
            throw new \RuntimeException('نظام النقاط معطل حالياً');
        }
        if ($user->total_points < $pointsToRedeem) {
            throw new \RuntimeException('رصيد نقاطك غير كافٍ');
        }
        $minPoints = $this->minRedemptionForUser($user, $settings);
        if ($settings->enable_min_redemption && $pointsToRedeem < $minPoints) {
            throw new \RuntimeException("الحد الأدنى للاستبدال هو {$minPoints} نقطة");
        }
    }

    private function minRedemptionForUser(User $user, LoyaltySetting $settings): int
    {
        if (!$settings->enable_tiers) {
            return (int) $settings->min_redemption_points;
        }

        $tier = LoyaltyTier::where('name', $user->current_tier)->where('is_active', true)->first();
        if ($tier && $tier->min_redemption_points !== null) {
            return (int) $tier->min_redemption_points;
        }

        return (int) $settings->min_redemption_points;
    }

    private function tierMultiplier(User $user): float
    {
        $tier = LoyaltyTier::where('name', $user->current_tier)->where('is_active', true)->first();

        return $tier ? (float) $tier->points_multiplier : 1.0;
    }

    private function tierBirthdayMultiplier(User $user): float
    {
        $tier = LoyaltyTier::where('name', $user->current_tier)->where('is_active', true)->first();

        return $tier ? (float) $tier->birthday_bonus_multiplier : 1.0;
    }

    private function campaignMultiplier(?string $deliveryType): float
    {
        $settings = $this->settings();
        if (!$settings->enable_double_points) {
            return 1.0;
        }

        $appliesTo = $deliveryType === 'pick_up' ? 'pick_up' : 'delivery';
        $campaign = DoublePointsCampaign::active()
            ->where(function ($query) use ($appliesTo) {
                $query->where('applies_to', 'all')->orWhere('applies_to', $appliesTo);
            })
            ->orderByDesc('multiplier')
            ->first();

        return $campaign ? (float) $campaign->multiplier : 1.0;
    }

    private function expiryInfo(User $user): array
    {
        $settings = $this->settings();
        if (!$settings->enable_expiry) {
            return [
                'days_remaining' => null,
                'expiry_date' => null,
                'is_expiring_soon' => false,
            ];
        }

        $lastEarn = LoyaltyTransaction::where('user_id', $user->id)
            ->whereIn('type', ['earn', 'bonus', 'welcome', 'birthday', 'referral'])
            ->latest()
            ->first();

        if (!$lastEarn) {
            return [
                'days_remaining' => null,
                'expiry_date' => null,
                'is_expiring_soon' => false,
            ];
        }

        $expiryDate = Carbon::parse($lastEarn->created_at)->addMonths((int) $settings->points_expiry_months);
        $daysRemaining = now()->startOfDay()->diffInDays($expiryDate, false);

        return [
            'days_remaining' => $daysRemaining,
            'expiry_date' => $expiryDate->toDateString(),
            'is_expiring_soon' => $daysRemaining >= 0 && $daysRemaining <= 30,
        ];
    }

    private function nextTierProgress(User $user): array
    {
        $current = LoyaltyTier::where('name', $user->current_tier)->first();
        $currentLevel = $current?->level ?? 1;
        $next = LoyaltyTier::where('is_active', true)
            ->where('level', '>', $currentLevel)
            ->orderBy('level')
            ->first();

        if (!$next) {
            return ['name' => null, 'points_needed' => 0, 'progress' => 100];
        }

        $currentSpending = (float) $user->lifetime_spending;
        $target = (float) $next->min_lifetime_spending;
        $base = $current ? (float) $current->min_lifetime_spending : 0;
        $range = max(1, $target - $base);
        $progress = (int) min(100, max(0, round((($currentSpending - $base) / $range) * 100)));

        return [
            'name' => $next->name,
            'points_needed' => (int) max(0, ceil($target - $currentSpending)),
            'progress' => $progress,
        ];
    }

    private function expirePointsForUser(User $user, LoyaltySetting $settings): bool
    {
        $lastEarn = LoyaltyTransaction::where('user_id', $user->id)
            ->whereIn('type', ['earn', 'bonus', 'welcome', 'birthday', 'referral'])
            ->latest()
            ->first();

        if (!$lastEarn) {
            return false;
        }

        $expiryDate = Carbon::parse($lastEarn->created_at)->addMonths((int) $settings->points_expiry_months);
        if (now()->lte($expiryDate) || $user->total_points <= 0) {
            return false;
        }

        $expiredPoints = (int) $user->total_points;
        DB::transaction(function () use ($user, $expiredPoints) {
            $user->update(['total_points' => 0]);
            LoyaltyTransaction::create([
                'user_id' => $user->id,
                'points' => -$expiredPoints,
                'type' => 'expiry',
                'description' => 'انتهاء صلاحية النقاط',
            ]);
        });

        return true;
    }

    public function adminAddPoints(User $user, int $points, string $description, string $type = 'bonus'): void
    {
        $this->awardPoints($user, $points, $type, $description, null, ['added_by_admin' => true]);
    }

    public function adminDeductPoints(User $user, int $points, string $description): void
    {
        if ($user->total_points < $points) {
            throw new \RuntimeException('رصيد النقاط غير كافٍ');
        }

        DB::transaction(function () use ($user, $points, $description) {
            $user->decrement('total_points', $points);
            LoyaltyTransaction::create([
                'user_id' => $user->id,
                'points' => -$points,
                'type' => 'redeem',
                'description' => $description,
                'metadata' => ['deducted_by_admin' => true],
            ]);
        });
    }

    public function summaryStats(): array
    {
        $totalAwarded = (int) LoyaltyTransaction::where('points', '>', 0)->sum('points');
        $totalRedeemed = abs((int) LoyaltyTransaction::where('type', 'redeem')->sum('points'));
        $activeMembers = User::where('type', 'client')->where('total_points', '>', 0)->count();
        $redemptionRate = $totalAwarded > 0 ? round(($totalRedeemed / $totalAwarded) * 100, 1) : 0;

        return [
            'total_points_outstanding' => (int) User::where('type', 'client')->sum('total_points'),
            'total_points_awarded' => $totalAwarded,
            'total_points_redeemed' => $totalRedeemed,
            'active_members' => $activeMembers,
            'redemption_rate' => $redemptionRate,
            'transactions_count' => LoyaltyTransaction::count(),
            'redemptions_count' => Redemption::count(),
            'referrals_count' => Referral::count(),
            'active_campaigns' => $this->activeCampaigns()->count(),
        ];
    }

    public function expiringSoonUsers(int $days = 30): array
    {
        $settings = $this->settings();
        if (!$settings->enable_expiry) {
            return [];
        }

        $users = User::where('type', 'client')->where('total_points', '>', 0)->get();
        $result = [];
        foreach ($users as $user) {
            $info = $this->expiryInfo($user);
            if (($info['days_remaining'] ?? 999) >= 0 && ($info['days_remaining'] ?? 999) <= $days) {
                $result[] = [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'mobile' => $user->mobile,
                    'total_points' => $user->total_points,
                    'days_remaining' => $info['days_remaining'],
                    'expiry_date' => $info['expiry_date'],
                ];
            }
        }

        return $result;
    }
}
