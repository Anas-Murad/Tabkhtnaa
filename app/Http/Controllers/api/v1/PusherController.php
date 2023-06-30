<?php

namespace App\Http\Controllers\api\v1;

use App\Events\LiveLocationEvent;
use App\Http\Controllers\Controller;

class PusherController extends Controller
{
    public function test()
    {
        $latitude = 123.456; // Example latitude value
        $longitude = 789.012; // Example longitude value
        $userId =1;

        return   event(new LiveLocationEvent($latitude, $longitude ,$userId));
        return 1 ;
    }
}
