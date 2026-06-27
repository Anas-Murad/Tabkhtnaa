<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\Cart\CreateCartRequest;
use App\Models\Cart;
use App\Models\CartMeal;
use App\Traits\HelperTrait;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CartController extends Controller
{
    use HelperTrait ;


    public function list(Request $request)
    {
        $user_id =  auth()->id() ;
        $cart = Cart::whereUserId( $user_id )->latest()->first();
        if ($cart){
            return $this->returnDataArray(
                $cart->getUpdatedData($request->query('delivery_type'))
            );
        }
        else{
            return $this->returnError(__('messages.no data found'));
        }
    }

    public function accessories()
    {
        return (new MealController)->accessories();
    }
    public function store(CreateCartRequest $request)
    {


        $cartData = $request->safe(['user_id', 'maker_id',]);
        $accessoriesData = $request->safe()->accessories ?? [];
        $additionsData = $request->safe()->additions ?? [];
        $mealData = $request->only(['user_id', 'meal_id', 'quantity', 'note',]);

       //delete old all carts
        Cart::whereUserId($cartData['user_id'])->where('maker_id' ,'!=',$cartData['maker_id'] )->delete();

        $cart = Cart::firstOrCreate($cartData);

        $mealData['cart_id'] = $cart->id;


        //  checkOldMeal
        $checkOldMeal = CartMeal::query();
        $checkOldMeal->whereUserId($request->user_id)
            ->whereCartId($cart->id)
            ->whereMealId($mealData['meal_id']);

        if ($request->filled('accessories')) {
            $checkOldMeal->whereHas('mealAccessories', function ($q) use ($accessoriesData) {
                $q->whereIn('cart_meal_accessories.accessory_id', $accessoriesData);
            }) ->whereDoesntHave('mealAccessories', function ($q) use ($accessoriesData) {
                    $q->whereNotIn('accessory_id', $accessoriesData);
                });

        } else {
            $checkOldMeal->whereDoesntHave('mealAccessories');
        }
        if ($request->filled('additions')) {
            $checkOldMeal->whereHas('mealAdditions', function ($q) use ($additionsData) {
                $q->whereIn('addition_id', $additionsData);
            }) ->whereDoesntHave('mealAdditions', function ($q) use ($additionsData) {
                $q->whereNotIn('addition_id', $additionsData);
            });
        } else {
            $checkOldMeal->whereDoesntHave('mealAdditions');
        }

        $OldMeal =$checkOldMeal->first();

        if ($OldMeal){
            $OldMeal->increment('quantity'  ,  $mealData['quantity']);
            if ($request->filled('accessories')) {
                $OldMeal->accessories()->sync($accessoriesData);
            }
            if ($request->filled('additions')) {
                $OldMeal->additions()->sync($additionsData);
            }
        }else{
           $CartMeal = CartMeal::create($mealData) ;
            $CartMeal->accessories()->sync($accessoriesData);
            $CartMeal->additions()->sync($additionsData);
        }
        return $this->returnDataArray($cart->getUpdatedData());
    }
    public function update_quantity(Request $request )
    {

        $user_id =  auth()->id() ;
        $request->validate([
            'cart_item_id' => [
                'required',
                'numeric',
                Rule::exists('cart_meals', 'id')->where(function (Builder $query) use($user_id) {
                    return $query
                        ->where('user_id', $user_id);
                }),
            ],
            'quantity' =>[ 'required', 'integer' ,'min:1' ]
        ]);
        $id =request()->cart_item_id ;
        CartMeal::whereId($id) ->  whereUserId($user_id)->  update([
            'quantity' => $request->quantity
        ]);
        $cart = Cart::whereUserId( $user_id )->latest()->first();
        if ($cart){
            return $this->returnDataArray($cart->getUpdatedData() , __('messages.cart item was deleted successfully'));
        }
        return $this->returnSuccess( __('messages.cart item was deleted successfully') ); //Impossible case
    }
    public function delete_item(Request $request )
    {
        $user_id =  auth()->id() ;
        $request->validate([
            'cart_item_id' => ['required', 'numeric',
                Rule::exists('cart_meals', 'id')->where(function (Builder $query) use($user_id) {
                    return $query
                        ->where('user_id', $user_id);
                }),
            ],
        ]);
       $id =  request()->cart_item_id ;
        CartMeal::whereUserId( $user_id )->destroy($id);
        $cart = Cart::whereUserId( $user_id )->latest()->first();
        if ($cart){
            return $this->returnDataArray($cart->getUpdatedData() , __('messages.cart item was deleted successfully'));
        }
        return $this->returnSuccess( __('messages.cart item was deleted successfully') ); //Impossible case
    }
    public function delete_all( )
    {
        $user_id =  auth()->id() ;
         Cart::whereUserId( $user_id )->delete();
         CartMeal::whereUserId( $user_id )->delete();
         return $this->returnError(__('messages.cart was deleted successfully'));
    }

    public function set_accessories(Request $request)
    {
        $user_id = auth()->id();
        $request->validate([
            'accessories' => ['nullable', 'array'],
            'accessories.*' => ['integer', 'exists:accessories,id'],
        ]);

        $cart = Cart::whereUserId($user_id)->latest()->firstOrFail();
        $accessories = $request->input('accessories', []);

        foreach ($cart->meals as $mealItem) {
            $mealItem->accessories()->sync($accessories);
        }

        return $this->returnDataArray($cart->getUpdatedData($request->query('delivery_type')));
    }


}
