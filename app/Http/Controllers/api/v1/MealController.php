<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\meal\CreateMealRequest;
use App\Http\Requests\api\v1\meal\UpdateCreateMealRequest;
use App\Http\Requests\api\v1\meal\UserMealsRequest;
use App\Models\Accessories;
use App\Models\AdditionCategory;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\Meal;
use App\Models\Translate;
use App\Models\User;
use App\Models\UserAddress;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MealController extends Controller
{
    use  HelperTrait;

    public function gen_code(Request $request)
    {
        $request->merge(['user_id' => auth()->id()]);
        $validatedData = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'user_id' => ['required'],
        ], [
//            'category_id.required' => 'Please enter category id.',
//            'email.required' => 'Please enter your email address.',
//            'email.email' => 'Please enter a valid email address.',
//            'email.unique' => 'This email address is already taken.',
//            'password.required' => 'Please enter a password.',
//            'password.min' => 'Your password must be at least 8 characters long.',
        ]);

        $meal = Meal::latest()->first();
        $id = $meal->id ?? 0;
        $code = $validatedData['category_id'] . auth()->id() . ($id + 1);
        return $this->returnDataArray([
            'meal_code' => $code,
            'hint' => "Be careful, someone may add a meal at this time.
            So we adopt the code generation in the back-end programming, however, always send it
            You will see an additional message in case the code differs when creating the meal",
        ]);

    }

    public function list()
    {
        $meals = Meal::whereUserId(auth()->id())->simplePaginate();
        return $this->returnPaginateData($meals);
    }

    public function store(CreateMealRequest $request)
    {
        $this->authorize('create', Meal::class);
        $meal = Meal::latest()->first();
        $id = $meal->id ?? 0;
        $pref = $request->category_id . auth()->id();
        $code = $pref . ($id + 1);

        $flag_code = false;
        if ($request->code != $code) {
            $flag_code = true;
        }
        $data = $request->safe()->except('image');
        if ($request->hasFile('image')) {
            $data['image'] = $this->saveImage($request->image, 'uploads/meal');
        }
        $data['code'] = $code;
        $Meal = Meal::create($data);
        $Meal->accessories()->sync($request->accessories);
        $Meal->additions()->sync($request->additions);
        if ($request->hasFile('images')) {
            $imagesArray = [];
            foreach ($request->images as $image) {
                $item = [];
                $item['image'] = $this->saveImage($image, 'uploads/meal');
                $item['user_id'] = Auth::id();
                $item['type'] = 'meal';
                $item['meal_id'] = $Meal->id;
                $imagesArray[] = $item;
            }
            Gallery::insert($imagesArray);
        }
        $Meal->load([
            'accessories' => function ($q) {
                $q->Trans();
            }
            ,'additions'
            ,'images'
        ]);
        if ($flag_code) {
            return $this->returnDataArrayWithOther($Meal, [
                "The meal code has been changed as a result of the difference in generating the code with the difference in time , The new code is:$code"
            ], 'hint');
        }
        return $this->returnDataArray($Meal);
    }

    public function accessories()
    {
        $lang = app()->getLocale();
        $accessories = Accessories::Trans()->get();
        return $this->returnDataArray($accessories);
    }

    public function get(Request $request)
    {
        $meal = Meal::whereUserId(auth()->id())->findOrFail($request->id);
        $meal->load([
            'accessories' => function ($q) {
                $q->Trans();
            }
            ,'additions'
            ,'images'
        ]);
        $meal->setCategoriesAdditions();
        return $this->returnDataArray($meal);
    }

    public function update(UpdateCreateMealRequest $request, Meal $meal)
    {
        $meal = Meal::whereUserId($request->user_id)->findOrFail($request->id);
        $meal->update($request->validated());
        $this->authorize('update', $meal);
        $data = $request->safe()->except('image', 'user_id');
        if ($request->hasFile('image')) {
            $data['image'] = $this->saveImage($request->image, 'uploads/');
        }
        $meal->update($data);
        $meal->accessories()->sync($request->accessories??[]);
        $meal->additions()->sync($request->additions??[]);

        if ($request->hasFile('images')) {
            $imagesArray = [];
            foreach ($request->images as $image) {
                $item = [];
                $item['image'] = $this->saveImage($image, 'uploads/meal');
                $item['user_id'] = Auth::id();
                $item['type'] = 'meal';
                $item['meal_id'] = $meal->id;
                $imagesArray[] = $item;
            }
            Gallery::insert($imagesArray);
        }
        $meal->load([
            'accessories' => function ($q) {
                $q->Trans();
            }
            ,'additions'
            ,'images'
        ]);
        $meal->setCategoriesAdditions();
        return $this->returnDataArray($meal);
    }

    public function delete(Request $request)
    {
        $count = Meal::whereUserId(auth()->id())->whereId($request->id)->delete();
        if ($count < 1) {
            return $this->returnError('It was not deleted, it may already be deleted or you do not have enough permission');
        }
        return $this->returnSuccess(__('messages.deleted_successfully'));
    }

    public function user_meals(UserMealsRequest $request)
    {
        $latitude = $request->lat;
        $longitude = $request->long;
        $distance = $request->radius ?? 30;
        $meals = Meal::select('meals.*' ,
            'users.name as user_name' ,
            'user_addresses.latitude',
            'user_addresses.longitude'
        )->active()->nearby($latitude, $longitude, $distance)->where(function ($q) use ($request){
            if ($request->filled('is_offer'))
                $q->whereHas('offer');
            if ($request->filled('category_id'))
                $q->where('category_id' , $request->category_id);
            if ($request->filled('min_price'))
                $q->where('price' , '>=' , $request->min_price);
            if ($request->filled('max_price'))
                $q->where('price' , '<=' , $request->max_price);
            if ($request->filled('days'))
                $q->where('days' , 'LIKE', '%' . $request->days . '%');
            if ($request->filled('type'))
                $q->where('type' , $request->type);
            if ($request->filled('chafe_name'))
                $q->whereHas('user' , function ($q1) use($request){
                    $q1->where('type' , 'chef');
                    $q1->where('account_status' , 'active');
                    $q1->where('name' , 'LIKE', '%' . $request->chafe_name . '%');
                });
        })->join('user_addresses', 'meals.user_id', '=', 'user_addresses.user_id')
            ->simplePaginate(10);
       return $this->returnDataArray($meals ,'Success Get All Meals');
    }
}
