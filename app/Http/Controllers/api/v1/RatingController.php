<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\rating\SeenRatingRequest;
use App\Http\Requests\api\v1\rating\StoreRatingRequest;
use App\Models\Rating;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    use HelperTrait;

    public function list(Request $request)
    {
        $ratings =  Rating::where('chef_id' , $request->chef_id)->with('user:id,name' )->simplePaginate(10);
        return $this->returnPaginateData($ratings);
    }

    public function store(StoreRatingRequest $request)
    {
        $data = $request->safe()->except('photo');
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->saveImage($request->photo, 'uploads/ratings');
        }
        $rating = Rating::create($data);
        return $this->returnDataArray($rating);
    }

    public function get_rating(SeenRatingRequest $request)
    {
        $rating = Rating::where('user_id' , Auth::id())->with('user' , 'chef')->find($request->id);
        if (isset($rating))
            return $this->returnDataArray($rating);
        else
            return $this->returnError('not fond Rating');
    }
}
