<?php

namespace Database\Seeders;

use App\Models\Accessories;
use Illuminate\Database\Seeder;

class AccessoriesSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['key' => 'cups', 'default_name' => 'Cups'],
            ['key' => 'plates', 'default_name' => 'Plates'],
            ['key' => 'spoons', 'default_name' => 'Spoons'],
            ['key' => 'napkins', 'default_name' => 'Napkins'],
        ];

        foreach ($items as $item) {
            Accessories::firstOrCreate(['key' => $item['key']], $item);
        }
    }
}
