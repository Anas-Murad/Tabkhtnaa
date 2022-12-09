<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Translate;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;

class TranslateController extends Controller
{
    use HelperTrait;

    public function getAllTranslate()
    {
        $translates = Translate::all();
        return $this->returnDataArray($translates,'Success Get All Translate');
    }
}
