<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\offers\CreateOfferRequest;
use App\Http\Requests\api\v1\offers\UpdateOfferRequest;
use App\Models\Offer;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class OfferController extends Controller
{
    use HelperTrait;

    public function list(Request $request)
    {
        $query = Offer::where('type',$request->type)->whereHas('meal' , function ($q){
            $q->where('user_id' , Auth::id());
        })->with('meal');
        if ($request->has('start_date') && $request->has('end_date')){
            $query->where('start_date' ,'>=',$request->start_date)
                ->where('end_date' ,'<=',$request->end_date);
        }
        $offers = $query->simplePaginate(10);
        return $this->returnPaginateData($offers);
    }

    public function listUser(Request $request)
    {
        $query = Offer::where('type',$request->type)->whereHas('meal')->with('meal');
        if ($request->has('start_date') && $request->has('end_date')){
            $query->where('start_date' ,'>=',$request->start_date)
                ->where('end_date' ,'<=',$request->end_date);
        }
        $offers = $query->simplePaginate(10);
        return $this->returnPaginateData($offers);
    }

    public function store(CreateOfferRequest $request)
    {
        try {
            $data =  $request->all();
            $offer = Offer::where('meal_id',$data['meal_id'])
                ->where('start_date' ,'>=',$data['start_date'])
                ->where('end_date' ,'<=',$data['end_date'])->first();
            if ($offer)
                return $this->returnError('There is a meal offer');
            Offer::create($data);
            return $this->returnSuccess('Success Create Offer');
        }catch (Exception $e){
            return $this->returnError('Error 500');
        }
    }

    public function update(UpdateOfferRequest $request)
    {
        try {
            $data =  $request->all();
            $offer =  Offer::find($data['offer_id']);
            if (empty($offer))
                 return $this->returnError('this offer not found');
            $offer->update($data);
            return $this->returnDataArray($offer,'Success Update Offer');
        }catch (Exception $e){
            return $this->returnError('Error 500');
        }
    }

    public function delete(Request $request)
    {
       $offer =  Offer::find($request->id);
       if (empty($offer))
           return $this->returnError('this offer not found');
       $offer->delete();
       return $this->returnSuccess('Success Delete Offer');
    }
}
