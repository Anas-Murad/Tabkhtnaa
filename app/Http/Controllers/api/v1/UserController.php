<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\chef\ChefIDRequest;
use App\Http\Requests\api\v1\chef\ChefRequest;
use App\Models\Meal;
use App\Models\User;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use HelperTrait;

    public function all_chefs(ChefRequest $request)
    {
        $latitude = $request->lat;
        $longitude = $request->long;
        $distance = $request->radius ?? 30;

        $chefs = User::select('users.*' ,
            'user_addresses.latitude',
            'user_addresses.longitude'
        )->where('type' , 'chef')
            ->where(function ($q) use ($request){
                if ($request->filled('search'))
                    $q->where('users.name' , 'LIKE', '%' . $request->search . '%');
            })
            ->nearby($latitude, $longitude, $distance)
            ->where('account_status' , 'active')
            ->join('user_addresses', 'users.id', '=', 'user_addresses.user_id')
            ->simplePaginate(10);

        return $this->returnPaginateData($chefs);
    }

    public function get_chef(ChefIDRequest $request)
    {
        $chef = User::with([
            'meals' =>function($q){
                $q->active() ;
            }, 'userAddress'
        ])->find( $request->id);
        $chef->loadRates();
        return $this->returnDataArray($chef);
    }
}
