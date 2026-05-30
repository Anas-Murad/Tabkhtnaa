<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Traits\HelperTrait;

class LanguagesController extends Controller
{
    use HelperTrait;

    /**
     * Supported app locales (must match setLangMiddleware).
     */
    public function index()
    {
        $data = [
            [
                'code' => 'ar',
                'name' => 'العربية',
                'native' => 'العربية',
                'rtl' => true,
            ],
            [
                'code' => 'en',
                'name' => 'English',
                'native' => 'English',
                'rtl' => false,
            ],
             [
                'code' => 'fr',
                'name' => 'Français',
                'native' => 'Français',
                'rtl' => false,
            ],
            [
                'code' => 'tr',
                'name' => 'Turkish',
                'native' => 'Türkçe',
                'rtl' => false,
            ],
        ];

        return $this->returnDataArray($data, 'Success Get Languages');
    }
}
