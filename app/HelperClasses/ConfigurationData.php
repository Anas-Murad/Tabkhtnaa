<?php


function ConfigurationData(){
    return  [
        'points'=>[
            [
                'config_key' => 'client_points_limit',
                'config_value' => 5,
                'config_bool' => null,
                'title' => "حدود نقاط المستخدم لاستبدالها",
            ],
        ],
        'distinction'=>[
            [
                'config_key' => 'distinction_delivery_orders',
                'config_value' => 5,
                'config_bool' => null,
                'title' => "حد طلبات الموصل لبلوغ التمييز",

            ],
            [
                'config_key' => 'distinction_delivery_revenues',
                'config_value' => 5,
                'config_bool' => null,
                'title' => "حد ايرادات الموصل لبلوغ التمييز",

            ],
            [
                'config_key' => 'distinction_delivery_sanctions',
                'config_value' => 5,
                'config_bool' => null,
                'title' => "حد عقوبات الموصل لبلوغ التمييز",
            ],
            [
                'config_key' => 'distinction_chef_orders',
                'config_value' => 5,
                'config_bool' => null,
                'title' => "حد طلبات الطاهي لبلوغ التمييز",

            ],
            [
                'config_key' => 'distinction_chef_revenues',
                'config_value' => 5,
                'config_bool' => null,
                'title' => "حد ايرادات الطاهي لبلوغ التمييز",

            ],
            [
                'config_key' => 'distinction_chef_sanctions',
                'config_value' => 5,
                'config_bool' => null,
                'title' => "حد عقوبات الطاهي لبلوغ التمييز",
            ],
        ],

        'sys_percentage'=>[
            [
                'config_key' => 'percentage_from_delivery',
                'config_value' => 5,
                'config_bool' => null,
                'title' => "النسبه من الموصل بالمئوية",

            ],

            [
                'config_key' => 'percentage_from_chef',
                'config_value' => 5,
                'config_bool' => null,
                'title' => "النسبه من الطاهي بالمئوية",
            ],

        ],
    ];
}


//
