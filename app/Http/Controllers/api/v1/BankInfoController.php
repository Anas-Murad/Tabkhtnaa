<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\bank\bankInfoRequest;
use App\Http\Requests\api\v1\bank\deletebankInfoRequest;
use App\Http\Requests\api\v1\bank\updatebankInfoRequest;
use App\Models\BankInfo;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankInfoController extends Controller
{
    use HelperTrait;

    public function get()
    {
        $bank = BankInfo::whereUserId(Auth::id())->first();
        return $this->returnSuccess($bank);
    }

    public function create(BankInfoRequest $request)
    {
        $data = $request->safe()->all();
        $bank = BankInfo::create($data);
        return $this->returnSuccess($bank);
    }

    public function update(UpdatebankInfoRequest $request)
    {
        $data = $request->safe()->all();
        $bank = BankInfo::findOrFail($data['bankInfo_id']);
        $bank->update($data);
        return $this->returnSuccess($bank);
    }

    public function delete(deletebankInfoRequest $request)
    {
        BankInfo::where('id' , $request->bankInfo_id)->where('user_id' , $request->user_id)->delete();
        return $this->returnSuccess('bank info was deleted successfully');
    }

}
