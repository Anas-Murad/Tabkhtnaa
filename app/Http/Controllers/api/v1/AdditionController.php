<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\additions\CreateAdditionRequest;
use App\Http\Requests\api\v1\additions\UpdateAdditionRequest;
use App\Models\Addition;
use App\Models\AdditionCategory;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;

class AdditionController extends Controller
{
    use  HelperTrait ;
    public function index()
    {
        return Addition::all();
    }

    public function store(CreateAdditionRequest $request)
    {
       return $this->returnDataArray( Addition::create($request->validated()));
    }
    public function update(UpdateAdditionRequest $request)
    {


        $addition = Addition::whereUserId($request->user_id )-> findOrFail($request->id);
        $addition->update($request->validated());
        return $this->returnDataArray( $addition );
    }
    public function delete( Request $request)
    {
        $count = Addition::whereUserId(auth()->id())->whereId($request->id)->delete();
        if ($count < 1 ){
            return $this->returnError('It was not deleted, it may already be deleted or you do not have enough permission');
        }
        return $this->returnSuccess(__('messages.deleted_successfully'));
    }


}
