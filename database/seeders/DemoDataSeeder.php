<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartMeal;
use App\Models\Category;
use App\Models\Complaint;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\Meal;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderHistoryStatus;
use App\Models\OrderMeal;
use App\Models\Rating;
use App\Models\Sanction;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserNotification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Demo data for full app testing.
 *
 * Test client login:
 *   country_code: 962
 *   mobile: 799999999
 *   password: Demo1234
 *
 * Run: php artisan db:seed --class=DemoDataSeeder
 */
class DemoDataSeeder extends Seeder
{
    public const DEMO_CLIENT_MOBILE = '799999999';
    public const DEMO_CLIENT_EMAIL = 'demo.client@tabkhtnaa.test';
    public const DEMO_PASSWORD = 'Demo1234';
    public const DEMO_COUNTRY_CODE = '962';

    private const AMman_LAT = 31.9539;
    private const AMman_LNG = 35.9106;

    public function run(): void
    {
        $countryId = 111;
        $cityId = 965;

        $client = User::updateOrCreate(
            ['mobile' => self::DEMO_CLIENT_MOBILE, 'country_code' => self::DEMO_COUNTRY_CODE],
            [
                'name' => 'Demo Client',
                'email' => self::DEMO_CLIENT_EMAIL,
                'residence_country_id' => $countryId,
                'dob' => '1995-01-15',
                'gender' => 'male',
                'type' => 'client',
                'account_status' => 'active',
                'mobile_verified' => true,
                'password' => Hash::make(self::DEMO_PASSWORD),
                'profile_image' => 'https://picsum.photos/seed/demo-client/200/200',
            ]
        );

        $chef = User::updateOrCreate(
            ['mobile' => '788888888', 'country_code' => self::DEMO_COUNTRY_CODE],
            [
                'name' => 'Chef Um Khalid',
                'email' => 'demo.chef@tabkhtnaa.test',
                'residence_country_id' => $countryId,
                'dob' => '1988-03-20',
                'gender' => 'female',
                'type' => 'chef',
                'account_status' => 'active',
                'mobile_verified' => true,
                'password' => Hash::make(self::DEMO_PASSWORD),
                'profile_image' => 'https://picsum.photos/seed/demo-chef/200/200',
            ]
        );

        UserAddress::updateOrCreate(
            ['user_id' => $client->id, 'name' => 'المنزل'],
            [
                'place' => 'Amman, Abdali',
                'country_id' => $countryId,
                'city_id' => $cityId,
                'neighborhood' => 'Abdali',
                'build_address' => '12',
                'floor' => '3',
                'apartment_address' => '301',
                'details' => 'Demo home address',
                'latitude' => (string) self::AMman_LAT,
                'longitude' => (string) self::AMman_LNG,
            ]
        );

        UserAddress::updateOrCreate(
            ['user_id' => $chef->id, 'name' => 'المطبخ'],
            [
                'place' => 'Amman, Jabal Amman',
                'country_id' => $countryId,
                'city_id' => $cityId,
                'neighborhood' => 'Jabal Amman',
                'build_address' => '5',
                'floor' => '1',
                'apartment_address' => 'Kitchen',
                'details' => 'Demo chef kitchen',
                'latitude' => '31.9550',
                'longitude' => '35.9150',
            ]
        );

        $category = Category::first();
        if (!$category) {
            $this->command?->warn('No categories found — run CategorySeeder first.');
            return;
        }

        $meals = [
            [
                'name' => 'Mansaf',
                'code' => 'DEMO-MANSaf',
                'description' => 'Traditional Jordanian mansaf with rice and lamb.',
                'price' => 12.50,
                'image' => 'https://picsum.photos/seed/mansaf/400/300',
                'type' => 'ready',
            ],
            [
                'name' => 'Maqluba',
                'code' => 'DEMO-MAQLUBA',
                'description' => 'Upside-down rice with chicken and vegetables.',
                'price' => 10.00,
                'image' => 'https://picsum.photos/seed/maqluba/400/300',
                'type' => 'pre-order',
            ],
            [
                'name' => 'Knafeh',
                'code' => 'DEMO-KNAFEH',
                'description' => 'Sweet cheese pastry dessert.',
                'price' => 6.00,
                'image' => 'https://picsum.photos/seed/knafeh/400/300',
                'type' => 'ready',
            ],
        ];

        $mealIds = [];
        foreach ($meals as $mealData) {
            $meal = Meal::updateOrCreate(
                ['user_id' => $chef->id, 'code' => $mealData['code']],
                array_merge($mealData, [
                    'category_id' => $category->id,
                    'is_active' => true,
                    'admin_status' => 'confirmed',
                ])
            );
            $mealIds[] = $meal->id;
        }

        $cart = Cart::firstOrCreate(
            ['user_id' => $client->id, 'maker_id' => $chef->id],
            []
        );

        CartMeal::updateOrCreate(
            ['cart_id' => $cart->id, 'meal_id' => $mealIds[0]],
            ['user_id' => $client->id, 'quantity' => 2, 'note' => 'Demo cart item']
        );

        $orders = [];
        foreach (['pending', 'confirmed', 'on_way', 'delivered', 'cancel'] as $status) {
            $subTotal = 25.00;
            $tax = 4.00;
            $delivery = 2.50;
            $order = Order::updateOrCreate(
                [
                    'user_id' => $client->id,
                    'chef_id' => $chef->id,
                    'status' => $status,
                ],
                [
                    'payment_method' => 'cash',
                    'delivery_type' => 'delivery',
                    'delivery_fees' => $delivery,
                    'tax' => $tax,
                    'sub_total' => $subTotal,
                    'discount' => 0,
                    'total' => $subTotal + $tax + $delivery,
                    'transaction_status' => $status === 'cancel' ? 'cancel' : 'success',
                    'expected_order_time' => 45,
                ]
            );
            $orders[$status] = $order;

            OrderMeal::updateOrCreate(
                ['order_id' => $order->id, 'meal_id' => $mealIds[0]],
                [
                    'user_id' => $client->id,
                    'meal_name' => 'Mansaf',
                    'quantity' => 2,
                    'price' => 12.50,
                    'total' => 25.00,
                ]
            );

            OrderAddress::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'user_id' => $client->id,
                    'address_id' => UserAddress::where('user_id', $client->id)->value('id'),
                    'country_id' => $countryId,
                    'city_id' => $cityId,
                    'name' => 'المنزل',
                    'place' => 'Amman, Abdali',
                    'neighborhood' => 'Abdali',
                    'build_address' => '12',
                    'floor' => '3',
                    'apartment_address' => '301',
                    'details' => 'Demo order address',
                    'latitude' => (string) self::AMman_LAT,
                    'longitude' => (string) self::AMman_LNG,
                ]
            );

            OrderHistoryStatus::firstOrCreate(
                ['order_id' => $order->id, 'status' => $status],
                ['action_by_type' => 'client', 'action_by_id' => $client->id]
            );
        }

        $deliveredOrder = $orders['delivered'];

        Rating::updateOrCreate(
            ['order_id' => $deliveredOrder->id, 'user_id' => $client->id],
            [
                'chef_id' => $chef->id,
                'rating_chef' => '5',
                'rating_delivery' => '4',
                'rating_speed_chef' => '5',
                'rating_speed_delivery' => '4',
                'note' => 'Excellent demo meal!',
            ]
        );

        $conversation = Conversation::firstOrCreate(
            [
                'user1_id' => $client->id,
                'user1_type' => 'client',
                'user2_id' => $chef->id,
                'user2_type' => 'chef',
                'order_id' => $deliveredOrder->id,
            ],
            []
        );

        $msg1 = ConversationMessage::firstOrCreate(
            ['conversation_id' => $conversation->id, 'user_id' => $client->id, 'message' => 'مرحباً، متى يصل الطلب؟'],
            []
        );
        ConversationMessage::firstOrCreate(
            ['conversation_id' => $conversation->id, 'user_id' => $chef->id, 'message' => 'أهلاً! الطلب في الطريق خلال 20 دقيقة.'],
            []
        );
        $conversation->update(['last_message_id' => $msg1->id]);

        $notifOrder = Notification::updateOrCreate(
            ['title' => 'Demo Order Update'],
            [
                'body' => 'Your order #' . $deliveredOrder->id . ' has been delivered.',
                'order_id' => (string) $deliveredOrder->id,
                'data' => json_encode(['type' => 'order', 'status' => 'delivered']),
            ]
        );

        UserNotification::updateOrCreate(
            ['user_id' => $client->id, 'notification_id' => $notifOrder->id],
            ['seen' => false]
        );

        $notifAdmin = Notification::updateOrCreate(
            ['title' => 'Demo Admin Notice'],
            [
                'body' => 'Welcome to Tabkhtnaa demo environment.',
                'order_id' => null,
                'data' => json_encode(['type' => 'admin']),
            ]
        );

        UserNotification::updateOrCreate(
            ['user_id' => $client->id, 'notification_id' => $notifAdmin->id],
            ['seen' => false]
        );

        Complaint::updateOrCreate(
            ['user_id' => $client->id, 'order_id' => $orders['pending']->id],
            [
                'type' => 'maker',
                'description' => 'Demo complaint about order delay',
                'note' => 'Late delivery sample',
                'status' => 'pending',
            ]
        );

        Sanction::updateOrCreate(
            ['user_id' => $client->id, 'type' => 'financial_violation'],
            [
                'admin_id' => $chef->id,
                'seen' => 'not_seen',
                'note' => 'Demo sanction — sample warning',
                'start_time' => now()->subDay(),
                'end_time' => now()->addDays(7),
            ]
        );

        $this->command?->info('DemoDataSeeder complete.');
        $this->command?->info('Login: country_code=' . self::DEMO_COUNTRY_CODE . ' mobile=' . self::DEMO_CLIENT_MOBILE . ' password=' . self::DEMO_PASSWORD);
    }
}
