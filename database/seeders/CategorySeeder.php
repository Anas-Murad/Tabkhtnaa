<?php

namespace Database\Seeders;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                ['name' => ['en' => 'category1', 'ar' => 'فواكة'], 'icon' => 'https://pos.tecknick.net/icons8-facebook-240.png','created_at' => Carbon::now() , 'updated_at' => Carbon::now()],
                ['name' => ['en' => 'category2', 'ar' => 'حلويات'], 'icon' => 'https://pos.tecknick.net/icons8-facebook-240.png','created_at' => Carbon::now() , 'updated_at' => Carbon::now()],
                ['name' => ['en' => 'category3', 'ar' => 'وجبات أرز'], 'icon' => 'https://pos.tecknick.net/icons8-facebook-240.png','created_at' => Carbon::now() , 'updated_at' => Carbon::now()],
                ['name' => ['en' => 'category4', 'ar' => 'معكرونة'], 'icon' => 'https://pos.tecknick.net/icons8-facebook-240.png','created_at' => Carbon::now() , 'updated_at' => Carbon::now()],
                ['name' => ['en' => 'category5', 'ar' => 'رغيف خبز'], 'icon' => 'https://pos.tecknick.net/icons8-facebook-240.png','created_at' => Carbon::now() , 'updated_at' => Carbon::now()],
                ['name' => ['en' => 'category6', 'ar' => 'مقبلات شهية'], 'icon' => 'https://pos.tecknick.net/icons8-facebook-240.png','created_at' => Carbon::now() , 'updated_at' => Carbon::now()],
                ['name' => ['en' => 'category7', 'ar' => 'مقبلات شهية'], 'icon' => 'https://pos.tecknick.net/icons8-facebook-240.png','created_at' => Carbon::now() , 'updated_at' => Carbon::now()],
                ['name' => ['en' => 'category8', 'ar' => 'وجبات دجاج شهية'], 'icon' => 'https://pos.tecknick.net/icons8-facebook-240.png','created_at' => Carbon::now() , 'updated_at' => Carbon::now()],
                ['name' => ['en' => 'category9', 'ar' => 'ودبات دجاج'], 'icon' => 'https://pos.tecknick.net/icons8-facebook-240.png','created_at' => Carbon::now() , 'updated_at' => Carbon::now()],
                ['name' => ['en' => 'category10', 'ar' => 'فواكه شهية'], 'icon' => 'https://pos.tecknick.net/icons8-facebook-240.png','created_at' => Carbon::now() , 'updated_at' => Carbon::now()],
                ['name' => ['en' => 'category11', 'ar' => 'حساء الحمة'], 'icon' => 'https://pos.tecknick.net/icons8-facebook-240.png','created_at' => Carbon::now() , 'updated_at' => Carbon::now()],
                ['name' => ['en' => 'category12', 'ar' => 'ستيك لحمة'], 'icon' => 'https://pos.tecknick.net/icons8-facebook-240.png','created_at' => Carbon::now() , 'updated_at' => Carbon::now()],
                ['name' => ['en' => 'category13', 'ar' => 'شوربات حارة'], 'icon' => 'https://pos.tecknick.net/icons8-facebook-240.png','created_at' => Carbon::now() , 'updated_at' => Carbon::now()],
                ['name' => ['en' => 'category14', 'ar' => 'مشاوي'], 'icon' => 'https://pos.tecknick.net/icons8-facebook-240.png','created_at' => Carbon::now() , 'updated_at' => Carbon::now()],
                ['name' => ['en' => 'category15', 'ar' => 'مشاوي عل فحم'], 'icon' => 'https://pos.tecknick.net/icons8-facebook-240.png','created_at' => Carbon::now() , 'updated_at' => Carbon::now()],
                ['name' => ['en' => 'category16', 'ar' => 'معكرونة'], 'icon' => 'https://pos.tecknick.net/icons8-facebook-240.png','created_at' => Carbon::now() , 'updated_at' => Carbon::now()],
                ['name' => ['en' => 'category17', 'ar' => 'رغيف خبرز'], 'icon' => 'https://pos.tecknick.net/icons8-facebook-240.png','created_at' => Carbon::now() , 'updated_at' => Carbon::now()],
                ['name' => ['en' => 'category18', 'ar' => 'معكرونة'], 'icon' => 'https://pos.tecknick.net/icons8-facebook-240.png','created_at' => Carbon::now() , 'updated_at' => Carbon::now()],
                ['name' => ['en' => 'category19', 'ar' => 'زغيف خبز'], 'icon' => 'https://pos.tecknick.net/icons8-facebook-240.png','created_at' => Carbon::now() , 'updated_at' => Carbon::now()],
                ['name' => ['en' => 'category20', 'ar' => 'مقبلات شهية'], 'icon' => 'https://pos.tecknick.net/icons8-facebook-240.png','created_at' => Carbon::now() , 'updated_at' => Carbon::now()],
            ];
        foreach ($data as $d)
        {
            Category::create(
                $d
            );
        }
    }
}
