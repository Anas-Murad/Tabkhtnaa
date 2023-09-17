<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'residence_country_id' => 1,
            'country_code' => '962',
            'mobile' => '0777777777',
            'dob' => '1995-09-25',
            'gender' => 'male',
            'password' => Hash::make('password'),
        ]);
    }
}
