<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use HelperTrait;


    public function getCategory()
    {
        $categorys = Category::get();
        return $this->returnDataArray($categorys , 'Success Get All Category');
    }
}
