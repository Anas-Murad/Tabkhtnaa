<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\addresses\CreateAddressRequest;
use App\Http\Requests\api\v1\addresses\UpdateAddressRequest;
use App\Models\UserAddress;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;

class AddressesController extends Controller
{
use  HelperTrait  ;


    public function delete(Request $request)
    {
         UserAddress::whereUserId(auth()->id())->whereId($request->id)->delete() ;
        return  $this->returnSuccess( __('messages.deleted_successfully') )  ;

    }



    public function update(UpdateAddressRequest $request)
    {
        $d = UserAddress::whereUserId(auth()->id())->find($request->id) ;
        if(!$d)
            return  $this->returnError( __('messages.not_found_data') )  ;
        $d->update($request->validated());
        return  $this->returnSuccess( $d)  ;
    }


    public function get(Request $request)
    {

        $d= UserAddress::whereUserId(auth()->id())->AllData()->find($request->id) ;
        if(!$d)
            return  $this->returnError( __('messages.not_found_data') )  ;

        return  $this->returnDataArray( $d)  ;

    }
    public function list(Request $request)
    {
        $d= UserAddress::whereUserId(auth()->id())->AllData()->latest()->get() ;
        return  $this->returnDataArray( $d)  ;

    }

    public function store(CreateAddressRequest $request)
    {


       $d= UserAddress::create($request->validated()) ;
       return  $this->returnSuccess( $d)  ;

    }
}
