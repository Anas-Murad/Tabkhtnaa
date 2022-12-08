<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data =
            [
                ['key' => 'appetizers', 'icon' => asset('images/categorise/Appetizers-01.png') ],
                ['key' => 'asian_food', 'icon' => asset('images/categorise/Asian food-01.png') ],
                ['key' => 'bakery', 'icon' => asset('images/categorise/Bakery-01.png') ],
                ['key' => 'barbeque', 'icon' => asset('images/categorise/Barbeque-01.png') ],
                ['key' => 'dessert', 'icon' => asset('images/categorise/Dessert-01.png') ],
                ['key' => 'drinks', 'icon' => asset('images/categorise/Drinks-01.png') ],
                ['key' => 'fast_food', 'icon' => asset('images/categorise/Fast food-01.png') ],
                ['key' => 'frozen', 'icon' => asset('images/categorise/Frozen-01.png') ],
                ['key' => 'healthy_food', 'icon' => asset('images/categorise/Healthy food-01.png') ],
                ['key' => 'orders', 'icon' => asset('images/categorise/Orders-01.png') ],
                ['key' => 'oriental_food', 'icon' => asset('images/categorise/Oriental food-01.png') ],
                ['key' => 'pasta', 'icon' => asset('images/categorise/Pasta-01.png') ],
                ['key' => 'pickels', 'icon' => asset('images/categorise/Pickels-01.png') ],
                ['key' => 'salad', 'icon' => asset('images/categorise/Salad-01.png') ],
                ['key' => 'sandwiches', 'icon' => asset('images/categorise/Sandwiches-01.png') ],
                ['key' => 'soup', 'icon' => asset('images/categorise/Soup-01.png') ],
                ['key' => 'spicy', 'icon' => asset('images/categorise/Spicy-01.png') ],
                ['key' => 'western', 'icon' => asset('images/categorise/Western-01.png') ],
            ];
        foreach ($data as $d)
        {
            Category::create(
                $d
            );
        }
    }
}
