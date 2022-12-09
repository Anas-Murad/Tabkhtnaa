<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Traits\HelperTrait;

class CountriesController extends Controller
{
    use  HelperTrait;

    public function index()
    {
        $data = Country::ApiData()->get();
        return $this->returnDataArray($data);
    }

}
