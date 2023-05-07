<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\additions\CreateAdditionCategoryRequest;
use App\Http\Requests\api\v1\additions\UpdateAdditionCategoryRequest;
use App\Models\AdditionCategory;
use App\Models\UserAddress;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;

class AdditionCategoryController extends Controller
{

    use  HelperTrait ;
    public function list()
    {
        return $this->returnDataArray(AdditionCategory::with('additions')->whereUserId(auth()->id() )->get()) ;
    }

    public function store(CreateAdditionCategoryRequest $request)
    {
        return $this->returnDataArray(AdditionCategory::create($request->validated()));
    }

    public function get(Request $request)
    {
        $additionCategory = AdditionCategory::with('additions')->whereUserId(auth()->id())->findOrFail($request->id);
        return $this->returnDataArray( $additionCategory ) ;
    }



    public function update(UpdateAdditionCategoryRequest $request   )
    {
        $additionCategory = AdditionCategory::whereUserId($request->user_id )-> findOrFail($request->id);
        $additionCategory->update($request->validated());
        return $this->returnDataArray( $additionCategory );
    }

    public function delete( Request $request)
    {
        $count = AdditionCategory::whereUserId(auth()->id())->whereId($request->id)->delete();
        if ($count < 1 ){
            return $this->returnError('It was not deleted, it may already be deleted or you do not have enough permission');
        }
        return $this->returnSuccess(__('messages.deleted_successfully'));
    }
}
