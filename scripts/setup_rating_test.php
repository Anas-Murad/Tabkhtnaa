<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;
use App\Models\Rating;
use App\Models\User;

$mobile = $argv[1] ?? '781780558';
$action = $argv[2] ?? 'inspect';

$user = User::query()
    ->where('mobile', ltrim($mobile, '0'))
    ->orWhere('mobile', $mobile)
    ->orWhere('mobile', '0' . ltrim($mobile, '0'))
    ->first();

if (!$user) {
    echo "USER_NOT_FOUND for mobile: {$mobile}\n";
    exit(1);
}

echo "USER id={$user->id} name={$user->name} mobile={$user->mobile} type={$user->type}\n";

$orders = Order::where('user_id', $user->id)->orderByDesc('id')->limit(10)->get();
echo "ORDERS (" . $orders->count() . "):\n";
foreach ($orders as $o) {
    $hasRating = Rating::where('order_id', $o->id)->exists();
    echo "  #{$o->id} status={$o->status} chef={$o->chef_id} delivery={$o->delivery_type} rated=" . ($hasRating ? 'yes' : 'no') . "\n";
}

if ($action === 'inspect') {
    exit(0);
}

if ($action === 'setup') {
    $order = Order::where('user_id', $user->id)
        ->where('status', '!=', 'delivered')
        ->orderByDesc('id')
        ->first();

    if (!$order) {
        $order = Order::where('user_id', $user->id)->orderByDesc('id')->first();
    }

    if (!$order) {
        echo "NO_ORDER_FOUND\n";
        exit(1);
    }

    $order->update([
        'status' => 'delivered',
        'transaction_status' => 'success',
    ]);

    echo "ORDER_CLOSED id={$order->id} status=delivered\n";

    $existing = Rating::where('order_id', $order->id)->first();
    if ($existing) {
        echo "RATING_EXISTS id={$existing->id}\n";
        exit(0);
    }

    $rating = Rating::create([
        'user_id' => $user->id,
        'chef_id' => $order->chef_id,
        'order_id' => $order->id,
        'rating_chef' => '5',
        'rating_delivery' => '4',
        'rating_speed_chef' => '5',
        'rating_speed_delivery' => '4',
        'note' => 'تجربة ممتازة - طلب تجريبي للتقييم',
    ]);

    echo "RATING_CREATED id={$rating->id} order={$order->id} chef={$order->chef_id}\n";
    exit(0);
}

if ($action === 'deliver-only') {
    $orderId = isset($argv[3]) ? (int) $argv[3] : null;
    $order = $orderId
        ? Order::where('user_id', $user->id)->where('id', $orderId)->first()
        : Order::where('user_id', $user->id)->where('status', '!=', 'delivered')->orderByDesc('id')->first();

    if (!$order) {
        echo "NO_ORDER_FOUND\n";
        exit(1);
    }

    $order->update(['status' => 'delivered', 'transaction_status' => 'success']);
    echo "ORDER_DELIVERED id={$order->id}\n";
    exit(0);
}

echo "Unknown action: {$action}\n";
exit(1);
