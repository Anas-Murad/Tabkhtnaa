<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    use HelperTrait;

    public function term_and_condition(Request $request)
    {
        $term_and_condition = Content::select(
            'id',
            'key',
            $request->lang ? $request->lang.'_text as text' : 'en_text as text',
        )->where('key' , 'term_and_condition')->first();

        if (!$term_and_condition) {
            return $this->returnDataArray(['text' => '']);
        }

        return $this->returnDataArray($term_and_condition);
    }
}
