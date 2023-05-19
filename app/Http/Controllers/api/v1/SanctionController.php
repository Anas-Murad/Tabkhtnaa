<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\sanction\SeenSanctionRequest;
use App\Models\Sanction;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SanctionController extends Controller
{
    use HelperTrait;

    public function list()
    {
        $user_id = Auth::id();
        $sanctions = Sanction::where('user_id' , $user_id)->with('user','admin')->simplePaginate(10);
        return $this->returnPaginateData($sanctions);
    }

    public function seen_sanction(SeenSanctionRequest $request)
    {
        $sanction = Sanction::where('user_id' , Auth::id())->with('user','admin')->find($request->id);
        if (isset($sanction))
        {
            $sanction->update([
                'seen' => 'seen'
            ]);
            return $this->returnDataArray($sanction);
        }else{
            return $this->returnError('not fond Sanction');
        }
    }
}
