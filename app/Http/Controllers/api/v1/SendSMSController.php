<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SendSMSController extends Controller
{
    use HelperTrait;

    public function sendSms()
    {
        $user = Auth::user();
        $random = rand(1000,9999);
        $user->update([
            'sms_verify' => $random
        ]);
        return $this->returnDataArray($user);
    }
}
