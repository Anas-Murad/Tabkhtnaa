<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder
{
    public function run(): void
    {
        //php artisan db:seed --class=ConfigurationSeeder
        $insertedArray = [];
        $countries = Country::all();
        $ConfigurationData = collect(ConfigurationData());
        foreach ($ConfigurationData as $classification=>$ConfigurationDataData) {
            foreach ($ConfigurationDataData as  $ConfigurationDataItem ) {
                $ConfigurationDataItem = collect($ConfigurationDataItem) ;
                foreach ($countries as $country){
                    $ConfigurationDataItem->put('country_id' , $country->id) ;
                    $ConfigurationDataItem->put('classification' ,$classification) ;
                    $insertedArray[] = $ConfigurationDataItem->only(
                    "config_key",
                    "config_value",
                    "config_bool",
                    "classification",
                    "country_id",
                )->toArray();
                }
            }
        }
        \DB::table('configurations')->truncate();
        \DB::table('configurations')->insert($insertedArray);
    }
}
