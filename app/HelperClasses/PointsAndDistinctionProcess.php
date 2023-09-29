<?php

namespace App\HelperClasses;

use App\Models\Country;
use App\Models\Order;
use App\Models\Transfer;
use App\Models\TransferRecord;
use App\Models\UserPointTransfers;

class PointsAndDistinctionProcess
{
    public static function Process(Order $order)
    {
        $order->loadMissing([
            'address',
            'chef',
            'user',
            'delivery',
        ]);
        $countryId = $order->address->country_id;
        $country = Country::with([
            'configuration' => function ($q) {
                $q->whereIn('classification', ['points', 'distinction']);
            }
        ])->whereHas('users')
        ->select('id', 'name')
        ->find($countryId);

        $config_key = $country->configuration->keyBy('config_key');
        $client_points_limit = $config_key['client_points_limit']->config_value;
        $distinction_delivery_orders = $config_key['distinction_delivery_orders']->config_value;
        $distinction_delivery_revenues = $config_key['distinction_delivery_revenues']->config_value;
        $distinction_delivery_sanctions = $config_key['distinction_delivery_sanctions']->config_value;
        $distinction_chef_orders = $config_key['distinction_chef_orders']->config_value;
        $distinction_chef_revenues = $config_key['distinction_chef_revenues']->config_value;
        $distinction_chef_sanctions = $config_key['distinction_chef_sanctions']->config_value;


        $user = $order->user;
        $chef = $order->chef;
        $delivery = $order->delivery;
        // Handel User Points
        if ($user) {

            $points = (int)$order->total;
            $user->userPoints()->create([
                'order_id' => $order->id,
                'points' => $points,
                'status' => 'new',
            ]);

            $TotalUserPoint = $user->userPoints()->where([
                'status' => 'new'
            ])->sum('points');


            if ($client_points_limit <= $TotalUserPoint) {
                $U_count = $user->userPointTransfers()->where([
                    'status' => 'new'
                ])->count();
                if ($U_count == 0) {
                    $userPointTransfers = UserPointTransfers::create([
                        'user_id' => $user->id,
                        'points' => $TotalUserPoint,
                        'status' => 'new',
                    ]);
                    $user->userPoints()->where([
                        'status' => 'new'
                    ])->update([
                        'user_point_transfers_id' => $userPointTransfers->id,
                    ]);
                }
            }
        }
        // Handel Chef Distinction
        if ($chef) {

            $last_distinction_at = $chef->last_distinction_at;
            $chef->loadCount([
                'chefOrders' => function ($q) use ($last_distinction_at) {
                    $q->when($last_distinction_at, function ($q) use ($last_distinction_at) {
                        $q->where('created_at', '>', $last_distinction_at);
                    });
                },
                'sanctions' => function ($q) use ($last_distinction_at) {
                    $q->when($last_distinction_at, function ($q) use ($last_distinction_at) {
                        $q->where('created_at', '>', $last_distinction_at);
                    });
                },
                'userDistinctions' => function ($q) {
                    $q->where('to_date', '>=', now()->toDateString())
                        ->whereIn( 'status',['active' ,'new']);
                },
            ]);


            $chef_revenues = TransferRecord::whereToId($chef->id)->whereToType('chef')
                ->when($last_distinction_at, function ($q) use ($last_distinction_at) {
                    $q->where('created_at', '>', $last_distinction_at);
                })->sum('amount');

            if (
                $chef->chef_orders_count >= $distinction_chef_orders &&
                $chef_revenues >= $distinction_chef_revenues &&
                $chef->sanctions_count <= $distinction_chef_sanctions &&
                $chef->user_distinctions_count == 0
            ) {

                $chef->userDistinctions()->create([
                    'from_date' => now()->toDateString(),
                    'to_date' => now()->addMonths(6)->toDateString(),
                    'status' => 'new',
                ]);
                $chef->update([
                    'last_distinction_at' => now()->toDateString()
                ]);
            }
        }
        // Handel Delivery Distinction
        if ($delivery) {
            $last_distinction_at = $delivery->last_distinction_at;

            $delivery->loadCount([
                'deliveryOrders' => function ($q) use ($last_distinction_at) {
                    $q->when($last_distinction_at, function ($q) use ($last_distinction_at) {
                        $q->where('created_at', '>', $last_distinction_at);
                    });
                },
                'sanctions' => function ($q) use ($last_distinction_at) {
                    $q->when($last_distinction_at, function ($q) use ($last_distinction_at) {
                        $q->where('created_at', '>', $last_distinction_at);
                    });
                },
                'userDistinctions' => function ($q) {
                    $q->where('to_date', '>=', now()->toDateString())
                        ->whereIn( 'status',['active' ,'new']);
                },

            ]);

            $delivery_revenues = TransferRecord::whereToId($delivery->id)->whereToType('delivery')
                ->when($last_distinction_at, function ($q) use ($last_distinction_at) {
                    $q->where('created_at', '>', $last_distinction_at);
                })->sum('amount');


            if (
                $delivery->delivery_orders_count >= $distinction_delivery_orders &&
                $delivery_revenues >= $distinction_delivery_revenues &&
                $delivery->sanctions_count <= $distinction_delivery_sanctions &&
                $delivery->user_distinctions_count == 0
            ) {
                $delivery->userDistinctions()->create([
                    'from_date' => now()->toDateString(),
                    'to_date' => now()->addMonths(6)->toDateString(),
                    'status' => 'new',
                ]);
                $delivery->update([
                    'last_distinction_at' => now()->toDateString()
                ]);
            }
        }
    }
}
