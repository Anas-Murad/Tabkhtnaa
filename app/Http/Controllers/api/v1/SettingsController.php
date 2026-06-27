<?php

namespace App\Http\Controllers\api\v1;

use App\HelperClasses\BusinessSettingHelper;
use App\Http\Controllers\Controller;
use App\Traits\HelperTrait;

class SettingsController extends Controller
{
    use HelperTrait;

    public function index()
    {
        return $this->returnDataArray(BusinessSettingHelper::appSettings());
    }
}
