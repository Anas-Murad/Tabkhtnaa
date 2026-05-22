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
        $lang = app()->getLocale();
        if (!in_array($lang, ['ar', 'en', 'fr', 'tr'], true)) {
            $lang = 'ar';
        }

        $map = [];
        foreach (Translate::all() as $row) {
            $value = $row->{$lang} ?? $row->en ?? $row->ar ?? $row->key;
            $map[$row->key] = $value;
        }

        return $this->returnDataArray($map, 'Success Get All Translate');
    }


}
