<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;
use App\Models\User;
use App\Services\LoyaltyService;

$loyalty = app(LoyaltyService::class);

$user = User::where('type', 'client')->find(1);
if ($user) {
    $loyalty->ensureReferralCode($user);
    $loyalty->applyWelcomeBonus($user->fresh());
    echo "Welcome bonus for user #{$user->id}: " . ($user->fresh()->welcome_bonus_awarded ? 'ok' : 'skipped') . PHP_EOL;
}

$order = Order::where('status', 'delivered')->where('user_id', 1)->orderByDesc('id')->first();
if ($order && !$order->loyalty_points_awarded) {
    $loyalty->awardPointsForOrder($order->fresh());
    echo "Points awarded for order #{$order->id}" . PHP_EOL;
}

echo 'Transactions for user 1: ' . \App\Models\LoyaltyTransaction::where('user_id', 1)->count() . PHP_EOL;
